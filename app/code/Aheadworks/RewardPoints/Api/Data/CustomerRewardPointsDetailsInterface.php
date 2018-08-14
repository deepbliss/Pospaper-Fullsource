<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Api\Data;

/**
 * @api
 */
interface CustomerRewardPointsDetailsInterface
{
    /**#@+
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    const CUSTOMER_REWARD_POINTS_BALANCE = 'customer_reward_points_balance';
    const CUSTOMER_REWARD_POINTS_CURRENCY_BALANCE = 'customer_reward_points_currency_balance';
    const CUSTOMER_REWARD_POINTS_BASE_CURRENCY_BALANCE = 'customer_reward_points_base_currency_balance';
    const CUSTOMER_BALANCE_UPDATE_NOTIFICATION_STATUS = 'customer_balance_update_notification_status';
    const CUSTOMER_EXPIRATION_NOTIFICATION_STATUS = 'customer_expiration_notification_status';
    const CUSTOMER_REWARD_POINTS_ONCE_MIN_BALANCE = 'customer_reward_points_once_min_balance';
    const CUSTOMER_REWARD_POINTS_SPEND_RATE_BY_GROUP = 'customer_reward_points_spend_rate_by_group';
    const CUSTOMER_REWARD_POINTS_SPEND_RATE = 'customer_reward_points_spend_rate';
    const CUSTOMER_REWARD_POINTS_EARN_RATE_BY_GROUP = 'customer_reward_points_earn_rate_by_group';
    const CUSTOMER_REWARD_POINTS_EARN_RATE = 'customer_reward_points_earn_rate';
    /**#@-*/

    /**
     * Retrieve customer reward points balance
     *
     * @return int
     */
    public function getCustomerRewardPointsBalance();

    /**
     * Set customer reward points balance
     *
     * @param int $balance
     * @return CustomerRewardPointsDetailsInterface
     */
    public function setCustomerRewardPointsBalance($balance);

    /**
     * Retrieve customer reward points balance base currency
     *
     * @return float
     */
    public function getCustomerRewardPointsBalanceBaseCurrency();

    /**
     * Set customer reward points balance base currency
     *
     * @param float $balanceBaseCurrency
     * @return CustomerRewardPointsDetailsInterface
     */
    public function setCustomerRewardPointsBalanceBaseCurrency($balanceBaseCurrency);

    /**
     * Retrieve customer reward points currency balance
     *
     * @return float
     */
    public function getCustomerRewardPointsBalanceCurrency();

    /**
     * Set customer reward points currency balance
     *
     * @param float $balanceCurrency
     * @return CustomerRewardPointsDetailsInterface
     */
    public function setCustomerRewardPointsBalanceCurrency($balanceCurrency);

    /**
     * Retrieve customer balance update notification status
     *
     * @return int
     */
    public function getCustomerBalanceUpdateNotificationStatus();

    /**
     * Set customer balance update notification status
     *
     * @param int $balanceUpdateNotificationStatus
     * @return CustomerRewardPointsDetailsInterface
     */
    public function setCustomerBalanceUpdateNotificationStatus($balanceUpdateNotificationStatus);

    /**
     * Set customer expiration notification status
     *
     * @param int $expirationNotificationStatus
     * @return CustomerRewardPointsDetailsInterface
     */
    public function setCustomerExpirationNotificationStatus($expirationNotificationStatus);

    /**
     * Get customer expiration notification status
     *
     * @return int
     */
    public function getCustomerExpirationNotificationStatus();

    /**
     * Set customer once min balance
     *
     * @param int $onceMinBalance
     * @return CustomerRewardPointsDetailsInterface
     */
    public function setCustomerRewardPointsOnceMinBalance($onceMinBalance);

    /**
     * Get customer once min balance
     *
     * @return int
     */
    public function getCustomerRewardPointsOnceMinBalance();

    /**
     * Set spend rate by customer group
     *
     * @param bool $spendRateByGroup
     * @return CustomerRewardPointsDetailsInterface
     */
    public function setCustomerRewardPointsSpendRateByGroup($spendRateByGroup);

    /**
     * Is spend rate by customer group
     *
     * @return bool
     */
    public function isCustomerRewardPointsSpendRateByGroup();

    /**
     * Set spend rate by customer
     *
     * @param bool $spendRate
     * @return CustomerRewardPointsDetailsInterface
     */
    public function setCustomerRewardPointsSpendRate($spendRate);

    /**
     * Is spend rate by customer
     *
     * @return bool
     */
    public function isCustomerRewardPointsSpendRate();

    /**
     * Set earn rate by customer group
     *
     * @param bool $earnRateByGroup
     * @return CustomerRewardPointsDetailsInterface
     */
    public function setCustomerRewardPointsEarnRateByGroup($earnRateByGroup);

    /**
     * Is earn rate by customer group
     *
     * @return bool
     */
    public function isCustomerRewardPointsEarnRateByGroup();

    /**
     * Set earn rate by customer
     *
     * @param bool $earnRate
     * @return CustomerRewardPointsDetailsInterface
     */
    public function setCustomerRewardPointsEarnRate($earnRate);

    /**
     * Is earn rate by customer
     *
     * @return bool
     */
    public function isCustomerRewardPointsEarnRate();
}
