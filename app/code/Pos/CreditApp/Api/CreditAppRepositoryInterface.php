<?php


namespace Pos\CreditApp\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CreditAppRepositoryInterface
{

    /**
     * Save CreditApp
     * @param \Pos\CreditApp\Api\Data\CreditAppInterface $creditApp
     * @return \Pos\CreditApp\Api\Data\CreditAppInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Pos\CreditApp\Api\Data\CreditAppInterface $creditApp
    );

    /**
     * Retrieve CreditApp
     * @param string $creditappId
     * @return \Pos\CreditApp\Api\Data\CreditAppInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($creditappId);

    /**
     * Retrieve CreditApp matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Pos\CreditApp\Api\Data\CreditAppSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete CreditApp
     * @param \Pos\CreditApp\Api\Data\CreditAppInterface $creditApp
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Pos\CreditApp\Api\Data\CreditAppInterface $creditApp
    );

    /**
     * Delete CreditApp by ID
     * @param string $creditappId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($creditappId);
}
