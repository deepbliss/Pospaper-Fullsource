<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Information\Messages;

use Aheadworks\RewardPoints\Model\Config;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Aheadworks\RewardPoints\Model\Service\PointsSummaryService;
use Aheadworks\RewardPoints\Model\Calculator\RateCalculator;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Checkout\Model\Session as CheckoutSession;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Quote\Model\Cart\CartTotalRepository;
use Aheadworks\RewardPoints\Model\Calculator\Earning as EarningCalculator;

/**
 * Class AbstractMessages
 *
 * @package Aheadworks\RewardPoints\Block\Information\Messages
 */
abstract class AbstractMessages extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var PointsSummaryService
     */
    protected $pointsSummaryService;

    /**
     * @var RateCalculator
     */
    protected $rateCalculator;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var CustomerRewardPointsManagementInterface
     */
    protected $customerRewardPointsService;

    /**
     * @var CartTotalRepository
     */
    protected $cartTotalRepository;

    /**
     * @var EarningCalculator
     */
    protected $earningCalculator;

    /**
     * @var CurrentCustomer
     */
    private $currentCustomer;

    /**
     * @var HttpContext
     */
    private $httpContext;

    /**
     * @var PriceHelper
     */
    private $priceHelper;

    /**
     * @param Context $context
     * @param Config $config
     * @param CurrentCustomer $currentCustomer
     * @param HttpContext $httpContext
     * @param PointsSummaryService $pointsSummaryService
     * @param RateCalculator $rateCalculator
     * @param PriceHelper $priceHelper
     * @param CheckoutSession $checkoutSession
     * @param CartTotalRepository $cartTotalRepository
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     * @param EarningCalculator $earningCalculator
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        CurrentCustomer $currentCustomer,
        HttpContext $httpContext,
        PointsSummaryService $pointsSummaryService,
        RateCalculator $rateCalculator,
        PriceHelper $priceHelper,
        CheckoutSession $checkoutSession,
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        CartTotalRepository $cartTotalRepository,
        EarningCalculator $earningCalculator,
        array $data = []
    ) {
        $this->config = $config;
        $this->currentCustomer = $currentCustomer;
        $this->httpContext = $httpContext;
        $this->pointsSummaryService = $pointsSummaryService;
        $this->rateCalculator = $rateCalculator;
        $this->priceHelper = $priceHelper;
        $this->checkoutSession = $checkoutSession;
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->cartTotalRepository = $cartTotalRepository;
        $this->earningCalculator = $earningCalculator;
        parent::__construct($context, $data);
    }

    /**
     * Can show block or not
     *
     * @return bool
     */
    abstract public function canShow();

    /**
     * Retrieve block message
     *
     * @return string
     */
    abstract public function getMessage();

    /**
     * {@inheritdoc}
     */
    public function toHtml()
    {
        if (!$this->getTemplate()) {
            return $this->getMessage();
        }
        return parent::toHtml();
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
     * Checking customer login status
     *
     * @return bool
     */
    public function isCustomerLoggedIn()
    {
        return (bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    /**
     * Retrieve potential earn money value
     *
     * @param int $points
     * @return float
     */
    public function getEarnMoneyByPoints($points)
    {
        $money = $this->rateCalculator->calculateRewardDiscount(
            $this->getCustomerId(),
            $points
        );
        return $money
            ? ' (' . $this->priceHelper->currency($money) . ')'
            : '';
    }

    /**
     * Retrieve customer id
     *
     * @return int
     */
    protected function getCustomerId()
    {
        return $this->currentCustomer->getCustomerId();
    }
}
