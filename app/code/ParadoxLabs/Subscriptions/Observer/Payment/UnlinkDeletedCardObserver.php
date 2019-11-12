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
 * UnlinkDeletedCardObserver Class
 */
class UnlinkDeletedCardObserver implements \Magento\Framework\Event\ObserverInterface
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
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $subscriptionColnFactory
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \Magento\Quote\Model\ResourceModel\Quote\Payment\CollectionFactory $quotePaymentCollectionFactory,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $subscriptionColnFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->quotePaymentCollectionFactory = $quotePaymentCollectionFactory;
        $this->subscriptionCollectionFactory = $subscriptionColnFactory;
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

        if ($card instanceof \ParadoxLabs\TokenBase\Api\Data\CardInterface) {
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

                $this->unlinkCard($card, $payment);

                $this->quoteRepository->save($quote);
            } catch (\Exception $e) {
                // No-op. Let failures pass so the save goes through.
            }
        }
    }

    /**
     * If card has been queued for deletion, unlink it from subscription quotes so they don't rebill with it.
     *
     * @param \ParadoxLabs\TokenBase\Api\Data\CardInterface $card
     * @param \Magento\Quote\Model\Quote\Payment $payment
     * @return void
     */
    public function unlinkCard(
        \ParadoxLabs\TokenBase\Api\Data\CardInterface $card,
        \Magento\Quote\Model\Quote\Payment $payment
    ) {
        $payment->setData('tokenbase_id', null);
    }
}
