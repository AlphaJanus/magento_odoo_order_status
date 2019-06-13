<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-06
 * Time: 12:23
 */

namespace Netzexpert\OdooOrderStatus\Model\ResourceModel\OrderStatus;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('Netzexpert\OdooOrderStatus\Model\OrderStatus', '\Netzexpert\OdooOrderStatus\Model\ResourceModel\OrderStatus');
    }
}