<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance;

use Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance;

/**
 * Class Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance\Toplink
 */
class Toplink extends RewardPointsBalance
{
    /**
     * @var string
     */
    protected $_template = 'Aheadworks_RewardPoints::customer/toplinks/points_balance.phtml';

    /**
     * Is ajax request or not
     *
     * @return bool
     */
    public function isAjax()
    {
        return $this->_request->isAjax();
    }

    /**
     * Checking customer login status
     *
     * @return bool
     */
    public function customerLoggedIn()
    {
        return (bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    /**
     * Can show block
     *
     * @return bool
     */
    public function canShow()
    {
        $customerRewardPointsSpendRate = $this->customerRewardPointsService
            ->isCustomerRewardPointsSpendRate($this->currentCustomer->getCustomerId());
        $customerRewardPointsSpendRateByGroup = $this->customerRewardPointsService
            ->isCustomerRewardPointsSpendRateByGroup($this->currentCustomer->getCustomerId());
        $customerRewardPointsEarnRate = $this->customerRewardPointsService
            ->isCustomerRewardPointsEarnRate($this->currentCustomer->getCustomerId());
        $customerRewardPointsEarnRateByGroup = $this->customerRewardPointsService
            ->isCustomerRewardPointsEarnRateByGroup($this->currentCustomer->getCustomerId());

        if ($this->config->isPointsBalanceTopLinkAtFrontend()
            && (!$this->config->isHideIfRewardPointsBalanceEmpty()
                || ($this->config->isHideIfRewardPointsBalanceEmpty() &&
                    (float)$this->getCustomerRewardPointsBalance() > 0))
            && (($customerRewardPointsSpendRateByGroup && $customerRewardPointsSpendRate)
                || ($customerRewardPointsEarnRateByGroup && $customerRewardPointsEarnRate))
        ) {
            return true;
        }
        return false;
    }
}
