<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-10
 * Time: 11:14
 */

namespace Netzexpert\OdooOrderStatus\Plugin\Sales\Model;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Netzexpert\OdooOrderStatus\Model\ResourceModel\OrderStatus\Collection;
use Netzexpert\OdooOrderStatus\Model\ResourceModel\OrderStatus\CollectionFactory;

use Magento\Sales\Model\Order;

class OrderPlugin
{
    /** @var CollectionFactory  */
    private $collectionFactory;

    /** @var OrderExtensionFactory  */
    private $orderExtensionFactory;

    /**
     * OrderPlugin constructor.
     * @param CollectionFactory $collectionFactory
     * @param OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->collectionFactory        = $collectionFactory;
        $this->orderExtensionFactory    = $orderExtensionFactory;
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
        /** @var Collection $statusCollection */
        $statusCollection = $this->collectionFactory->create();
        $statusCollection->addFieldToFilter('order_id', ['eq' => $order->getEntityId()]);
        $extensionAttributes->setOdooOrderStatuses($statusCollection);
        return $order;
    }
}