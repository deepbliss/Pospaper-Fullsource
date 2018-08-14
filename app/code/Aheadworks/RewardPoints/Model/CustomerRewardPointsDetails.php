<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Aheadworks\RewardPoints\Api\Data\CustomerRewardPointsDetailsInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Aheadworks\RewardPoints\Model\CustomerRewardPointsDetails
 */
class CustomerRewardPointsDetails extends AbstractModel implements CustomerRewardPointsDetailsInterface
{
    /**
     *  {@inheritDoc}
     */
    public function getCustomerRewardPointsBalance()
    {
        return $this->getData(self::CUSTOMER_REWARD_POINTS_BALANCE);
    }

    /**
     *  {@inheritDoc}
     */
    public function setCustomerRewardPointsBalance($balance)
    {
        return $this->setData(self::CUSTOMER_REWARD_POINTS_BALANCE, $balance);
    }

    /**
     *  {@inheritDoc}
     */
    public function getCustomerRewardPointsBalanceBaseCurrency()
    {
        return $this->getData(self::CUSTOMER_REWARD_POINTS_BASE_CURRENCY_BALANCE);
    }

    /**
     *  {@inheritDoc}
     */
    public function setCustomerRewardPointsBalanceBaseCurrency($balanceBaseCurrency)
    {
        return $this->setData(self::CUSTOMER_REWARD_POINTS_BASE_CURRENCY_BALANCE, $balanceBaseCurrency);
    }

    /**
     *  {@inheritDoc}
     */
    public function getCustomerRewardPointsBalanceCurrency()
    {
        return $this->getData(self::CUSTOMER_REWARD_POINTS_CURRENCY_BALANCE);
    }

    /**
     *  {@inheritDoc}
     */
    public function setCustomerRewardPointsBalanceCurrency($balanceCurrency)
    {
        return $this->setData(self::CUSTOMER_REWARD_POINTS_CURRENCY_BALANCE, $balanceCurrency);
    }

    /**
     *  {@inheritDoc}
     */
    public function getCustomerBalanceUpdateNotificationStatus()
    {
        return $this->getData(self::CUSTOMER_BALANCE_UPDATE_NOTIFICATION_STATUS);
    }

    /**
     *  {@inheritDoc}
     */
    public function setCustomerBalanceUpdateNotificationStatus($balanceUpdateNotificationStatus)
    {
        return $this->setData(self::CUSTOMER_BALANCE_UPDATE_NOTIFICATION_STATUS, $balanceUpdateNotificationStatus);
    }

    /**
     *  {@inheritDoc}
     */
    public function getCustomerExpirationNotificationStatus()
    {
        return $this->getData(self::CUSTOMER_EXPIRATION_NOTIFICATION_STATUS);
    }

    /**
     *  {@inheritDoc}
     */
    public function setCustomerExpirationNotificationStatus($expirationNotificationStatus)
    {
        return $this->setData(self::CUSTOMER_EXPIRATION_NOTIFICATION_STATUS, $expirationNotificationStatus);
    }

    /**
     *  {@inheritDoc}
     */
    public function setCustomerRewardPointsOnceMinBalance($onceMinBalance)
    {
        return $this->setData(self::CUSTOMER_REWARD_POINTS_ONCE_MIN_BALANCE, $onceMinBalance);
    }

    /**
     *  {@inheritDoc}
     */
    public function getCustomerRewardPointsOnceMinBalance()
    {
        return $this->getData(self::CUSTOMER_REWARD_POINTS_ONCE_MIN_BALANCE);
    }

    /**
     *  {@inheritDoc}
     */
    public function setCustomerRewardPointsSpendRateByGroup($spendRateByGroup)
    {
        return $this->setData(self::CUSTOMER_REWARD_POINTS_SPEND_RATE_BY_GROUP, $spendRateByGroup);
    }

    /**
     *  {@inheritDoc}
     */
    public function isCustomerRewardPointsSpendRateByGroup()
    {
        return $this->getData(self::CUSTOMER_REWARD_POINTS_SPEND_RATE_BY_GROUP);
    }

    /**
     *  {@inheritDoc}
     */
    public function setCustomerRewardPointsSpendRate($spendRate)
    {
        return $this->setData(self::CUSTOMER_REWARD_POINTS_SPEND_RATE, $spendRate);
    }

    /**
     *  {@inheritDoc}
     */
    public function isCustomerRewardPointsSpendRate()
    {
        return $this->getData(self::CUSTOMER_REWARD_POINTS_SPEND_RATE);
    }

    /**
     *  {@inheritDoc}
     */
    public function setCustomerRewardPointsEarnRateByGroup($earnRateByGroup)
    {
        return $this->setData(self::CUSTOMER_REWARD_POINTS_EARN_RATE_BY_GROUP, $earnRateByGroup);
    }

    /**
     *  {@inheritDoc}
     */
    public function isCustomerRewardPointsEarnRateByGroup()
    {
        return $this->getData(self::CUSTOMER_REWARD_POINTS_EARN_RATE_BY_GROUP);
    }

    /**
     *  {@inheritDoc}
     */
    public function setCustomerRewardPointsEarnRate($earnRate)
    {
        return $this->setData(self::CUSTOMER_REWARD_POINTS_EARN_RATE, $earnRate);
    }

    /**
     *  {@inheritDoc}
     */
    public function isCustomerRewardPointsEarnRate()
    {
        return $this->getData(self::CUSTOMER_REWARD_POINTS_EARN_RATE);
    }
}
