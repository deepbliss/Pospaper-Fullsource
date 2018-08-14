<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance;

use Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance;

/**
 * Class Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance\Account
 */
class Account extends RewardPointsBalance
{
    /**
     * @var []
     */
    private $minRateIsNeeded;

    /**
     * Retrieve customer transaction grid
     *
     * @return string
     */
    public function getTransactionHtml()
    {
        return $this->getChildHtml('aw_rp_transaction');
    }

    /**
     * Retrieve min balance for use at checkout
     *
     * @return int
     */
    public function getOnceMinBalance()
    {
        return $this->customerRewardPointsService->getCustomerRewardPointsOnceMinBalance(
            $this->currentCustomer->getCustomerId()
        );
    }

    /**
     * Is customer spend rate
     *
     * @return bool
     */
    public function isCustomerRewardPointsSpendRate()
    {
        return $this->customerRewardPointsService->isCustomerRewardPointsSpendRate(
            $this->currentCustomer->getCustomerId()
        );
    }

    /**
     * Retrieve formatted spend customer is needed
     *
     * @return string
     */
    public function getFormattedSpendCustomerIsNeeded()
    {
        return $this->priceHelper->currency(
            $this->getLifetimeSalesDifference(),
            true,
            false
        );
    }

    /**
     * Retrieve customer will earn points
     *
     * @return int
     */
    public function getCustomerWillEarnPoints()
    {
        return $this->rateCalculator->calculateEarnPoints(
            $this->currentCustomer->getCustomerId(),
            $this->getLifetimeSalesDifference()
        );
    }

    /**
     * Retrieve formatted customer bonus discount
     *
     * @return string
     */
    public function getFormattedCustomerBonusDiscount()
    {
        $customerSpendRate = $this->getMinRateIsNeeded();
        $discount = $this->rateCalculator->calculateRewardDiscount(
            $this->currentCustomer->getCustomerId(),
            $this->getCustomerRewardPointsBalance() + $this->getCustomerWillEarnPoints(),
            null,
            $customerSpendRate['spend_rate']
        );

        return $this->priceHelper->currency(
            $discount,
            true,
            false
        );
    }

    /**
     * Retrieve customer difference lifetime sales
     *
     * @return float
     */
    private function getLifetimeSalesDifference()
    {
        $customerSpendRate = $this->getMinRateIsNeeded();
        return (float)$customerSpendRate['spend_rate']->getLifetimeSalesAmount()
            - (float)$customerSpendRate['lifetime_sales'];
    }

    /**
     * Retrieve min rate is needed to customer
     *
     * @return []
     */
    private function getMinRateIsNeeded()
    {
        if (null == $this->minRateIsNeeded) {
            $this->minRateIsNeeded = $this->rateCalculator->getMinRateIsNeeded(
                $this->currentCustomer->getCustomerId()
            );
        }
        return $this->minRateIsNeeded;
    }
}
