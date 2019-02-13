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
 * LogRepositoryInterface
 *
 * @api
 */
interface LogRepositoryInterface
{
    /**
     * Save log.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\LogInterface $log
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\LogInterface $log);

    /**
     * Retrieve log.
     *
     * @param int $logId
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($logId);

    /**
     * Retrieve log.
     *
     * @param int $logId
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function load($logId);

    /**
     * Retrieve logs matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete log.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\LogInterface $log
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\LogInterface $log);

    /**
     * Delete log by ID.
     *
     * @param int $logId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($logId);
}
