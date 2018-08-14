<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Api\Data;

/**
 * @api
 */
interface PointsSummaryInterface
{
    /**
     * #@+
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    const SUMMARY_ID = 'summary_id';
    const WEBSITE_ID = 'website_id';
    const CUSTOMER_ID = 'customer_id';
    const POINTS = 'points';
    const POINTS_EARN = 'points_earn';
    const POINTS_SPEND = 'points_spend';
    const DAILY_REVIEW_POINTS = 'daily_review_points';
    const DAILY_REVIEW_POINTS_DATE = 'daily_review_points_date';
    const DAILY_SHARE_POINTS = 'daily_share_points';
    const MONTHLY_SHARE_POINTS = 'monthly_share_points';
    const DAILY_SHARE_POINTS_DATE = 'daily_share_points_date';
    const IS_AWARDED_FOR_NEWSLETTER_SIGNUP = 'is_awarded_for_newsletter_signup';
    const BALANCE_UPDATE_NOTIFICATION_STATUS = 'balance_update_notification_status';
    const EXPIRATION_NOTIFICATION_STATUS = 'expiration_notification_status';
    /**#@-*/

    /**
     * Set summary Id
     *
     * @param int $summaryId
     * @return PointsSummaryInterface
     */
    public function setSummaryId($summaryId);

    /**
     * Get summary Id
     *
     * @return int
     */
    public function getSummaryId();

    /**
     * Set website Id
     *
     * @param int $websiteId
     * @return PointsSummaryInterface
     */
    public function setWebsiteId($websiteId);

    /**
     * Get website Id
     *
     * @return int
     */
    public function getWebsiteId();

    /**
     * Set customer id
     *
     * @param int $customerId
     * @return PointsSummaryInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get customer id
     *
     * @return int
     */
    public function getCustomerId();

    /**
     * Set summary points
     *
     * @param int $points
     * @return PointsSummaryInterface
     */
    public function setPoints($points);

    /**
     * Get summary points
     *
     * @return int
     */
    public function getPoints();

    /**
     * Set earn points
     *
     * @param int $points
     * @return PointsSummaryInterface
     */
    public function setPointsEarn($points);

    /**
     * Get earn points
     *
     * @return int
     */
    public function getPointsEarn();

    /**
     * Set spend points
     *
     * @param int $points
     * @return PointsSummaryInterface
     */
    public function setPointsSpend($points);

    /**
     * Get spend points
     *
     * @return int
     */
    public function getPointsSpend();

    /**
     * Set Daily Reward Points for current day
     *
     * @param int $dailyReviewPoints
     * @return PointsSummaryInterface
     */
    public function setDailyReviewPoints($dailyReviewPoints);

    /**
     * Get Daily Reward Points for current day
     *
     * @return int
     */
    public function getDailyReviewPoints();

    /**
     * Set Current Date for Daily Reward Points
     *
     * @param string $dailyReviewPointsDate
     * @return PointsSummaryInterface
     */
    public function setDailyReviewPointsDate($dailyReviewPointsDate);

    /**
     * Get Current Date for Daily Reward Points
     *
     * @return string
     */
    public function getDailyReviewPointsDate();

    /**
     * Set Daily Reward Points for current day
     *
     * @param int $dailySharePoints
     * @return PointsSummaryInterface
     */
    public function setDailySharePoints($dailySharePoints);

    /**
     * Get Daily Reward Points for current day
     *
     * @return int
     */
    public function getDailySharePoints();

    /**
     * Set Daily Reward Points for current month
     *
     * @param int $monthlySharePoints
     * @return PointsSummaryInterface
     */
    public function setMonthlySharePoints($monthlySharePoints);

    /**
     * Get Daily Reward Points for current month
     *
     * @return int
     */
    public function getMonthlySharePoints();

    /**
     * Set Current Date for Daily Reward Points
     *
     * @param string $dailySharePointsDate
     * @return PointsSummaryInterface
     */
    public function setDailySharePointsDate($dailySharePointsDate);

    /**
     * Get Current Date for Daily Reward Points
     *
     * @return string
     */
    public function getDailySharePointsDate();

    /**
     * Set if Customer is Awarded for Newsletter Signup
     *
     * @param boolean $isAwardedForNewsletterSignup
     * @return PointsSummaryInterface
     */
    public function setIsAwardedForNewsletterSignup($isAwardedForNewsletterSignup);

    /**
     * Is customer already Awarded for Newsletter Signup
     *
     * @return boolean
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsAwardedForNewsletterSignup();

    /**
     * Set summary balance update notification status
     *
     * @param int $balanceUpdateNotificationStatus
     * @return PointsSummaryInterface
     */
    public function setBalanceUpdateNotificationStatus($balanceUpdateNotificationStatus);

    /**
     * Get summary balance update notification status
     *
     * @return int
     */
    public function getBalanceUpdateNotificationStatus();

    /**
     * Set summary expiration notification status
     *
     * @param int $expirationNotificationStatus
     * @return PointsSummaryInterface
     */
    public function setExpirationNotificationStatus($expirationNotificationStatus);

    /**
     * Get summary expiration notification status
     *
     * @return int
     */
    public function getExpirationNotificationStatus();
}
