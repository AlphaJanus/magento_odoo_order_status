<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-11
 * Time: 11:19
 */

namespace Netzexpert\OdooOrderStatus\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusInterface;
use Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusSearchResultsInterface;

interface OdooStatusRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * @param OdooOrderStatusInterface $odooOrderStatus
     * @return bool true on success
     */
    public function delete(OdooOrderStatusInterface $odooOrderStatus);

    /**
     * @param OdooOrderStatusInterface $odooOrderStatus
     * @return $this
     */
    public function save(OdooOrderStatusInterface $odooOrderStatus);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return OdooOrderStatusSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param int $orderId
     * @param string $newStatus
     * @return int
     */
    public function saveNewStatus($orderId, $newStatus);
}
