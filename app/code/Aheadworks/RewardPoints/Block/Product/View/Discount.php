<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Product\View;

use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Aheadworks\RewardPoints\Model\Calculator\RateCalculator;
use Aheadworks\RewardPoints\Model\CategoryAllowed;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\RewardPoints\Model\Config;
use Magento\Customer\Model\Session;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class Aheadworks\RewardPoints\Block\Product\View\Discount
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Discount extends \Magento\Framework\View\Element\Template
{
    /**
     * Block template filename
     *
     * @var string
     */
    protected $_template = 'Aheadworks_RewardPoints::product/view/discount.phtml';

    /**
     * @var CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsService;

    /**
     * @var RateCalculator
     */
    private $rateCalculator;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var PriceHelper
     */
    private $priceHelper;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var CategoryAllowed
     */
    private $categoryAllowed;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param Context $context
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     * @param Session $customerSession
     * @param Config $config
     * @param RateCalculator $rateCalculator
     * @param PriceHelper $priceHelper
     * @param CategoryAllowed $categoryAllowed
     * @param ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        Session $customerSession,
        Config $config,
        RateCalculator $rateCalculator,
        PriceHelper $priceHelper,
        CategoryAllowed $categoryAllowed,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->rateCalculator = $rateCalculator;
        $this->customerSession = $customerSession;
        $this->priceHelper = $priceHelper;
        $this->config = $config;
        $this->categoryAllowed = $categoryAllowed;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

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
     * Retrieve config value for Display block with discount information
     *
     * @return boolean
     */
    public function isDisplayBlock()
    {
        $customerRewardPointsOnceMinBalance = $this->customerRewardPointsService
            ->getCustomerRewardPointsOnceMinBalance($this->customerSession->getId());
        $customerRewardPointsSpendRate = $this->customerRewardPointsService
            ->isCustomerRewardPointsSpendRate($this->customerSession->getId());
        $customerRewardPointsSpendRateByGroup = $this->customerRewardPointsService
            ->isCustomerRewardPointsSpendRateByGroup($this->customerSession->getId());

        return $this->config->isDisplayDiscountInfoBlock() && $this->isAllowedCategoriesForSpend()
            && $customerRewardPointsOnceMinBalance == 0
            && $customerRewardPointsSpendRateByGroup && $customerRewardPointsSpendRate;
    }

    /**
     * Get customer available points
     *
     * @return int
     */
    public function getAvailablePoints()
    {
        if ($this->customerSession->getId()) {
            return $this->customerRewardPointsService->getCustomerRewardPointsBalance($this->customerSession->getId());
        }

        return 0;
    }

    /**
     * Get customer available amount
     *
     * @return float
     */
    private function getAvailableAmount()
    {
        $points = $this->getAvailablePoints();
        if ($points > 0) {
            return $this->rateCalculator->calculateRewardDiscount($this->customerSession->getId(), $points);
        }

        return 0;
    }

    /**
     * Get formatted customer available amount
     *
     * @return string
     */
    public function getFormattedAvailableAmount()
    {
        return $this->priceHelper->currency($this->getAvailableAmount(), true, false);
    }

    /**
     * Is allowed category products for spend
     *
     * @return boolean
     */
    private function isAllowedCategoriesForSpend()
    {
        return $this->categoryAllowed->isAllowedCategoryForSpendPoints($this->getProduct()->getCategoryIds());
    }

    /**
     * Retrieve current product
     *
     * @return \Magento\Catalog\Model\Product
     */
    private function getProduct()
    {
        return $this->productRepository->getById($this->_request->getParam('id'));
    }
}
