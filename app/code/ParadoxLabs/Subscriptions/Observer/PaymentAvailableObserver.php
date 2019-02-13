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
 * PaymentAvailableObserver Class
 */
class PaymentAvailableObserver implements \Magento\Framework\Event\ObserverInterface
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
     * @var \ParadoxLabs\Subscriptions\Model\Service\Payment
     */
    protected $paymentService;

    /**
     * GenerateSubscriptionsObserver constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager
     * @param \ParadoxLabs\Subscriptions\Model\Service\Payment $paymentService
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Config $config,
        \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager,
        \ParadoxLabs\Subscriptions\Model\Service\Payment $paymentService
    ) {
        $this->config = $config;
        $this->quoteManager = $quoteManager;
        $this->paymentService = $paymentService;
    }

    /**
     * Disable ineligible payment methods when purchasing a subscription. Tokenbase methods only.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->moduleIsActive() !== true) {
            return;
        }

        /** @var \Magento\Payment\Model\Method\AbstractMethod $method */
        $method = $observer->getEvent()->getData('method_instance');

        /** @var \Magento\Framework\DataObject $result */
        $result = $observer->getEvent()->getData('result');

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote  = $observer->getEvent()->getData('quote');

        /**
         * If it's already inactive, don't care.
         */
        if ((bool)$result->getData('is_available') === false) {
            return;
        }

        /**
         * If it's eligible, we don't care any further.
         */
        if ($this->paymentService->isAllowedForSubscription($method->getCode()) === true) {
            return;
        }

        /**
         * Otherwise, check if we have a subscription item. If so, not available.
         */
        if ($this->quoteManager->quoteContainsSubscription($quote)) {
            $result->setData('is_available', false);
        }
    }
}
