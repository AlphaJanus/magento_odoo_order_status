<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-06
 * Time: 11:37
 */

namespace Netzexpert\OdooOrderStatus\Model\ResourceModel;


class OrderStatus extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('odoo_order_status_history', 'id');
    }
}