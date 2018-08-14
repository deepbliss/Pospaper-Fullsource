<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Service;

use Aheadworks\RewardPoints\Api\Data\PointsSummaryInterface;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Aheadworks\RewardPoints\Api\PointsSummaryRepositoryInterface;
use Aheadworks\RewardPoints\Model\DateTime;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\RewardPoints\Model\Source\Transaction\Type as TransactionType;
use Aheadworks\RewardPoints\Model\Config;
use Aheadworks\RewardPoints\Model\Source\SubscribeStatus;
use Magento\Framework\DataObject;

/**
 * Class Aheadworks\RewardPoints\Model\Service\PointsSummaryService
 */
class PointsSummaryService
{
    /**
     * @var PointsSummaryRepositoryInterface
     */
    private $pointsSummaryRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var []
     */
    private $summaryCache;

    /**
     * @param PointsSummaryRepositoryInterface $pointsSummaryRepository
     * @param StoreManagerInterface $storeManager
     * @param DateTime $dateTime
     * @param Config $config
     */
    public function __construct(
        PointsSummaryRepositoryInterface $pointsSummaryRepository,
        StoreManagerInterface $storeManager,
        DateTime $dateTime,
        Config $config
    ) {
        $this->pointsSummaryRepository = $pointsSummaryRepository;
        $this->storeManager = $storeManager;
        $this->dateTime = $dateTime;
        $this->config = $config;
    }

    /**
     * Retrieve customer reward points balance
     *
     * @param int $customerId
     * @return int
     */
    public function getCustomerRewardPointsBalance($customerId)
    {
        $pointsSummary = $this->getPointsSymmary($customerId);
        return (int) $pointsSummary->getPoints();
    }

    /**
     * Retrieve customer daily review points
     *
     * @param int $customerId
     * @return int
     */
    public function getCustomerDailyReviewPoints($customerId)
    {
        $pointsSummary = $this->getPointsSymmary($customerId);
        return (int) $pointsSummary->getDailyReviewPoints();
    }

    /**
     * Retrieve customer daily review date
     *
     * @param int $customerId
     * @return string
     */
    public function getCustomerDailyReviewPointsDate($customerId)
    {
        $pointsSummary = $this->getPointsSymmary($customerId);
        return $pointsSummary->getDailyReviewPointsDate();
    }

    /**
     * Retrieve customer daily share points
     *
     * @param int $customerId
     * @return int
     */
    public function getCustomerDailySharePoints($customerId)
    {
        $pointsSummary = $this->getPointsSymmary($customerId);
        return (int) $pointsSummary->getDailySharePoints();
    }

    /**
     * Retrieve customer monthly share points
     *
     * @param int $customerId
     * @return string
     */
    public function getCustomerMonthlySharePoints($customerId)
    {
        $pointsSummary = $this->getPointsSymmary($customerId);
        return $pointsSummary->getMonthlySharePoints();
    }

    /**
     * Retrieve customer daily share date
     *
     * @param int $customerId
     * @return string
     */
    public function getCustomerDailySharePointsDate($customerId)
    {
        $pointsSummary = $this->getPointsSymmary($customerId);
        return $pointsSummary->getDailySharePointsDate();
    }

    /**
     * Return if Customer is already Awarded for Newsletter Signup
     *
     * @param int $customerId
     * @return boolean
     */
    public function isAwardedForNewsletterSignup($customerId)
    {
        $pointsSummary = $this->getPointsSymmary($customerId);
        return (boolean) $pointsSummary->getIsAwardedForNewsletterSignup();
    }

    /**
     * Retrieve customer is balance update notification status
     *
     * @param int $customerId
     * @param int $websiteId
     * @return int
     */
    public function getCustomerBalanceUpdateNotificationStatus($customerId, $websiteId = null)
    {
        $pointsSummary = $this->getPointsSymmary($customerId);
        $defaultNotification = $this->config->isSubscribeCustomersToNotificationsByDefault($websiteId)
            ? SubscribeStatus::SUBSCRIBED
            : SubscribeStatus::NOT_SUBSCRIBED;
        return $pointsSummary->getBalanceUpdateNotificationStatus() == null
            ? $defaultNotification
            : $pointsSummary->getBalanceUpdateNotificationStatus();
    }

    /**
     * Retrieve customer is expiration notification status
     *
     * @param int $customerId
     * @param int $websiteId
     * @return int
     */
    public function getCustomerExpirationNotificationStatus($customerId, $websiteId = null)
    {
        $pointsSummary = $this->getPointsSymmary($customerId);
        $defaultNotification = $this->config->isSubscribeCustomersToNotificationsByDefault($websiteId)
            ? SubscribeStatus::SUBSCRIBED
            : SubscribeStatus::NOT_SUBSCRIBED;
        return $pointsSummary->getExpirationNotificationStatus() == null
            ? $defaultNotification
            : $pointsSummary->getExpirationNotificationStatus();
    }

    /**
     * Reset daily review data
     *
     * @param int $customerId
     * @return boolean
     * @throws CouldNotSaveException
     */
    public function resetPointsSummaryDailyReview($customerId)
    {
        $pointsSummary = $this->setupPointsSummary($customerId);
        return $this->savePointsSummary($pointsSummary);
    }

    /**
     * Reset daily share data
     *
     * @param int $customerId
     * @return boolean
     * @throws CouldNotSaveException
     */
    public function resetPointsSummaryDailyShare($customerId)
    {
        $pointsSummary = $this->setupPointsSummary($customerId);
        return $this->savePointsSummary($pointsSummary);
    }

    /**
     * Add points summary to customer after each transaction
     *
     * @param TransactionInterface $transaction
     * @return boolean
     * @throws CouldNotSaveException
     */
    public function addPointsSummaryToCustomer(TransactionInterface $transaction)
    {
        $transactionBalance = $transaction->getBalance();
        $pointsSummary = $this->setupPointsSummary(
            $transaction->getCustomerId(),
            $transaction,
            true
        );

        if ($transaction->getType() == TransactionType::POINTS_REWARDED_FOR_REVIEW_APPROVED_BY_ADMIN) {
            $pointsSummary->setDailyReviewPoints(
                (int) $pointsSummary->getDailyReviewPoints() + $transactionBalance
            );
        }

        if ($transaction->getType() == TransactionType::POINTS_REWARDED_FOR_SHARES) {
            $pointsSummary->setDailySharePoints(
                (int) $pointsSummary->getDailySharePoints() + $transaction->getBalance()
            );
            $pointsSummary->setMonthlySharePoints(
                (int) $pointsSummary->getMonthlySharePoints() + $transaction->getBalance()
            );
        }

        if ($transaction->getType() == TransactionType::POINTS_REWARDED_FOR_NEWSLETTER_SIGNUP) {
            $pointsSummary->setIsAwardedForNewsletterSignup(true);
        }

        return $this->savePointsSummary($pointsSummary);
    }

    /**
     * Update customer summary
     *
     * @param DataObject $data
     * @return boolean
     * @throws CouldNotSaveException
     */
    public function updateCustomerSummary($data)
    {
        $summary = $this->setupPointsSummary($data->getCustomerId(), $data);
        return $this->savePointsSummary($summary);
    }

    /**
     * Retrieve points summary instance
     *
     * @param int $customerId
     * @return PointsSummaryInterface
     */
    private function getPointsSymmary($customerId)
    {
        if (isset($this->summaryCache[$customerId])) {
            return $this->summaryCache[$customerId];
        }

        try {
            $pointsSymmary = $this->pointsSummaryRepository->get($customerId);
        } catch (NoSuchEntityException $e) {
            $pointsSymmary = $this->pointsSummaryRepository->create();
        }
        $this->summaryCache[$customerId] = $pointsSymmary;

        return $pointsSymmary;
    }

    /**
     * Setup points summary data model
     *
     * @param int $customerId
     * @param DataObject|TransactionInterface|null $data
     * @param bool isTransaction
     * @return PointsSummaryInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function setupPointsSummary($customerId, $data = null, $isTransaction = false)
    {
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        if ($isTransaction && null != $data) {
            $websiteId = $data->getWebsiteId();
        }

        /** @var $pointsSummary PointsSummaryInterface **/
        $pointsSummary = $this->getPointsSymmary($customerId);
        if (!$pointsSummary->getSummaryId()) {
            $pointsSummary->setWebsiteId($websiteId);
            $pointsSummary->setCustomerId($customerId);
            $defaultNotification = $this->config->isSubscribeCustomersToNotificationsByDefault($websiteId)
                ? SubscribeStatus::SUBSCRIBED
                : SubscribeStatus::NOT_SUBSCRIBED;
            $pointsSummary->setBalanceUpdateNotificationStatus($defaultNotification);
            $pointsSummary->setExpirationNotificationStatus($defaultNotification);
        }

        if ($isTransaction && null != $data) {
            $transactionBalance = $data->getBalance();
            $pointsSummary->setPoints(
                (int)$pointsSummary->getPoints() + $transactionBalance
            );
            if ($transactionBalance > 0) {
                $transactionTypes = [
                    TransactionType::ORDER_CANCELED,
                    TransactionType::REIMBURSE_OF_SPENT_REWARD_POINTS
                ];
                if (in_array($data->getType(), $transactionTypes)) {
                    $pointsSummary->setPointsSpend(
                        (int)$pointsSummary->getPointsSpend() - $transactionBalance
                    );
                } else {
                    $pointsSummary->setPointsEarn(
                        (int)$pointsSummary->getPointsEarn() + $transactionBalance
                    );
                }
            } else {
                $transactionTypes = [
                    TransactionType::CANCEL_EARNED_POINTS_FOR_REFUND_ORDER
                ];
                if (in_array($data->getType(), $transactionTypes)) {
                    $pointsSummary->setPointsEarn(
                        (int)$pointsSummary->getPointsEarn() + $transactionBalance
                    );
                } else {
                    $pointsSummary->setPointsSpend(
                        (int)$pointsSummary->getPointsSpend() + abs($transactionBalance)
                    );
                }
            }
        }

        if (null != $data && null != $data->getBalanceUpdateNotificationStatus()) {
            $pointsSummary->setBalanceUpdateNotificationStatus($data->getBalanceUpdateNotificationStatus());
        }

        if (null != $data && null != $data->getExpirationNotificationStatus()) {
            $pointsSummary->setExpirationNotificationStatus($data->getExpirationNotificationStatus());
        }

        if (!$this->dateTime->isTodayDate($pointsSummary->getDailyReviewPointsDate())) {
            $pointsSummary->setDailyReviewPoints(0);
            $pointsSummary->setDailyReviewPointsDate($this->dateTime->getTodayDate());
        }

        if (!$this->dateTime->isTodayDate($pointsSummary->getDailySharePointsDate())) {
            $pointsSummary->setDailySharePoints(0);
            $pointsSummary->setDailySharePointsDate($this->dateTime->getTodayDate());
        }

        if (!$this->dateTime->isCurrentMonthDate($pointsSummary->getDailySharePointsDate())) {
            $pointsSummary->setMonthlySharePoints(0);
        }

        return $pointsSummary;
    }

    /**
     * @param PointsSummaryInterface $pointsSummary
     * @throws CouldNotSaveException
     * @return boolean
     */
    private function savePointsSummary(PointsSummaryInterface $pointsSummary)
    {
        $result = false;
        try {
            $result = $this->pointsSummaryRepository->save($pointsSummary);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $result;
    }
}
