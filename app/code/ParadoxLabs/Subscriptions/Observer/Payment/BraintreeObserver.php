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

namespace ParadoxLabs\Subscriptions\Observer\Payment;

/**
 * BraintreeObserver Class
 */
class BraintreeObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Braintree\Gateway\Command\GetPaymentNonceCommand
     */
    protected $nonceCommand;

    /**
     * BraintreeObserver constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Helper\Data $helper
     * @param \Magento\Braintree\Gateway\Command\GetPaymentNonceCommand $nonceCommand
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Helper\Data $helper,
        \Magento\Braintree\Gateway\Command\GetPaymentNonceCommand $nonceCommand
    ) {
        $this->helper = $helper;
        $this->nonceCommand = $nonceCommand;
    }

    /**
     * On a Braintree subscription rebilling, fetch a nonce before billing.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');

        // Workaround for potential 'payment has no quote' fatal error
        $quote->getPayment()->setQuote($quote);

        if ($this->isQuoteBraintree($quote) && $quote->getPayment()->getMethodInstance()->isActive()) {
            /**
             * In order to process a braintree payment, we have to get a nonce from the payment key
             * before running the order. Normally this happens via a browser request during checkout.
             *
             * token_metadata was used in 2.1.0-2.1.2. In 2.1.3 the values were moved to the top level.
             */
            $metaData = $quote->getPayment()->getAdditionalInformation('token_metadata')
                ?: $quote->getPayment()->getAdditionalInformation();

            // Any error here would be exceptional. We want that.
            $result = $this->nonceCommand->execute(
                [
                    'public_hash' => $metaData['public_hash'],
                    'customer_id' => $metaData['customer_id'],
                ]
            )->get();

            $quote->getPayment()->setAdditionalInformation(
                'payment_method_nonce',
                $result['paymentMethodNonce']
            );
        }
    }

    /**
     * Determine whether the given quote is using Braintree payment.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return bool
     */
    protected function isQuoteBraintree(\Magento\Quote\Api\Data\CartInterface $quote)
    {
        $braintreeMethods = [
            'braintree',
            'braintree_paypal',
            'braintree_cc_vault',
            'braintree_paypal_vault',
        ];

        /** @var \Magento\Quote\Model\Quote $quote */
        if (in_array($quote->getPayment()->getMethod(), $braintreeMethods, true) !== false) {
            return true;
        }

        return false;
    }
}
