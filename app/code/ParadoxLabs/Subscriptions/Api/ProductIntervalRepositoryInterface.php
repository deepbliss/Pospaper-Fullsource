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
 * Interface ProductIntervalRepositoryInterface
 *
 * @api
 */
interface ProductIntervalRepositoryInterface
{
    /**
     * Save interval.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\ProductIntervalInterface $interval);

    /**
     * Retrieve interval.
     *
     * @param int $intervalId
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($intervalId);

    /**
     * Retrieve interval.
     *
     * @param int $intervalId
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function load($intervalId);

    /**
     * Retrieve intervals for a given product ID.
     *
     * @param int $productId
     * @param int|null $storeId
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalSearchResultsInterface
     */
    public function getIntervalsByProductId($productId, $storeId = null);

    /**
     * Retrieve intervals for a given option ID.
     *
     * @param int $optionId
     * @param int|null $storeId
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalSearchResultsInterface
     */
    public function getIntervalsByOptionId($optionId, $storeId = null);

    /**
     * Retrieve intervals matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete interval.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\ProductIntervalInterface $interval);

    /**
     * Delete interval by ID.
     *
     * @param int $intervalId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($intervalId);
}
