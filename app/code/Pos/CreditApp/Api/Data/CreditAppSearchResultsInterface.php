<?php


namespace Pos\CreditApp\Api\Data;

interface CreditAppSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get CreditApp list.
     * @return \Pos\CreditApp\Api\Data\CreditAppInterface[]
     */
    public function getItems();

    /**
     * Set id list.
     * @param \Pos\CreditApp\Api\Data\CreditAppInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
