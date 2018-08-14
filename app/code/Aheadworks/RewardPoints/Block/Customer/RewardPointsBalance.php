<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Customer;

use Aheadworks\RewardPoints\Model\Calculator\RateCalculator;
use Aheadworks\RewardPoints\Model\Config;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance
 */
class RewardPointsBalance extends Template
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var CustomerRewardPointsManagementInterface
     */
    protected $customerRewardPointsService;

    /**
     * @var CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var PriceHelper
     */
    protected $priceHelper;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @var RateCalculator
     */
    protected $rateCalculator;

    /**
     * @param Context $context
     * @param Config $config
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     * @param CurrentCustomer $currentCustomer
     * @param PriceHelper $priceHelper
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param RateCalculator $rateCalculator
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        CurrentCustomer $currentCustomer,
        PriceHelper $priceHelper,
        \Magento\Framework\App\Http\Context $httpContext,
        RateCalculator $rateCalculator,
        array $data = []
    ) {
        $this->config = $config;
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->currentCustomer = $currentCustomer;
        $this->priceHelper = $priceHelper;
        $this->httpContext = $httpContext;
        $this->rateCalculator = $rateCalculator;
        parent::__construct($context, $data);
    }

    /**
     * Get customer balance in points
     *
     * @return int
     */
    public function getCustomerRewardPointsBalance()
    {
        return (int)$this->customerRewardPointsService->getCustomerRewardPointsBalance(
            $this->currentCustomer->getCustomerId()
        );
    }

    /**
     * Get formatted customer balance currency
     *
     * @return string
     */
    public function getFormattedCustomerBalanceCurrency()
    {
        return $this->priceHelper->currency(
            $this->getCustomerRewardPointsBalanceBaseCurrency(),
            true,
            false
        );
    }

    /**
     * Get frontend explainer page link
     *
     * @return string
     */
    public function getFrontendExplainerPageLink()
    {
        return $this->getUrl($this->config->getFrontendExplainerPage());
    }

    /**
     * Retrieve customer reward points balance in base currency
     *
     * @return float
     */
    protected function getCustomerRewardPointsBalanceBaseCurrency()
    {
        return $this->customerRewardPointsService->getCustomerRewardPointsBalanceBaseCurrency(
            $this->currentCustomer->getCustomerId()
        );
    }
}
