<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance;

use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\View\Element\Html\Link\Current as LinkCurrent;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\DefaultPathInterface;

/**
 * Class Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance\Link
 */
class Link extends LinkCurrent
{
    /**
     * @var CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsService;

    /**
     * @var CurrentCustomer
     */
    private $currentCustomer;

    /**
     * @param Context $context
     * @param DefaultPathInterface $defaultPath
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     * @param CurrentCustomer $currentCustomer
     * @param array $data
     */
    public function __construct(
        Context $context,
        DefaultPathInterface $defaultPath,
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        CurrentCustomer $currentCustomer,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $data);
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $customerRewardPointsSpendRateByGroup = $this->customerRewardPointsService
            ->isCustomerRewardPointsSpendRateByGroup($this->currentCustomer->getCustomerId());
        $customerRewardPointsEarnRateByGroup = $this->customerRewardPointsService
            ->isCustomerRewardPointsEarnRateByGroup($this->currentCustomer->getCustomerId());

        if ($customerRewardPointsSpendRateByGroup || $customerRewardPointsEarnRateByGroup) {
            return parent::_toHtml();
        }
        return '';
    }
}
