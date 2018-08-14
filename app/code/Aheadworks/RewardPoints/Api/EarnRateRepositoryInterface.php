<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Api;

use Aheadworks\RewardPoints\Api\Data\EarnRateInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * @api
 */
interface EarnRateRepositoryInterface
{
    /**
     * Retrieve earn rate by id
     *
     * @param  int $id
     * @return EarnRateInterface
     */
    public function getById($id);

    /**
     * Retrieve earn rate
     *
     * @param  int $customerGroupId
     * @param  int $lifetimeSalesAmount
     * @param  string $websiteId
     * @return EarnRateInterface
     */
    public function get($customerGroupId, $lifetimeSalesAmount, $websiteId = null);

    /**
     * Save earn rate
     *
     * @param  EarnRateInterface $earnRate
     * @return EarnRateInterface
     */
    public function save(EarnRateInterface $earnRate);

    /**
     * Delete earn rate by id
     *
     * @param  int $id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * Delete earn rate
     *
     * @param EarnRateInterface $earnRate
     * @return boolean
     */
    public function delete(EarnRateInterface $earnRate);

    /**
     * Retrieve earn rates matching the specified criteria
     *
     * @param SearchCriteriaInterface $criteria
     * @return EarnRateSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria);
}
