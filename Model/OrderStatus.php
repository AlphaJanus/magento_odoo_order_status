<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-06
 * Time: 11:54
 */

namespace Netzexpert\OdooOrderStatus\Model;

use Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusInterface;

class OrderStatus extends \Magento\Framework\Model\AbstractModel implements OdooOrderStatusInterface
{
    const CACHE_TAG = 'order_status';

    public function _construct()
    {
        $this->_init('Netzexpert\OdooOrderStatus\Model\ResourceModel\OrderStatus');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_'. $this->getId()];
    }

    /**
     * @inheritDoc
     */
    public function getId(): ?int
    {
        return $this->getData(self::ID);
    }

    /**
     * Set Id
     * @param mixed $entityId
     * @return \Magento\Framework\Model\AbstractModel|OdooOrderStatusInterface|OrderStatus
     */
    public function setId($entityId)
    {
        return $this->setData(self::ID, $entityId);
    }

    /**
     * Get Order Id
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * Set Order Id
     * @param $orderId
     * @return OdooOrderStatusInterface|OrderStatus
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * Get Date
     * @return string
     */
    public function getDate(): string
    {
        return $this->getData(self::DATE);
    }

    /**
     * Set Date
     * @param $date
     * @return OdooOrderStatusInterface|OrderStatus
     */
    public function setDate($date)
    {
        return $this->setData(self::DATE, $date);

    }

    /**
     * Get Status
     * @return string
     */
    public function getStatus(): string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set Status
     * @param $status
     * @return OdooOrderStatusInterface|OrderStatus
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

}