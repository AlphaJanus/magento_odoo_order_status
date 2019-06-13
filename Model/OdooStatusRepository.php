<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-11
 * Time: 11:25
 */

namespace Netzexpert\OdooOrderStatus\Model;

use GuzzleHttp\Exception\ConnectException;
use Magento\Framework\Exception\CouldNotSaveException;
use Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusInterface;
use Netzexpert\OdooOrderStatus\Api\OdooStatusRepositoryInterface;

class OdooStatusRepository implements OdooStatusRepositoryInterface
{
    /** @var ResourceModel\OrderStatus  */
    private $orderStatus;

    /**
     * @var Statusfactory
     */
    private $statusFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $date;

    public function __construct(
        \Netzexpert\OdooOrderStatus\Model\ResourceModel\OrderStatus $orderStatus,
        \Netzexpert\OdooOrderStatus\Model\OrderStatusFactory $statusfactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date
    )
    {
        $this->orderStatus      = $orderStatus;
        $this->statusFactory    = $statusfactory;
        $this->date             = $date;
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
        if(!$status->getId()) {
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
            $this->orderStatus->save($odooOrderStatus);
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
        }
        catch (\Exception $e) {
            throw new CouldNotSaveException(__('Unable to order status'));
        }
        return $orderId;
    }
}