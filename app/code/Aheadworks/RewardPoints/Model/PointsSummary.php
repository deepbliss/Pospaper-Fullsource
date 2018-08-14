<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Aheadworks\RewardPoints\Api\Data\PointsSummaryInterface;
use Aheadworks\RewardPoints\Model\ResourceModel\PointsSummary as PointsSummaryResource;

/**
 * Class Aheadworks\RewardPoints\Model\PointsSummary
 */
class PointsSummary extends \Magento\Framework\Model\AbstractModel implements PointsSummaryInterface
{
    /**
     *  {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(PointsSummaryResource::class);
    }

    /**
     *  {@inheritDoc}
     */
    public function setSummaryId($summaryId)
    {
        return $this->setData(self::SUMMARY_ID, $summaryId);
    }

    /**
     *  {@inheritDoc}
     */
    public function getSummaryId()
    {
        return $this->getData(self::SUMMARY_ID);
    }

    /**
     *  {@inheritDoc}
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);
    }

    /**
     *  {@inheritDoc}
     */
    public function getWebsiteId()
    {
        return $this->getData(self::WEBSITE_ID);
    }

    /**
     *  {@inheritDoc}
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     *  {@inheritDoc}
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     *  {@inheritDoc}
     */
    public function setPoints($points)
    {
        return $this->setData(self::POINTS, $points);
    }

    /**
     *  {@inheritDoc}
     */
    public function getPoints()
    {
        return $this->getData(self::POINTS);
    }

    /**
     *  {@inheritDoc}
     */
    public function setPointsEarn($points)
    {
        return $this->setData(self::POINTS_EARN, $points);
    }

    /**
     *  {@inheritDoc}
     */
    public function getPointsEarn()
    {
        return $this->getData(self::POINTS_EARN);
    }

    /**
     *  {@inheritDoc}
     */
    public function setPointsSpend($points)
    {
        return $this->setData(self::POINTS_SPEND, $points);
    }

    /**
     *  {@inheritDoc}
     */
    public function getPointsSpend()
    {
        return $this->getData(self::POINTS_SPEND);
    }

    /**
     *  {@inheritDoc}
     */
    public function setDailyReviewPoints($dailyReviewPoints)
    {
        return $this->setData(self::DAILY_REVIEW_POINTS, $dailyReviewPoints);
    }

    /**
     *  {@inheritDoc}
     */
    public function getDailyReviewPoints()
    {
        return $this->getData(self::DAILY_REVIEW_POINTS);
    }

    /**
     *  {@inheritDoc}
     */
    public function setDailyReviewPointsDate($dailyReviewPointsDate)
    {
        return $this->setData(self::DAILY_REVIEW_POINTS_DATE, $dailyReviewPointsDate);
    }

    /**
     *  {@inheritDoc}
     */
    public function getDailyReviewPointsDate()
    {
        return $this->getData(self::DAILY_REVIEW_POINTS_DATE);
    }

    /**
     *  {@inheritDoc}
     */
    public function setDailySharePoints($dailySharePoints)
    {
        return $this->setData(self::DAILY_SHARE_POINTS, $dailySharePoints);
    }

    /**
     *  {@inheritDoc}
     */
    public function getDailySharePoints()
    {
        return $this->getData(self::DAILY_SHARE_POINTS);
    }

    /**
     *  {@inheritDoc}
     */
    public function setMonthlySharePoints($monthlySharePoints)
    {
        return $this->setData(self::MONTHLY_SHARE_POINTS, $monthlySharePoints);
    }

    /**
     *  {@inheritDoc}
     */
    public function getMonthlySharePoints()
    {
        return $this->getData(self::MONTHLY_SHARE_POINTS);
    }

    /**
     *  {@inheritDoc}
     */
    public function setDailySharePointsDate($dailySharePointsDate)
    {
        return $this->setData(self::DAILY_SHARE_POINTS_DATE, $dailySharePointsDate);
    }

    /**
     *  {@inheritDoc}
     */
    public function getDailySharePointsDate()
    {
        return $this->getData(self::DAILY_SHARE_POINTS_DATE);
    }

    /**
     *  {@inheritDoc}
     */
    public function setIsAwardedForNewsletterSignup($isAwardedForNewsletterSignup)
    {
        return $this->setData(self::IS_AWARDED_FOR_NEWSLETTER_SIGNUP, $isAwardedForNewsletterSignup);
    }

    /**
     *  {@inheritDoc}
     */
    public function getIsAwardedForNewsletterSignup()
    {
        return $this->getData(self::IS_AWARDED_FOR_NEWSLETTER_SIGNUP);
    }

    /**
     *  {@inheritDoc}
     */
    public function setBalanceUpdateNotificationStatus($balanceUpdateNotificationStatus)
    {
        return $this->setData(self::BALANCE_UPDATE_NOTIFICATION_STATUS, $balanceUpdateNotificationStatus);
    }

    /**
     *  {@inheritDoc}
     */
    public function getBalanceUpdateNotificationStatus()
    {
        return $this->getData(self::BALANCE_UPDATE_NOTIFICATION_STATUS);
    }

    /**
     *  {@inheritDoc}
     */
    public function setExpirationNotificationStatus($expirationNotificationStatus)
    {
        return $this->setData(self::EXPIRATION_NOTIFICATION_STATUS, $expirationNotificationStatus);
    }

    /**
     *  {@inheritDoc}
     */
    public function getExpirationNotificationStatus()
    {
        return $this->getData(self::EXPIRATION_NOTIFICATION_STATUS);
    }
}
