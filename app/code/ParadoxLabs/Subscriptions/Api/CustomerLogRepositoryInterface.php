<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Api;

/**
 * Interface CustomerLogRepositoryInterface
 *
 * @package ParadoxLabs\Subscriptions\Api
 */
interface CustomerLogRepositoryInterface
{
    /**
     * Retrieve logs matching the specified criteria.
     *
     * @param int $customerId
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList($customerId, \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
