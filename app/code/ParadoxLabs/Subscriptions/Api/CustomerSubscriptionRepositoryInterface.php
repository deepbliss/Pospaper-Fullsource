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
 * Interface CustomerSubscriptionRepositoryInterface
 *
 * @package ParadoxLabs\Subscriptions\Api
 */
interface CustomerSubscriptionRepositoryInterface
{
    /**
     * Save subscription.
     *
     * @param int $customerId The customer ID.
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save($customerId, \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription);

    /**
     * Retrieve subscription.
     *
     * @param int $customerId The customer ID.
     * @param int $subscriptionId
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($customerId, $subscriptionId);

    /**
     * Retrieve subscriptions matching the specified criteria.
     *
     * @param int $customerId The customer ID.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList($customerId, \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete subscription by ID.
     *
     * @param int $customerId The customer ID.
     * @param int $subscriptionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($customerId, $subscriptionId);
}
