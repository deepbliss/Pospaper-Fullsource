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
 * SubscriptionRepositoryInterface
 *
 * @api
 */
interface SubscriptionRepositoryInterface
{
    /**
     * Save subscription.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\SubscriptionInterface $subscription);

    /**
     * Retrieve subscription.
     *
     * @param int $subscriptionId
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($subscriptionId);

    /**
     * Retrieve subscription.
     *
     * @param int $subscriptionId
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function load($subscriptionId);

    /**
     * Retrieve subscriptions matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete subscription.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(Data\SubscriptionInterface $subscription);

    /**
     * Delete subscription by ID.
     *
     * @param int $subscriptionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($subscriptionId);

    /**
     * Run a billing for the given subscription.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function bill(Data\SubscriptionInterface $subscription);

    /**
     * Run a billing for the given subscription ID.
     *
     * @param int $subscriptionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function billById($subscriptionId);
}
