<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Product\View;

use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Aheadworks\RewardPoints\Model\Calculator\RateCalculator;
use Aheadworks\RewardPoints\Model\Config;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class Aheadworks\RewardPoints\Block\Product\View\Share
 */
class Share extends \Magento\Framework\View\Element\Template
{
    /**
     * Facebook share link
     */
    const FACEBOOK_SHARE_LINK = 'https://www.facebook.com/sharer/sharer.php?u=';

    /**
     * Twitter share link
     */
    const TWITTER_SHARE_LINK = 'https://twitter.com/intent/tweet?url=';

    /**
     * Google+ share link
     */
    const GOOGLE_PLUS_SHARE_LINK = 'https://plus.google.com/share?url=';

    /**
     * Block template filename
     *
     * @var string
     */
    protected $_template = 'Aheadworks_RewardPoints::product/view/share.phtml';

    /**
     * @var CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsService;

    /**
     * @var RateCalculator
     */
    private $rateCalculator;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var PriceHelper
     */
    private $priceHelper;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    private $httpContext;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param Context $context
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     * @param RateCalculator $rateCalculator
     * @param Config $config
     * @param Session $customerSession
     * @param PriceHelper $priceHelper
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        RateCalculator $rateCalculator,
        Config $config,
        Session $customerSession,
        PriceHelper $priceHelper,
        \Magento\Framework\App\Http\Context $httpContext,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->rateCalculator = $rateCalculator;
        $this->config = $config;
        $this->customerSession = $customerSession;
        $this->priceHelper = $priceHelper;
        $this->httpContext = $httpContext;
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
     * Retrieve product
     *
     * @return \Magento\Catalog\Model\Product
     */
    private function getProduct()
    {
        return $this->productRepository->getById($this->_request->getParam('id'));
    }

    /**
     * Retrieve config value for Display social sharing buttons at product page
     *
     * @return boolean
     */
    public function isDisplayBlock()
    {
        $customerRewardPointsEarnRate = $this->customerRewardPointsService
            ->isCustomerRewardPointsEarnRate($this->customerSession->getId());
        $customerRewardPointsEarnRateByGroup = $this->customerRewardPointsService
            ->isCustomerRewardPointsEarnRateByGroup($this->customerSession->getId());

        return $this->config->isDisplayShareLinks()
            && $customerRewardPointsEarnRateByGroup && $customerRewardPointsEarnRate;
    }

    /**
     * Get action url
     *
     * @return string
     */
    public function getAjaxActionUrl()
    {
        return $this->getUrl('aw_rewardpoints/share');
    }

    /**
     * Is customer is login
     *
     * @return boolean
     */
    public function isGuest()
    {
        return !(bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    /**
     * Get product id
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->getProduct()->getId();
    }

    /**
     * Get current product url
     *
     * @return string
     */
    public function getCurrentProductUrl()
    {
        return $this->getProduct()->getUrlModel()->getUrl($this->getProduct());
    }

    /**
     * Get customer awarded points for share
     *
     * @return float
     */
    public function getAwardedPointsForShare()
    {
        return $this->config->getAwardedPointsForShare();
    }

    /**
     * Get customer awarded amount
     *
     * @return float
     */
    private function getAwardedAmount()
    {
        $points = $this->getAwardedPointsForShare();
        if ($points > 0) {
            return $this->rateCalculator->calculateRewardDiscount($this->customerSession->getId(), $points);
        }

        return 0;
    }

    /**
     * Get formatted customer awarded amount
     *
     * @return string
     */
    public function getFormattedAwardedAmount()
    {
        return $this->priceHelper->currency($this->getAwardedAmount(), true, false);
    }

    /**
     * Get facebook share url
     *
     * @return string
     */
    public function getFacebookShareUrl()
    {
        return self::FACEBOOK_SHARE_LINK . $this->escapeUrl($this->getCurrentProductUrl());
    }

    /**
     * Get twitter share url
     *
     * @return string
     */
    public function getTwitterShareUrl()
    {
        return self::TWITTER_SHARE_LINK . $this->escapeUrl($this->getCurrentProductUrl());
    }

    /**
     * Get google plus share url
     *
     * @return string
     */
    public function getGooglePlusShareUrl()
    {
        return self::GOOGLE_PLUS_SHARE_LINK . $this->escapeUrl($this->getCurrentProductUrl());
    }
}
