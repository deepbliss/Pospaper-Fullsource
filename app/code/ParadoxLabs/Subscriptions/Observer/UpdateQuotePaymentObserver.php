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
 * UpdateQuotePaymentObserver Class
 */

class UpdateQuotePaymentObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Quote\Model\ResourceModel\Quote\Payment\CollectionFactory
     */
    private $quotePaymentCollectionFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory
     */
    private $subscriptionCollectionFactory;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    private $config;

    /**
     * UpdateQuotePaymentObserver constructor.
     *
     * @param \Magento\Quote\Model\ResourceModel\Quote\Payment\CollectionFactory $quotePaymentCollectionFactory
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $subscriptionCollectionFactory
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \Magento\Quote\Model\ResourceModel\Quote\Payment\CollectionFactory $quotePaymentCollectionFactory,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $subscriptionCollectionFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->quotePaymentCollectionFactory = $quotePaymentCollectionFactory;
        $this->subscriptionCollectionFactory = $subscriptionCollectionFactory;
        $this->quoteRepository = $quoteRepository;
        $this->config = $config;
    }

    /**
     * Update subscription payment info on TokenBasecard save.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->moduleIsActive() !== true) {
            return;
        }

        /** @var \ParadoxLabs\TokenBase\Model\Card $card */
        $card = $observer->getEvent()->getData('object');

        if ($card instanceof \ParadoxLabs\TokenBase\Api\Data\CardInterface && $card->hasDataChanges()) {
            $this->updateQuotesForCard($card);
        }
    }

    /**
     * Update quote_payment with any updated credit card information.
     *
     * @param \ParadoxLabs\TokenBase\Api\Data\CardInterface $card
     * @return void
     */
    public function updateQuotesForCard(\ParadoxLabs\TokenBase\Api\Data\CardInterface $card)
    {
        /**
         * Load only subscriptions associated to the card in question, via quote payment records.
         */
        $payments = $this->quotePaymentCollectionFactory->create()
            ->addFieldToSelect('quote_id')
            ->addFieldToFilter('tokenbase_id', $card->getId());

        $quoteIds = $payments->getConnection()->fetchCol($payments->getSelect());

        if (empty($quoteIds)) {
            return;
        }

        /** @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\Collection $subscriptions */
        $subscriptions = $this->subscriptionCollectionFactory->create()
             ->addFieldToFilter('quote_id', ['in' => $quoteIds]);

        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            try {
                /** @var \Magento\Quote\Model\Quote $quote */
                $quote   = $subscription->getQuote();
                $payment = $quote->getPayment();

                $this->copyPrimaryFields($card, $payment);
                $this->copyAdditionalFields($card, $payment);
                $this->copyBillingAddress($card, $quote);

                $this->quoteRepository->save($quote);
            } catch (\Exception $e) {
                // No-op. Let failures pass so the save goes through.
            }
        }
    }

    /**
     * Copy primary data fields from a TokenBase payment card to quote payment object.
     *
     * @param \ParadoxLabs\TokenBase\Api\Data\CardInterface $card
     * @param \Magento\Payment\Model\Info $payment
     * @return void
     */
    public function copyPrimaryFields(
        \ParadoxLabs\TokenBase\Api\Data\CardInterface $card,
        \Magento\Payment\Model\Info $payment
    ) {
        // Not using fieldsets because the serialized data stores (addition, additional_information) won't work with it.
        $fieldsToCopy = [
            'cc_type',
            'cc_last_4',
            'cc_exp_month',
            'cc_exp_year',
        ];

        foreach ($fieldsToCopy as $key) {
            $value = $card->getAdditional($key);

            if ($value !== null) {
                $payment->setData($key, $value);
            } else {
                $payment->unsetData($key);
            }
        }
    }

    /**
     * Copy additional data fields from a TokenBase payment card to quote payment object.
     *
     * @param \ParadoxLabs\TokenBase\Api\Data\CardInterface $card
     * @param \Magento\Payment\Model\Info $payment
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function copyAdditionalFields(
        \ParadoxLabs\TokenBase\Api\Data\CardInterface $card,
        \Magento\Payment\Model\Info $payment
    ) {
        // Not using fieldsets because the serialized data stores (addition, additional_information) won't work with it.
        $additionalFieldsToCopy = [
            'echeck_account_name',
            'echeck_bank_name',
            'echeck_routing_no',
            'echeck_account_no',
            'echeck_account_type',
            'cc_bin',
        ];

        foreach ($additionalFieldsToCopy as $key) {
            $value = $card->getAdditional($key);

            if ($value !== null) {
                $payment->setAdditionalInformation($key, $value);
            } else {
                $payment->unsAdditionalInformation($key);
            }
        }
    }

    /**
     * Copy billing address from a TokenBase payment card to quote.
     *
     * @param \ParadoxLabs\TokenBase\Api\Data\CardInterface $card
     * @param \Magento\Quote\Model\Quote $quote
     * @return void
     */
    public function copyBillingAddress(
        \ParadoxLabs\TokenBase\Api\Data\CardInterface $card,
        \Magento\Quote\Model\Quote $quote
    ) {
        $quote->getBillingAddress()->importCustomerAddressData(
            $card->getAddressObject()
        );
    }
}
