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

namespace ParadoxLabs\Subscriptions\Model\Service;

/**
 * QuoteManager Class
 */
class QuoteManager
{
    /**
     * @var array
     */
    protected $quoteContainsSubscription = [];

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\ItemManager
     */
    protected $itemManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * QuoteManager constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager *Proxy
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager,
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->itemManager = $itemManager;
        $this->config = $config;
    }

    /**
     * Check whether the given quote contains a subscription item.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return bool
     */
    public function quoteContainsSubscription($quote)
    {
        /** @var \Magento\Quote\Model\Quote $quote */

        if (($quote instanceof \Magento\Quote\Api\Data\CartInterface) !== true) {
            return false;
        }

        if ($quote->getId() && isset($this->quoteContainsSubscription[$quote->getId()])) {
            return $this->quoteContainsSubscription[$quote->getId()];
        }

        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($quote->getAllItems() as $item) {
            if ($this->itemManager->isSubscription($item) === true) {
                if ($quote->getId()) {
                    $this->quoteContainsSubscription[$quote->getId()] = true;
                }

                return true;
            }
        }

        if ($quote->getId()) {
            $this->quoteContainsSubscription[$quote->getId()] = false;
        }

        return false;
    }

    /**
     * Mark the quote as belonging to an existing subscription. Behavior can differ for initial vs. follow-up billings.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    public function setQuoteExistingSubscription(\Magento\Quote\Api\Data\CartInterface $quote)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        if ($quote->getPayment() instanceof \Magento\Quote\Api\Data\PaymentInterface) {
            $quote->getPayment()->setAdditionalInformation('is_subscription_generated', 1);
        }

        return $quote;
    }

    /**
     * Check whether the given quote is an existing subscription.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return bool
     */
    public function isQuoteExistingSubscription(\Magento\Quote\Api\Data\CartInterface $quote)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        if ($quote->getPayment() instanceof \Magento\Quote\Api\Data\PaymentInterface) {
            return (int)$quote->getPayment()->getAdditionalInformation('is_subscription_generated') === 1;
        }

        return false;
    }

    /**
     * Set shipping method on the new quote.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @param string $shippingMethod
     * @param string $shippingDescription
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    public function setQuoteShippingMethod(
        \Magento\Quote\Api\Data\CartInterface $quote,
        $shippingMethod,
        $shippingDescription
    ) {
        /** @var \Magento\Quote\Model\Quote $quote */

        $quote->setIsVirtual($quote->getIsVirtual());

        if ((bool)$quote->getIsVirtual() === false) {
            $shippingAddress = $quote->getShippingAddress();

            // We have to collect totals to prep for any amount- or weight-based shipping settings or promotion rules.
            $quote->collectTotals();

            // This fetches current shipping rates. NB: The rates available now may differ from the original order,
            // especially if minimum amounts are in play! This can be a problem.
            $shippingAddress->setCollectShippingRates(true)
                            ->collectShippingRates();

            $forceMethod = (string)$this->config->forceShippingMethod();

            if (!empty($forceMethod) && $this->isShippingMethodValid($shippingAddress, $forceMethod)) {
                // Configuration is forcing a specific shipping method. Apply that.
                $rate = $shippingAddress->getShippingRateByCode($forceMethod);

                $shippingMethod = $forceMethod;
                $shippingDescription = (string)__(
                    '%1 - %2',
                    $rate->getCarrierTitle(),
                    $rate->getMethodTitle()
                );
            } elseif ($this->isShippingMethodValid($shippingAddress, $shippingMethod) === false
                && $this->config->isShippingFallbackEnabled() === true) {
                // The order shipping method isn't available, so fall back to something else.
                $rate = $this->getFallbackShippingMethod($shippingAddress);

                if ($rate instanceof \Magento\Quote\Model\Quote\Address\Rate) {
                    $shippingMethod = $rate->getCode();
                    $shippingDescription = (string)__(
                        '%1 - %2',
                        $rate->getCarrierTitle(),
                        $rate->getMethodTitle()
                    );
                }
            }

            $shippingAddress->setShippingMethod($shippingMethod)
                            ->setShippingDescription($shippingDescription);
        }

        return $quote;
    }

    /**
     * Check whether the subscription's assigned shipping method is actually available.
     *
     * @param \Magento\Quote\Api\Data\AddressInterface $shippingAddress
     * @param string $methodCode
     * @return bool
     */
    public function isShippingMethodValid(\Magento\Quote\Api\Data\AddressInterface $shippingAddress, $methodCode)
    {
        /** @var \Magento\Quote\Model\Quote\Address $shippingAddress */

        $method = $shippingAddress->getShippingRateByCode($methodCode);

        return $method !== false;
    }

    /**
     * Choose an available shipping method as fallback for the subscription (original being unavailable).
     *
     * @param \Magento\Quote\Api\Data\AddressInterface $shippingAddress
     * @return \Magento\Quote\Model\Quote\Address\Rate|null
     */
    public function getFallbackShippingMethod(\Magento\Quote\Api\Data\AddressInterface $shippingAddress)
    {
        /** @var \Magento\Quote\Model\Quote\Address $shippingAddress */
        /** @var \Magento\Quote\Model\Quote\Address\Rate $cheapest */
        $cheapest = null;

        $rates = $shippingAddress->getShippingRatesCollection();
        foreach ($rates as $rate) {
            if ($cheapest === null || $rate->getPrice() < $cheapest->getPrice()) {
                $cheapest = $rate;
            }
        }

        return $cheapest;
    }
}
