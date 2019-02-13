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

namespace ParadoxLabs\Subscriptions\Observer;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

/**
 * QuoteAddItemObserver Class
 */
class QuoteAddItemObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\QuoteManager
     */
    protected $quoteManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\ItemManager
     */
    protected $itemManager;

    /**
     * GenerateSubscriptionsObserver constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager
     * @param \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Config $config,
        \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager,
        \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager
    ) {
        $this->config = $config;
        $this->itemManager = $itemManager;
        $this->quoteManager = $quoteManager;
    }

    /**
     * Override item price et al when adding subscriptions to the cart.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->moduleIsActive() !== true) {
            return;
        }

        /** @var \Magento\Quote\Model\Quote\Item[] $items */
        $items = $observer->getEvent()->getData('items');

        foreach ($items as $quoteItem) {
            $this->updateQuoteItemSubscriptionPrice($quoteItem);
        }
    }

    /**
     * Update subscription pricing on the given quote item.
     *
     * @param \Magento\Quote\Api\Data\CartItemInterface $quoteItem
     * @return void
     */
    protected function updateQuoteItemSubscriptionPrice(\Magento\Quote\Api\Data\CartItemInterface $quoteItem)
    {
        /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $quoteItem->getQuote();

        if ($this->itemManager->isSubscription($quoteItem) === true
            && $this->quoteManager->isQuoteExistingSubscription($quote) === false) {
            $baseCurrency = $quote->getBaseCurrencyCode() ?: $quote->getStore()->getBaseCurrencyCode();
            $quoteCurrency = $quote->getQuoteCurrencyCode() ?: $quote->getStore()->getCurrentCurrencyCode();

            $price = $this->itemManager->calculatePrice(
                $quoteItem,
                1,
                $baseCurrency,
                $quoteCurrency
            );

            $changeItem = $quoteItem;
            if ($this->itemManager->parentItemShouldHavePrice($quoteItem) === true) {
                $changeItem = $quoteItem->getParentItem();
            }

            if ($price != $quoteItem->getProduct()->getFinalPrice()) {
                $changeItem->setCustomPrice($price);
                $changeItem->setOriginalCustomPrice($price);
            }
        }
    }
}
