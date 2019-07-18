<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-10
 * Time: 11:14
 */

namespace Netzexpert\OdooOrderStatus\Plugin\Sales\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Netzexpert\OdooOrderStatus\Api\OdooStatusRepositoryInterface;
use Netzexpert\OdooOrderStatus\Model\ResourceModel\OrderStatus\Collection;
use Netzexpert\OdooOrderStatus\Model\ResourceModel\OrderStatus\CollectionFactory;

use Magento\Sales\Model\Order;

class OrderPlugin
{

    /** @var OrderExtensionFactory  */
    private $orderExtensionFactory;

    private $odooStatusRepository;

    private $searchCriteriaBuilder;

    /**
     * OrderPlugin constructor.
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param OdooStatusRepositoryInterface $odooStatusRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        OdooStatusRepositoryInterface $odooStatusRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->orderExtensionFactory    = $orderExtensionFactory;
        $this->odooStatusRepository     = $odooStatusRepository;
        $this->searchCriteriaBuilder    = $searchCriteriaBuilder;
    }

    /**
     * @param Order $orderModel
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterLoad(Order $orderModel, $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $order->getEntityId())->create();
        $odooStatusesList = $this->odooStatusRepository->getList($searchCriteria);
        $extensionAttributes->setOdooOrderStatuses($odooStatusesList->getItems());
        return $order;
    }
}