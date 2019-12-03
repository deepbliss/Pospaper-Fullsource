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
 * Subscription service model: Common actions to be performed on subscriptions.
 *
 * @api
 */
class Subscription
{
    /**
     * @var \ParadoxLabs\TokenBase\Api\CardRepositoryInterface
     */
    protected $cardRepository;

    /**
     * @var \Magento\Framework\DataObject\Copy
     */
    protected $objectCopyService;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var \Magento\Quote\Model\QuoteManagement
     */
    protected $quoteManagement;

    /**
     * @var \Magento\Quote\Api\Data\CartInterfaceFactory
     */
    protected $quoteFactory;

    /**
     * @var \Magento\Quote\Api\Data\AddressInterfaceFactory
     */
    protected $quoteAddressFactory;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Customer\Api\AddressRepositoryInterface
     */
    protected $customerAddressRepository;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $orderSender;
    
    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    protected $helper;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\EmailSender
     */
    protected $emailSender;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    protected $emulator;

    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Vault
     */
    protected $vaultHelper;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface
     */
    protected $subscriptionRepository;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $dateProcessor;

    /**
     * @var \Magento\Framework\App\ProductMetadata
     */
    protected $productMetadata;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\QuoteManager
     */
    protected $quoteManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\CurrencyManager
     */
    protected $currencyManager;

    /**
     * Subscription service constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Service\Subscription\Context $context
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Service\Subscription\Context $context
    ) {
        $this->registry = $context->getRegistry();
        $this->objectCopyService = $context->getObjectCopyService();
        $this->cardRepository = $context->getCartRepository();
        $this->quoteRepository = $context->getCartRepository();
        $this->quoteManagement = $context->getQuoteManagement();
        $this->quoteFactory = $context->getQuoteFactory();
        $this->quoteAddressFactory = $context->getQuoteAddressFactory();
        $this->customerRepository = $context->getCustomerRepository();
        $this->customerAddressRepository = $context->getCustomerAddressRepository();
        $this->eventManager = $context->getEventManager();
        $this->orderSender = $context->getOrderSender();
        $this->logger = $context->getLogger();
        $this->helper = $context->getHelper();
        $this->emailSender = $context->getEmailSender();
        $this->emulator = $context->getEmulator();
        $this->vaultHelper = $context->getVaultHelper();
        $this->subscriptionRepository = $context->getSubscriptionRepository();
        $this->dateProcessor = $context->getDateProcessor();
        $this->productMetadata = $context->getProductMetadata();
        $this->quoteManager = $context->getQuoteManager();
        $this->currencyManager = $context->getCurrencyManager();
    }

    /**
     * Change subscription payment account to the given card.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @param string $hash Card hash owned by the subscription customer
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function changePaymentId(
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription,
        $hash
    ) {
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $subscription->getQuote();
        $card = $this->vaultHelper->getCardByHash($hash);

        try {
            $quoteCard = $this->vaultHelper->getQuoteCard($quote);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            // An exception here means the existing card was deleted. That's enough to know to process the change.
        }

        if (!isset($quoteCard) || $card->getPublicHash() !== $quoteCard->getPublicHash()) {
            if ($card instanceof \Magento\Vault\Api\Data\PaymentTokenInterface
                && ($card->getCustomerId() == $subscription->getCustomerId()
                    || $card instanceof \ParadoxLabs\Subscriptions\Model\OfflinePayment\Card)) {
                $payment = $quote->getPayment();

                if ($card instanceof \ParadoxLabs\TokenBase\Api\Data\CardInterface) {
                    /**
                     * Update billing address from the given card. Only known for TokenBase.
                     */
                    $this->objectCopyService->copyFieldsetToTarget(
                        'sales_copy_order_billing_address',
                        'to_order',
                        $card->getAddress(),
                        $quote->getBillingAddress()
                    );

                    $payment->setMethod($card->getPaymentMethodCode());
                    $payment->setData('tokenbase_id', $card->getId());
                } else {
                    /**
                     * Update payment data.
                     *
                     * token_metadata was used in 2.1.0-2.1.2. In 2.1.3 the values were moved to the top level.
                     */
                    if (version_compare($this->productMetadata->getVersion(), '2.1.3', '>=')) {
                        $payment->setAdditionalInformation('customer_id', $card->getCustomerId());
                        $payment->setAdditionalInformation('public_hash', $card->getPublicHash());
                    } else {
                        $payment->setAdditionalInformation(
                            'token_metadata',
                            [
                                'customer_id' => $card->getCustomerId(),
                                'public_hash' => $card->getPublicHash(),
                            ]
                        );
                    }

                    $payment->setMethod($this->vaultHelper->getVaultMethodCode($card->getPaymentMethodCode()));
                    $payment->setData('tokenbase_id', null);
                }

                $expires = strtotime($this->vaultHelper->getCardExpires($card));
                $payment->setData('cc_type', $this->vaultHelper->getCardType($card));
                $payment->setData('cc_last_4', $this->vaultHelper->getCardLast4($card));
                $payment->setData('cc_exp_year', date('Y', $expires));
                $payment->setData('cc_exp_month', date('m', $expires));

                $subscription->addRelatedObject($quote, true);

                $subscription->addLog(
                    __('Payment method changed to %1.', $this->vaultHelper->getCardLabel($card))
                );
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Invalid payment ID.')
                );
            }
        }

        return $this;
    }

    /**
     * Change subscription shipping address to the given data.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @param array $data Array of address info
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function changeShippingAddress(
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription,
        $data
    ) {
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $subscription->getQuote();
        $shippingAddress = $quote->getShippingAddress();

        if (isset($data['address_id']) && $data['address_id'] > 0 && $subscription->getCustomerId() > 0) {
            $customer = $this->customerRepository->getById($subscription->getCustomerId());

            if ($customer->getId() != $subscription->getCustomerId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(
                    __('Unable to load subscription customer.')
                );
            }

            $address = $this->customerAddressRepository->getById($data['address_id']);

            if ($address instanceof \Magento\Customer\Api\Data\AddressInterface
                && $address->getId() == $data['address_id']
                && $address->getCustomerId() == $customer->getId()) {
                $shippingAddress->importCustomerAddressData($address);
            } else {
                throw new \Magento\Framework\Exception\NoSuchEntityException(
                    __('Please choose a valid shipping address.')
                );
            }
        } else {
            $this->objectCopyService->copyFieldsetToTarget(
                'sales_convert_customer_address',
                'to_quote_address',
                $data,
                $shippingAddress
            );
        }

        $data = $shippingAddress->getData();
        foreach ($data as $key => $value) {
            if ($this->shouldSkipShippingAddressField($key) === false
                && !is_object($value)
                && $shippingAddress->getOrigData($key) != $value) {
                $this->emulator->startEnvironmentEmulation(
                    $subscription->getStoreId(),
                    \Magento\Framework\App\Area::AREA_FRONTEND,
                    true
                );

                $quote->collectTotals();

                $shippingAddress->validate();

                $shippingAddress->setCollectShippingRates(true)
                                ->collectShippingRates();

                $this->emulator->stopEnvironmentEmulation();

                $subscription->addLog(
                    __('Shipping address changed.')
                );

                $subscription->addRelatedObject($quote, true);

                break;
            }
        }

        return $this;
    }

    /**
     * Change subscription shipping method to the given code. Must be an available method.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @param string $methodCode
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function changeShippingMethod(
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription,
        $methodCode
    ) {
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $subscription->getQuote();
        $shippingAddress = $quote->getShippingAddress();

        if ($methodCode !== $shippingAddress->getShippingMethod()) {
            $rate = $shippingAddress->getShippingRateByCode($methodCode);
            if ($rate instanceof \Magento\Quote\Model\Quote\Address\Rate) {
                $shippingAddress->setShippingMethod($rate->getCode());
                $shippingAddress->setShippingDescription($rate->getMethodDescription());

                $subscription->addLog(
                    __('Shipping method changed to %1 - %2.', $rate->getCarrierTitle(), $rate->getMethodTitle())
                );

                $subscription->addRelatedObject($quote, true);
            } else {
                throw new \Magento\Framework\Exception\NoSuchEntityException(
                    __('Please choose a valid shipping method.')
                );
            }
        }

        return $this;
    }

    /**
     * Generate a hash from fulfillment details (billing, shipping, payment) for the given subscription.
     *
     * Used for identifying subscriptions that can be merged and billed together.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return string
     */
    public function hashFulfillmentInfo(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        $keys  = [];

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $subscription->getQuote();

        // Customer
        $keys['customer_id'] = $subscription->getCustomerId();

        // Store
        $keys['store_id'] = $subscription->getStoreId();

        // Payment
        try {
            $keys['payment_account'] = $this->vaultHelper->getQuoteCard($quote)->getPublicHash();
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            // No-op: Let missing payment info pass through to fail on generation and trigger proper error handling.
        }

        // Shipping
        if ((bool)$quote->getIsVirtual() === false) {
            $shippingAddr = $quote->getShippingAddress();
            $shippingKeys = [
                'shipping_method',
                'street',
                'city',
                'region',
                'region_id',
                'postcode',
                'country_id',
            ];

            foreach ($shippingKeys as $key) {
                $keys[$key] = $shippingAddr->getData($key);
            }
        }

        // Fire an event off to allow modifying the hash info for grouping.
        $transport = new \Magento\Framework\DataObject($keys);

        $this->eventManager->dispatch(
            'paradoxlabs_subscription_billing_hash_fulfillment_info',
            [
                'subscription' => $subscription,
                'service'      => $this,
                'transport'    => $transport,
            ]
        );

        return hash('sha256', implode('-', $transport->getData()));
    }

    /**
     * Generate order for the given subscription(s). If multiple given, they should all share the same
     * payment and shipping info.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface[] $subscriptions
     * @return bool Success
     */
    public function generateOrder($subscriptions)
    {
        /**
         * This wrapper function manages error handling and emulation.
         */

        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $firstSubscription */
        $firstSubscription = current($subscriptions);

        $this->emulator->startEnvironmentEmulation(
            $firstSubscription->getStoreId(),
            \Magento\Framework\App\Area::AREA_FRONTEND,
            true
        );

        try {
            foreach ($subscriptions as $subscription) {
                if ($subscription->getStatus() !== \ParadoxLabs\Subscriptions\Model\Source\Status::STATUS_ACTIVE) {
                    throw new \Magento\Framework\Exception\StateException(
                        __(
                            "Subscriptions may only be billed in the '%1' status. #%2 has status '%3'.",
                            \ParadoxLabs\Subscriptions\Model\Source\Status::STATUS_ACTIVE,
                            $subscription->getIncrementId(),
                            $subscription->getStatus()
                        )
                    );
                }
            }

            $this->generateOrderInternal($subscriptions);

            return true;
        } catch (\Exception $e) {
            $this->handleSubscriptionsError($subscriptions, $e);
        }

        $this->emulator->stopEnvironmentEmulation();

        return false;
    }

    /**
     * Generate order for the given subscription(s). If multiple given, they should all share the same
     * payment and shipping info.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface[] $subscriptions
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function generateOrderInternal($subscriptions)
    {
        /**
         * Initialize quote from first subscription
         */
        $quote = $this->generateBillingQuote(
            current($subscriptions)
        );

        /**
         * Add item(s) from each subscription
         */
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            /** @var \Magento\Quote\Model\Quote $subscriptionQuote */
            $subscriptionQuote = $subscription->getQuote();

            try {
                // Attach subscription object to each quote item individually, for later reference (as needed).
                foreach ($subscriptionQuote->getAllItems() as $item) {
                    $item->setData('subscription', $subscription);
                }
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                // A no-such-entity exception here means a product was deleted. Mark it and move on.
                $this->handleSubscriptionsError([$subscription], $e);
                continue;
            }

            // Merge item(s) onto the new quote.
            $quote->merge($subscriptionQuote);
        }

        $this->checkStock($quote);

        /**
         * Calculate shipping and totals
         */
        $this->eventManager->dispatch(
            'paradoxlabs_subscription_collect_totals_before',
            [
                'quote'         => $quote,
                'subscriptions' => $subscriptions,
            ]
        );

        $this->quoteManager->setQuoteShippingMethod(
            $quote,
            $quote->getShippingAddress()->getShippingMethod(),
            $quote->getShippingAddress()->getShippingDescription()
        );

        // Pull the new shipping amount (if any) into totals.
        $quote->setTotalsCollectedFlag(false)
              ->collectTotals();

        /**
         * Run the order
         */
        $this->eventManager->dispatch(
            'paradoxlabs_subscription_generate_before',
            [
                'quote'         => $quote,
                'subscriptions' => $subscriptions,
            ]
        );

        // This event allows for soft dependencies on payment methods (module won't be referenced unless used).
        $this->eventManager->dispatch(
            'paradoxlabs_subscription_prepare_payment_' . $quote->getPayment()->getMethod(),
            [
                'quote'         => $quote,
                'subscriptions' => $subscriptions,
            ]
        );

        $this->quoteRepository->save($quote);

        $ids = [];
        foreach ($subscriptions as $subscription) {
            $ids[] = $subscription->getIncrementId();
        }

        $this->helper->log(
            'subscriptions',
            __('Placing generateOrderInternal([%1])', implode(',', $ids))
        );

        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->quoteManagement->submit($quote);

        if (!($order instanceof \Magento\Sales\Api\Data\OrderInterface)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Failed to place order.')
            );
        }

        /**
         * Update post-order
         */
        $message = __(
            'Subscription billed. Order total: %1',
            $order->formatPriceTxt($order->getGrandTotal())
        );

        foreach ($subscriptions as $subscription) {
            $subscription->recordBilling($order, $message);
            $subscription->calculateNextRun();
        }

        $this->eventManager->dispatch(
            'paradoxlabs_subscription_generate_after',
            [
                'order'         => $order,
                'quote'         => $quote,
                'subscriptions' => $subscriptions,
            ]
        );

        foreach ($subscriptions as $subscription) {
            $this->subscriptionRepository->save($subscription);
        }

        /**
         * Send email
         */
        if ($order->getCanSendNewEmailFlag()) {
            try {
                $this->orderSender->send($order);
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
        }

        return $this;
    }

    /**
     * Generate a new quote from the given subscription info.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return \Magento\Quote\Model\Quote
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function generateBillingQuote(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        /**
         * Initialize objects
         */

        /** @var \Magento\Quote\Model\Quote $sourceQuote */
        $sourceQuote = $subscription->getQuote();

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteFactory->create();

        $this->objectCopyService->copyFieldsetToTarget(
            'sales_convert_quote',
            'to_subscription_quote',
            $sourceQuote,
            $quote
        );

        /**
         * Duplicate billing address
         */

        /** @var \Magento\Quote\Model\Quote\Address $billingAddress */
        $billingAddress = $this->quoteAddressFactory->create();

        $this->objectCopyService->copyFieldsetToTarget(
            'sales_copy_order_billing_address',
            'to_order',
            $sourceQuote->getBillingAddress(),
            $billingAddress
        );

        $billingAddress->setCustomerId($sourceQuote->getBillingAddress()->getCustomerId());

        // Prevent 'no such address' errors if the address was deleted, but still keep the association if valid.
        if ($billingAddress->getCustomerAddressId()) {
            try {
                $billingAddress->importCustomerAddressData(
                    $this->customerAddressRepository->getById($billingAddress->getCustomerAddressId())
                );
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $billingAddress->setCustomerAddressId(null);
            }
        }

        /**
         * Duplicate shipping address
         */

        /** @var \Magento\Quote\Model\Quote\Address $shippingAddress */
        $shippingAddress = $this->quoteAddressFactory->create();

        $this->objectCopyService->copyFieldsetToTarget(
            'sales_copy_order_shipping_address',
            'to_order',
            $sourceQuote->getShippingAddress(),
            $shippingAddress
        );

        $shippingAddress->setShippingMethod($sourceQuote->getShippingAddress()->getShippingMethod())
                        ->setShippingDescription($sourceQuote->getShippingAddress()->getShippingDescription())
                        ->setCustomerId($sourceQuote->getShippingAddress()->getCustomerId());

        // Prevent 'no such address' errors if the address was deleted, but still keep the association if valid.
        if ($shippingAddress->getCustomerAddressId()) {
            try {
                $shippingAddress->importCustomerAddressData(
                    $this->customerAddressRepository->getById($shippingAddress->getCustomerAddressId())
                );
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $shippingAddress->setCustomerAddressId(null);
            }
        }

        /**
         * Duplicate payment object
         */

        $this->objectCopyService->copyFieldsetToTarget(
            'sales_convert_order_payment',
            'to_quote_payment',
            $sourceQuote->getPayment(),
            $quote->getPayment()
        );

        $quote->getPayment()->setId(null);
        $quote->getPayment()->setQuoteId(null);

        // Record the quote/order source to prevent a generation loop
        $this->quoteManager->setQuoteExistingSubscription($quote);

        // Force quote currency. Normally this gets pulled from the customer's session.
        // NB: Quote checks for 'forced_currency', but that's not implemented fully. Doesn't cover item collection.
        $quoteCurrency = $this->currencyManager->getCurrencyByCode($quote->getQuoteCurrencyCode());
        $quote->getStore()->setData('current_currency', $quoteCurrency);

        /**
         * Duplicate customer info
         */
        $customerId = $subscription->getCustomerId();

        if ($customerId > 0) {
            try {
                $customer = $this->customerRepository->getById($customerId);

                $quote->assignCustomer($customer);
            } catch (\Exception $e) {
                // Ignore missing customer error -- guest data was copied in sales_convert_quote
            }
        }

        /**
         * Pull quote together
         */

        $now = $this->dateProcessor->date(null, null, false);

        $quote->setIsMultiShipping(false)
              ->setIsActive(false)
              ->setUpdatedAt($now->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT))
              ->setBillingAddress($billingAddress)
              ->setShippingAddress($shippingAddress);

        return $quote;
    }

    /**
     * Run stock checks on our generated quote's items.
     *
     * We have to do this because Magento bypasses it on quote merge.
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function checkStock(\Magento\Quote\Model\Quote $quote)
    {
        $errors = [];
        $itemCount = 0;

        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($quote->getAllItems() as $item) {
            $item->setQty($item->getQty());

            $itemCount++;

            if ($item->getHasError()) {
                $message = $item->getMessage();
                if (!in_array($message, $errors) && !empty($message)) {
                    // filter duplicate messages
                    $errors[] = $message;
                }
            }
        }

        if (!empty($errors)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __(implode("\n", $errors))
            );
        }
        if ($itemCount === 0) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Subscription does not contain any items to bill.')
            );
        }

        return $this;
    }

    /**
     * Handle exceptions from the subscription generation process.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface[] $subscriptions
     * @param \Exception $exception
     * @return $this
     */
    protected function handleSubscriptionsError($subscriptions, \Exception $exception)
    {
        try {
            $ids = [];
            foreach ($subscriptions as $subscription) {
                $ids[] = $subscription->getIncrementId();
            }

            $this->helper->log(
                'subscriptions',
                __('Error on generateOrder([%1]): %2', implode(',', $ids), (string)$exception)
            );

            $this->eventManager->dispatch(
                'paradoxlabs_subscription_billing_failed',
                [
                    'subscriptions' => $subscriptions,
                    'exception'     => $exception,
                ]
            );

            if ($this->isPaymentException($exception)) {
                $this->changeSubscriptionsStatus(
                    $subscriptions,
                    \ParadoxLabs\Subscriptions\Model\Source\Status::STATUS_PAYMENT_FAILED,
                    (string)__('ERROR: %1', $exception->getMessage())
                );

                $this->sendPaymentFailedEmail($subscriptions, $exception);
            } else {
                $this->changeSubscriptionsStatus(
                    $subscriptions,
                    \ParadoxLabs\Subscriptions\Model\Source\Status::STATUS_PAUSED,
                    (string)__('ERROR: %1', $exception->getMessage())
                );
            }

            $this->sendBillingFailedEmail($subscriptions, $exception);
        } catch (\Exception $e) {
            $this->helper->log(
                'subscriptions',
                __('Error while handling "%1": %2', $exception->getMessage(), (string)$e)
            );
        }

        return $this;
    }

    /**
     * Send billing failure email to admin
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface[] $subscriptions
     * @param \Exception $exception
     * @return $this
     */
    public function sendBillingFailedEmail($subscriptions, \Exception $exception)
    {
        foreach ($subscriptions as $subscription) {
            $this->emailSender->sendBillingFailedEmail($subscription, $exception->getMessage());
        }

        return $this;
    }

    /**
     * Send payment failure email to customer
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface[] $subscriptions
     * @param \Exception $exception
     * @return $this
     */
    public function sendPaymentFailedEmail($subscriptions, \Exception $exception)
    {
        foreach ($subscriptions as $subscription) {
            $this->emailSender->sendPaymentFailedEmail($subscription, $exception->getMessage());
        }

        return $this;
    }

    /**
     * Set status for the given subscriptions, and log the change.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface[] $subscriptions
     * @param string $status
     * @param null $message
     * @return $this
     */
    public function changeSubscriptionsStatus($subscriptions, $status, $message = null)
    {
        foreach ($subscriptions as $subscription) {
            $subscription->setStatus($status, $message);
            $this->subscriptionRepository->save($subscription);
        }

        return $this;
    }

    /**
     * Determine whether the given address field should be skipped when evaluating shipping address for changes.
     *
     * @param string $field
     * @return bool
     */
    protected function shouldSkipShippingAddressField($field)
    {
        $excludeFields = [
            'entity_id',
            'region',
        ];

        return in_array($field, $excludeFields, true);
    }

    /**
     * Determine whether the given exception is considered a payment exception (user-fixable, ideally).
     *
     * @param \Exception $exception
     * @return bool
     */
    protected function isPaymentException(\Exception $exception)
    {
        return $exception instanceof \Magento\Framework\Exception\PaymentException
            || $exception instanceof \Magento\Payment\Gateway\Http\ClientException
            || $exception instanceof \Magento\Payment\Gateway\Command\CommandException
            || $exception instanceof \Magento\Paypal\Model\Api\ProcessableException;
    }
}
