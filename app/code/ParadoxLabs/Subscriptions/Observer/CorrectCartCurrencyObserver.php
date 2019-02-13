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

/**
 * CorrectCartCurrencyObserver Class
 */
class CorrectCartCurrencyObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\QuoteManager
     */
    protected $quoteManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\ItemManager
     */
    protected $itemManager;

    /**
     * CorrectCartCurrencyObserver constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager
     * @param \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Config $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager,
        \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->quoteManager = $quoteManager;
        $this->itemManager = $itemManager;
    }

    /**
     * Update subscription currency in cart if applicable.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->moduleIsActive() !== true) {
            return;
        }

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote  = $observer->getEvent()->getData('quote');

        /**
         * Has currency changed? No need to convert anything if not.
         */
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore();
        $newCurrency = $store->getCurrentCurrencyCode();

        if (empty($quote->getQuoteCurrencyCode()) || $quote->getQuoteCurrencyCode() === $newCurrency) {
            return;
        }

        /**
         * If it has, look for any subscription items with custom prices and handle it. More dots.
         */
        $existingSubscription = $this->quoteManager->isQuoteExistingSubscription($quote);
        $baseCurrency = $quote->getBaseCurrencyCode();

        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($quote->getAllItems() as $item) {
            $this->handleItemPriceConversion(
                $item,
                $baseCurrency,
                $newCurrency,
                $existingSubscription
            );
        }
    }

    /**
     * Check/update price for any subscription items on the quote.
     *
     * @param \Magento\Quote\Model\Quote\Item $item
     * @param string $baseCurrency
     * @param string $newCurrency
     * @param bool $existingSubscription
     * @return void
     */
    protected function handleItemPriceConversion(
        \Magento\Quote\Model\Quote\Item $item,
        $baseCurrency,
        $newCurrency,
        $existingSubscription
    ) {
        // Eligible item?
        if (!empty($item->getOriginalCustomPrice()) && $this->itemManager->isSubscription($item)) {
            // Calculate price -- differs by new vs. existing.
            $price = $this->itemManager->calculatePrice(
                $item,
                $existingSubscription === true ? 1 : 0,
                $baseCurrency,
                $newCurrency
            );

            // Parent item we need to worry about?
            $changeItem = $item;
            if ($this->itemManager->parentItemShouldHavePrice($item) === true) {
                $changeItem = $item->getParentItem();
            }

            // Finally: If price has changed, set the new one.
            if ($price != $item->getProduct()->getFinalPrice()) {
                $changeItem->setCustomPrice($price);
                $changeItem->setOriginalCustomPrice($price);
            }
        }
    }
}
