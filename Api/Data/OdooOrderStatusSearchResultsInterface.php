<?php
/**
 * Created by Andrew Stepanchuk.
 * Date: 18.07.19
 * Time: 15:04
 */

namespace Netzexpert\OdooOrderStatus\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface OdooOrderStatusSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return OdooOrderStatusInterface[]
     */
    public function getItems();

    /**
     * @param OdooOrderStatusInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
