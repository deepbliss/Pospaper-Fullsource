<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Helper;

/**
 * DataLegacy Class
 *
 * Separates out backwards compatibility method stubs for cleanliness.
 * They probably won't go anywhere, but don't use these.
 */
class DataLegacy extends \ParadoxLabs\TokenBase\Helper\Operation
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\QuoteManager
     */
    protected $quoteManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\ItemManager
     */
    protected $itemManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $subscriptionsConfig;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Monolog\Logger $tokenbaseLogger
     * @param \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager
     * @param \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager
     * @param \ParadoxLabs\Subscriptions\Model\Config $subscriptionsConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Monolog\Logger $tokenbaseLogger,
        \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager,
        \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager,
        \ParadoxLabs\Subscriptions\Model\Config $subscriptionsConfig
    ) {
        parent::__construct($context, $tokenbaseLogger);

        $this->quoteManager = $quoteManager;
        $this->itemManager = $itemManager;
        $this->subscriptionsConfig = $subscriptionsConfig;
    }

    /**
     * Check whether the given quote contains a subscription item.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return bool
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Service\QuoteManager::quoteContainsSubscription()
     */
    public function quoteContainsSubscription($quote)
    {
        return $this->quoteManager->quoteContainsSubscription($quote);
    }

    /**
     * Mark the quote as belonging to an existing subscription. Behavior can differ for initial vs. follow-up billings.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return \Magento\Quote\Api\Data\CartInterface
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Service\QuoteManager::setQuoteExistingSubscription()
     */
    public function setQuoteIsExistingSubscription(\Magento\Quote\Api\Data\CartInterface $quote)
    {
        return $this->quoteManager->setQuoteExistingSubscription($quote);
    }

    /**
     * Check whether the given quote is an existing subscription.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return bool
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Service\QuoteManager::isQuoteExistingSubscription()
     */
    public function isQuoteExistingSubscription(\Magento\Quote\Api\Data\CartInterface $quote)
    {
        return $this->quoteManager->isQuoteExistingSubscription($quote);
    }

    /**
     * Calculate regular price for a subscription item.
     *
     * @param \Magento\Quote\Model\Quote\Item $item
     * @param string $baseCurrency Website base currency code (convert from)
     * @param string $quoteCurrency Cart currency code (convert to)
     * @return float
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Service\ItemManager::calculatePrice()
     */
    public function calculateRegularSubscriptionPrice(
        \Magento\Quote\Model\Quote\Item $item,
        $baseCurrency = null,
        $quoteCurrency = null
    ) {
        return $this->itemManager->calculatePrice($item, 2, $baseCurrency, $quoteCurrency);
    }

    /**
     * Calculate initial price for a subscription item.
     *
     * @param \Magento\Quote\Model\Quote\Item $item
     * @param string $baseCurrency Website base currency code (convert from)
     * @param string $quoteCurrency Cart currency code (convert to)
     * @return float
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Service\ItemManager::calculatePrice()
     */
    public function calculateInitialSubscriptionPrice(
        \Magento\Quote\Model\Quote\Item $item,
        $baseCurrency = null,
        $quoteCurrency = null
    ) {
        return $this->itemManager->calculatePrice($item, 1, $baseCurrency, $quoteCurrency);
    }

    /**
     * Get the subscription interval (if any) for the current item. 0 for none.
     *
     * @param \Magento\Framework\Model\AbstractExtensibleModel $item
     * @return int
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Service\ItemManager::getFrequencyCount()
     */
    public function getItemSubscriptionInterval(\Magento\Framework\Model\AbstractExtensibleModel $item)
    {
        return $this->itemManager->getFrequencyCount($item);
    }

    /**
     * Get the subscription unit for the current item.
     *
     * @param \Magento\Framework\Model\AbstractExtensibleModel $item
     * @return string
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Service\ItemManager::getFrequencyUnit()
     */
    public function getItemSubscriptionUnit(\Magento\Framework\Model\AbstractExtensibleModel $item)
    {
        return $this->itemManager->getFrequencyUnit($item);
    }

    /**
     * Get the subscription length for the current item--number of billing cycles to be run. 0 for indefinite.
     *
     * @param \Magento\Framework\Model\AbstractExtensibleModel $item
     * @return int
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Service\ItemManager::getLength()
     */
    public function getItemSubscriptionLength(\Magento\Framework\Model\AbstractExtensibleModel $item)
    {
        return $this->itemManager->getLength($item);
    }

    /**
     * Get the subscription description for the given item.
     *
     * @param \Magento\Framework\Model\AbstractExtensibleModel $item
     * @return string
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Service\ItemManager::getSubscriptionDescription()
     */
    public function getItemSubscriptionDesc(\Magento\Framework\Model\AbstractExtensibleModel $item)
    {
        return $this->itemManager->getSubscriptionDescription($item);
    }

    /**
     * Check whether the given item should be a subscription.
     *
     * @param \Magento\Framework\Model\AbstractExtensibleModel $item
     * @return bool
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Service\ItemManager::isSubscription()
     */
    public function isItemSubscription(\Magento\Framework\Model\AbstractExtensibleModel $item)
    {
        return $this->itemManager->isSubscription($item);
    }

    /**
     * Determine whether the item's product has only one subscription option.
     *
     * @param \Magento\Framework\Model\AbstractExtensibleModel $item
     * @return bool
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Service\ItemManager::isSingleOptionSubscription()
     */
    public function isSingleOptionSubscriptionItem(\Magento\Framework\Model\AbstractExtensibleModel $item)
    {
        return $this->itemManager->isSingleOptionSubscription($item);
    }

    /**
     * Check whether the given item has a parent we should set the price on.
     *
     * @param \Magento\Quote\Api\Data\CartItemInterface $quoteItem
     * @return bool
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Service\ItemManager::parentItemShouldHavePrice()
     */
    public function parentItemShouldHavePrice(\Magento\Quote\Api\Data\CartItemInterface $quoteItem)
    {
        return $this->itemManager->parentItemShouldHavePrice($quoteItem);
    }

    /**
     * Get label for the subscription custom option. Poor attempt at flexibility/localization.
     *
     * @return string
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Config::getSubscriptionLabel()
     */
    public function getSubscriptionLabel()
    {
        return $this->subscriptionsConfig->getSubscriptionLabel();
    }

    /**
     * Check whether custom option should be skipped if only a single option is available for a product.
     *
     * @return bool
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Config::skipSingleOption()
     */
    public function skipSingleOption()
    {
        return $this->subscriptionsConfig->skipSingleOption();
    }

    /**
     * Check whether subscriptions module is enabled in configuration for the current scope.
     *
     * @return bool
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Config::moduleIsActive()
     */
    public function moduleIsActive()
    {
        return $this->subscriptionsConfig->moduleIsActive();
    }

    /**
     * Get input type for the subscription custom option.
     *
     * @return string
     * @deprecated since 3.0.0
     * @see \ParadoxLabs\Subscriptions\Model\Config::getInputType()
     */
    public function getInputType()
    {
        return $this->subscriptionsConfig->getInputType();
    }
}
