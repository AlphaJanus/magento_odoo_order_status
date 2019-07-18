<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-06
 * Time: 11:39
 */

namespace Netzexpert\OdooOrderStatus\Api\Data;

interface OdooOrderStatusInterface
{
    const ID          = 'id';
    const ORDER_ID    = 'order_id';
    const DATE        = 'date';
    const STATUS      = 'status';

    /**
     * Get Id
     * @return int | null
     */
    public function getId();

    /**
     * Set Id
     * @param $entityId
     * @return \Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusInterface
     */
    public function setId($entityId);

    /**
     * Get Order Id
     * @return int
     */
    public function getOrderId(): int;

    /**
     * Set Order Id
     * @return \Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusInterface
     */
    public function setOrderId($orderId);

    /**
     * Get Date
     * @return string
     */
    public function getDate(): string;

    /**
     * Set Date
     * @param $date
     * @return \Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusInterface
     */
    public function setDate($date);

    /**
     * Get Status
     * @return string
     */
    public function getStatus(): string;

    /**
     * Set Status
     * @param $status
     * @return \Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusInterface
     */
    public function setStatus($status);
}
