<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-11
 * Time: 11:19
 */

namespace Netzexpert\OdooOrderStatus\Api;


interface OdooStatusRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * @param \Magento\Sales\Api\Data\ShipmentInterface $entity
     * @return mixed
     */
    public function delete(\Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusInterface $odooOrderStatus);

    /**
     * @param \Magento\Sales\Api\Data\ShipmentInterface $entity
     * @return mixed
     */
    public function save(\Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusInterface $odooOrderStatus);

    /**
     * @param int $orderId
     * @param string $newStatus
     * @return mixed
     */
    public function saveNewStatus($orderId, $newStatus);

}