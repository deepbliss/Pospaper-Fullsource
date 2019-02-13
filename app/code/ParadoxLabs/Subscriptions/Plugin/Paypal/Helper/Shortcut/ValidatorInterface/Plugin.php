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

namespace ParadoxLabs\Subscriptions\Plugin\Paypal\Helper\Shortcut\ValidatorInterface;

/**
 * Plugin Class
 */
class Plugin
{
    /**
     * @var \ParadoxLabs\TokenBase\Helper\Data
     */
    private $helper;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\QuoteManager
     */
    private $quoteManager;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * Plugin constructor.
     *
     * @param \ParadoxLabs\TokenBase\Helper\Data $helper
     * @param \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager
     * @param \Magento\Checkout\Model\Session $checkoutSession *Proxy
     */
    public function __construct(
        \ParadoxLabs\TokenBase\Helper\Data $helper,
        \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->helper = $helper;
        $this->quoteManager = $quoteManager;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Enforce PayPal Express availability with subscriptions across site.
     *
     * @param \Magento\Paypal\Helper\Shortcut\ValidatorInterface $subject
     * @param bool $result
     * @return bool
     */
    public function afterValidate(\Magento\Paypal\Helper\Shortcut\ValidatorInterface $subject, $result)
    {
        // If PayPal Express is coming out as allowed, make sure the quote doesn't contain a subscription.
        // Express does not allow payment storage, so it's not compatible.

        if ($result === true && $this->helper->getIsFrontend()) {
            /** @var \Magento\Quote\Api\Data\CartInterface $quote */
            $quote = $this->checkoutSession->getQuote();

            if ($this->quoteManager->quoteContainsSubscription($quote) === true) {
                return false;
            }
        }

        return $result;
    }
}
