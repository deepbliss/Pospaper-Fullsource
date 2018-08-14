<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Api;

use Aheadworks\RewardPoints\Api\Data\SpendRateInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * @api
 */
interface SpendRateRepositoryInterface
{
    /**
     * Retrieve spend rate by id
     *
     * @param  int $id
     * @return SpendRateInterface
     */
    public function getById($id);

    /**
     * Retrieve spend rate
     *
     * @param  int $customerGroupId
     * @param  int $lifetimeSalesAmount
     * @param  int|null $websiteId
     * @param  bool $min
     * @return SpendRateInterface
     */
    public function get($customerGroupId, $lifetimeSalesAmount, $websiteId = null, $min = false);

    /**
     * Save spend rate
     *
     * @param  SpendRateInterface $spendRate
     * @return SpendRateInterface
     */
    public function save(SpendRateInterface $spendRate);

    /**
     * Delete spend rate by id
     *
     * @param  int $id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * Delete spend rate
     *
     * @param  SpendRateInterface $spendRate
     * @return boolean
     */
    public function delete(SpendRateInterface $spendRate);

    /**
     * Retrieve spend rates matching the specified criteria
     *
     * @param  SearchCriteriaInterface $criteria
     * @return SpendRateSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria);
}
