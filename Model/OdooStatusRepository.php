<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-11
 * Time: 11:25
 */

namespace Netzexpert\OdooOrderStatus\Model;

use GuzzleHttp\Exception\ConnectException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusInterface;
use Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusSearchResultsInterface;
use Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusSearchResultsInterfaceFactory;
use Netzexpert\OdooOrderStatus\Api\OdooStatusRepositoryInterface;
use Netzexpert\OdooOrderStatus\Model\OrderStatusFactory;
use Netzexpert\OdooOrderStatus\Model\ResourceModel\OrderStatus as OrderStatusResource;
use Netzexpert\OdooOrderStatus\Model\ResourceModel\OrderStatus\CollectionFactory;

class OdooStatusRepository implements OdooStatusRepositoryInterface
{
    /** @var OrderStatusResource  */
    private $orderStatusResource;

    /** @var OrderStatusFactory  */
    private $statusFactory;

    /** @var CollectionFactory  */
    private $statusCollectionFactory;

    /** @var CollectionProcessorInterface  */
    private $collectionProcessor;

    private $searchResultsInterfaceFactory;

    /**
     * @var TimezoneInterface
     */
    private $date;

    public function __construct(
        OrderStatusResource $orderStatusResource,
        OrderStatusFactory $statusFactory,
        CollectionFactory $statusCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        OdooOrderStatusSearchResultsInterfaceFactory $searchResultsInterfaceFactory,
        TimezoneInterface $date
    ) {
        $this->orderStatusResource              = $orderStatusResource;
        $this->statusFactory                    = $statusFactory;
        $this->statusCollectionFactory          = $statusCollectionFactory;
        $this->collectionProcessor              = $collectionProcessor;
        $this->searchResultsInterfaceFactory    = $searchResultsInterfaceFactory;
        $this->date                             = $date;
    }

    /**
     * @param $id
     * @return mixed|OrderStatus|null
     */
    public function get($id)
    {
        /** @var \Netzexpert\OdooOrderStatus\Model\OrderStatus $status */
        $status = $this->statusFactory->create();
        $status->load($id);
        if (!$status->getId()) {
            return null;
        }
        return $status;
    }

    /**
     * @param OdooOrderStatusInterface $odooOrderStatus
     * @return mixed
     * @throws CouldNotSaveException
     * @throws \Magento\Framework\Exception\TemporaryState\CouldNotSaveException
     */
    public function save(OdooOrderStatusInterface $odooOrderStatus)
    {
        try {
            $this->orderStatusResource->save($odooOrderStatus);
        } catch (ConnectException $exception) {
            throw new \Magento\Framework\Exception\TemporaryState\CouldNotSaveException(
                __('Database connection error'),
                $exception,
                $exception->getCode()
            );
        } catch (DeadlockException $exception) {
            throw new \Magento\Framework\Exception\TemporaryState\CouldNotSaveException(
                __('Database deadlock found when trying to get lock'),
                $exception,
                $exception->getCode()
            );
        } catch (LockWaitException $exception) {
            throw new \Magento\Framework\Exception\TemporaryState\CouldNotSaveException(
                __('Database lock wait timeout exceeded'),
                $exception,
                $exception->getCode()
            );
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (LocalizedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__('Unable to order status'), $e);
        }
        return $odooOrderStatus;
    }

    /**
     * @param OdooOrderStatusInterface $odooOrderStatus
     * @return bool|mixed
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(OdooOrderStatusInterface $odooOrderStatus)
    {
        try {
            $this->orderStatus->delete($odooOrderStatus);
            return true;
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __('Unable to remove order %1', $odooOrderStatus->getId())
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->statusCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);
        /** @var OdooOrderStatusSearchResultsInterface $searchResult */
        $searchResult = $this->searchResultsInterfaceFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }


    /**
     * @inheritdoc
     */
    public function saveNewStatus($orderId, $newStatus)
    {
        /** @var OdooOrderStatusInterface $status */
        $status = $this->statusFactory->create();
        $currentDate = $this->date->date()->format('Y-m-d H:i:s');
        $status->setOrderId($orderId)->setStatus($newStatus)->setDate($currentDate);
        try {
            $this->save($status);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Unable to order status'));
        }
        return $status->getId();
    }
}
