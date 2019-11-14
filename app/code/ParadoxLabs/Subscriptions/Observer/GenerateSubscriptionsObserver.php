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

use \ParadoxLabs\Subscriptions\Model\Source\Status;

/**
 * GenerateSubscriptionsObserver Class
 */
class GenerateSubscriptionsObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    protected $helper;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\SubscriptionFactory
     */
    protected $subscriptionFactory;

    /**
     * @var \Magento\Quote\Api\Data\CartInterfaceFactory
     */
    protected $quoteFactory;

    /**
     * @var \Magento\Quote\Api\Data\AddressInterfaceFactory
     */
    protected $quoteAddressFactory;

    /**
     * @var \Magento\Framework\DataObject\Copy
     */
    protected $objectCopyService;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

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
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    protected $emulator;

    /**
     * @var \Magento\Framework\App\ProductMetadata
     */
    protected $productMetadata;

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
    protected $config;

    /**
     * @var \Magento\Sales\Api\OrderCustomerManagementInterface
     */
    protected $orderCustomerManager;

    /**
     * @var \ParadoxLabs\TokenBase\Helper\Data
     */
    protected $tokenbaseHelper;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * GenerateSubscriptionsObserver constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Observer\GenerateSubscriptionsObserver\Context $context
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Observer\GenerateSubscriptionsObserver\Context $context
    ) {
        $this->helper = $context->getHelper();
        $this->subscriptionFactory = $context->getSubscriptionFactory();
        $this->quoteFactory = $context->getQuoteFactory();
        $this->quoteAddressFactory = $context->getQuoteAddressFactory();
        $this->customerRepository = $context->getCustomerRepository();
        $this->objectCopyService = $context->getObjectCopyService();
        $this->vaultHelper = $context->getVaultHelper();
        $this->subscriptionRepository = $context->getSubscriptionRepository();
        $this->dateProcessor = $context->getDateProcessor();
        $this->cartRepository = $context->getCartRepository();
        $this->orderRepository = $context->getOrderRepository();
        $this->storeManager = $context->getStoreManager();
        $this->emulator = $context->getEmulator();
        $this->productMetadata = $context->getProductMetadata();
        $this->quoteManager = $context->getQuoteManager();
        $this->itemManager = $context->getItemManager();
        $this->config = $context->getConfig();
        $this->orderCustomerManager = $context->getOrderCustomerManager();
        $this->tokenbaseHelper = $context->getTokenbaseHelper();
        $this->customerSession = $context->getCustomerSession();
    }

    /**
     * Create subscriptions as needed on order place.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->moduleIsActive() !== true) {
            return;
        }

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');

        // Don't process any further unless a subscription was ordered.
        if ($this->quoteManager->quoteContainsSubscription($quote) === false) {
            return;
        }

        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');

        if ($order instanceof \Magento\Sales\Api\Data\OrderInterface) {
            // Standard checkouts
            $this->processOrder($order);
        } else {
            // Multishipping checkout ... multiple orders
            $orders = $observer->getEvent()->getData('orders');

            if (count($orders)) {
                foreach ($orders as $order) {
                    if ($order instanceof \Magento\Sales\Api\Data\OrderInterface) {
                        $this->processOrder($order);
                    }
                }
            }
        }
    }

    /**
     * Create subscriptions for any subscription items purchased in the given order.
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return void
     * @throws \Exception
     */
    public function processOrder(
        \Magento\Sales\Api\Data\OrderInterface $order
    ) {
        /** @var \Magento\Sales\Model\Order $order */
        /** @var \Magento\Sales\Model\Order\Payment $payment */
        $payment = $order->getPayment();

        // Ensure we don't end up generating new subscriptions from existing ones.
        if ((int)$payment->getAdditionalInformation('is_subscription_generated') === 1) {
            return;
        }

        // If we are not in the correct scope, emulate it to ensure everything comes out correct.
        $emulate = ((int)$this->storeManager->getStore()->getStoreId() !== (int)$order->getStoreId());
        if ($emulate === true) {
            $this->emulator->startEnvironmentEmulation($order->getStoreId());
        }

        // Ensure customer is registered
        $this->registerCustomer($order);

        /** @var \Magento\Sales\Model\Order\Item $item */
        foreach ($order->getAllItems() as $item) {
            if ($this->itemManager->isSubscription($item) === true) {
                /**
                 * For each active subscription item,
                 * Create a matching quote
                 * Initialize an associated subscription
                 */

                try {
                    $subscription = $this->generateSubscription($order, $item);

                    $message = __(
                        'Subscription created. Initial order total: %1',
                        $order->formatPriceTxt($order->getGrandTotal())
                    );

                    $subscription->recordBilling($order, $message);

                    $this->subscriptionRepository->save($subscription);
                } catch (\Exception $e) {
                    $this->helper->log('subscriptions', (string)$e);

                    if ($emulate === true) {
                        $this->emulator->stopEnvironmentEmulation();
                    }

                    throw $e;
                }
            }
        }

        if ($emulate === true) {
            $this->emulator->stopEnvironmentEmulation();
        }
    }

    /**
     * Create a subscription for the given item.
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @param \Magento\Sales\Api\Data\OrderItemInterface $item
     * @return \ParadoxLabs\Subscriptions\Model\Subscription
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function generateSubscription(
        \Magento\Sales\Api\Data\OrderInterface $order,
        \Magento\Sales\Api\Data\OrderItemInterface $item
    ) {
        /** @var \Magento\Sales\Model\Order\Item $item */

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->generateSubscriptionQuote($order, $item);

        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
        $subscription = $this->subscriptionFactory->create();

        $subscription->setStoreId($quote->getStoreId());
        $subscription->setStatus(Status::STATUS_ACTIVE);
        $subscription->setCustomerId($quote->getCustomerId());
        $subscription->setQuote($quote);
        $subscription->setFrequencyCount($this->itemManager->getFrequencyCount($item));
        $subscription->setFrequencyUnit($this->itemManager->getFrequencyUnit($item));
        $subscription->setLength($this->itemManager->getLength($item));
        $subscription->setDescription($this->itemManager->getSubscriptionDescription($item));
        $subscription->setSubtotal($quote->getSubtotal());
        $subscription->calculateNextRun();

        $subscription->addRelatedObject($quote, true);

        return $subscription;
    }

    /**
     * Create a subscription base quote for the given item.
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @param \Magento\Sales\Api\Data\OrderItemInterface $item
     * @return \Magento\Quote\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function generateSubscriptionQuote(
        \Magento\Sales\Api\Data\OrderInterface $order,
        \Magento\Sales\Api\Data\OrderItemInterface $item
    ) {
        /**
         * Initialize objects
         */

        /** @var \Magento\Sales\Model\Order\Item $item */
        /** @var \Magento\Sales\Model\Order $order */
        /** @var \Magento\Quote\Model\Quote $orderQuote */
        $orderQuote = $this->cartRepository->get($order->getQuoteId());

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteFactory->create();

        $this->objectCopyService->copyFieldsetToTarget(
            'sales_convert_order',
            'to_subscription_quote',
            $order,
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
            $orderQuote->getBillingAddress(),
            $billingAddress
        );

        $billingAddress->setCustomerId($orderQuote->getCustomerId());
        $billingAddress->setEmail($orderQuote->getCustomerEmail());

        /**
         * Duplicate shipping address
         */

        /** @var \Magento\Quote\Model\Quote\Address $shippingAddress */
        $shippingAddress = $this->quoteAddressFactory->create();

        $this->objectCopyService->copyFieldsetToTarget(
            'sales_copy_order_shipping_address',
            'to_order',
            $orderQuote->getShippingAddress(),
            $shippingAddress
        );

        $shippingAddress->setCustomerId($orderQuote->getCustomerId());
        $shippingAddress->setEmail($orderQuote->getCustomerEmail());

        /**
         * Duplicate payment object
         */

        $this->objectCopyService->copyFieldsetToTarget(
            'sales_convert_order_payment',
            'to_quote_payment',
            $order->getPayment(),
            $quote->getPayment()
        );

        $quote->getPayment()->setId(null);
        $quote->getPayment()->setQuoteId(null);

        // Record the quote/order source to prevent a generation loop
        $this->quoteManager->setQuoteExistingSubscription($quote);

        $this->prepareVaultData($quote, $order);

        /**
         * Duplicate customer info
         */
        $this->objectCopyService->copyFieldsetToTarget(
            'sales_convert_order_customer',
            'to_quote',
            $order,
            $quote
        );

        // Try to load and set customer.
        $customerId = $order->getCustomerId();

        if ($customerId > 0) {
            try {
                $customer = $this->customerRepository->getById($customerId);

                $quote->assignCustomer($customer);
            } catch (\Exception $e) {
                // Ignore missing customer error
            }
        }

        /**
         * Pull quote together
         */

        // Set a far-off quote updated date to avoid pruning. This is the highest Magento allows (timestamp).
        $updatedAt = $this->dateProcessor->date('2038-01-01', null, false);

        $quote->setIsMultiShipping(false)
              ->setIsActive(false)
              ->setUpdatedAt($updatedAt->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT))
              ->setBillingAddress($billingAddress)
              ->setShippingAddress($shippingAddress);

        /**
         * Set the product and price
         */
        $this->addItemToQuote($quote, $item);

        /**
         * Set shipping info
         */
        $this->setQuoteShippingMethod($quote, $order);

        $quote->setTotalsCollectedFlag(false)
              ->collectTotals();

        return $quote;
    }

    /**
     * Add our subscription item+product to the new quote, with appropriate pricing.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @param \Magento\Sales\Api\Data\OrderItemInterface $item
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function addItemToQuote(
        \Magento\Quote\Api\Data\CartInterface $quote,
        \Magento\Sales\Api\Data\OrderItemInterface $item
    ) {
        /** @var \Magento\Quote\Model\Quote $quote */
        /** @var \Magento\Sales\Model\Order\Item $item */

        $product = $item->getProduct();

        if (!$product->getId()) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Could not find product for item %1 (%2)', $item->getSku(), $item->getId())
            );
        }

        $info = $this->itemManager->getInfoBuyRequest($item);

        $quote->addProduct($product, $info);

        /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
        $quoteItem = $quote->getItemsCollection()->getFirstItem();

        $newPrice = $this->itemManager->calculatePrice(
            $quoteItem,
            2,
            $quote->getBaseCurrencyCode(),
            $quote->getQuoteCurrencyCode()
        );

        if ($newPrice != $product->getFinalPrice()) {
            $quoteItem->setCustomPrice($newPrice);
            $quoteItem->setOriginalCustomPrice($newPrice);
        }
    }

    /**
     * Set shipping method on the new quote.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return void
     */
    protected function setQuoteShippingMethod(
        \Magento\Quote\Api\Data\CartInterface $quote,
        \Magento\Sales\Api\Data\OrderInterface $order
    ) {
        /** @var \Magento\Sales\Model\Order $order */

        $this->quoteManager->setQuoteShippingMethod(
            $quote,
            $order->getShippingMethod(),
            $order->getShippingDescription()
        );
    }

    /**
     * If the payment method is not TokenBase, convert it to its proper vault form for later.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function prepareVaultData(
        \Magento\Quote\Api\Data\CartInterface $quote,
        \Magento\Sales\Api\Data\OrderInterface $order
    ) {
        /** @var \Magento\Quote\Model\Quote $quote */
        /** @var \Magento\Sales\Model\Order $order */

        if ($this->vaultHelper->isQuoteVaultPayment($quote) === true) {
            $payment = $quote->getPayment();
            $method = $payment->getMethod();

            if (strpos($method, '_cc_vault') === false) {
                $payment->setMethod($method . '_cc_vault');
            }

            // token_metadata was used in 2.1.0-2.1.2. In 2.1.3 the values were moved to the top level.
            $metadata = $payment->getAdditionalInformation('token_metadata')
                ?: $payment->getAdditionalInformation();

            if ($metadata === null || !isset($metadata['public_hash']) || empty($metadata['public_hash'])) {
                // We're missing the vault info. Fetch and store it.

                /** @var \Magento\Sales\Model\Order\Payment $orderPayment */
                $orderPayment = $order->getPayment();

                if ($orderPayment->getId() === null) {
                    // The order must be saved to trigger vault hash generation.
                    // @see \Magento\Vault\Observer\AfterPaymentSaveObserver::execute()
                    $this->orderRepository->save($order);
                }

                $vault = $this->getVaultExtension($orderPayment->getExtensionAttributes());

                if ($vault !== null) {
                    // Fix Vault customer ID if guest checkout.
                    if ($vault->getCustomerId() === null) {
                        $this->vaultHelper->fixCardCustomerId($vault, $order->getCustomerId());
                    }

                    // Store the data at the appropriate spot.
                    if (version_compare($this->productMetadata->getVersion(), '2.1.3', '>=')) {
                        $payment->setAdditionalInformation('customer_id', $vault->getCustomerId());
                        $payment->setAdditionalInformation('public_hash', $vault->getPublicHash());
                    } else {
                        $payment->setAdditionalInformation(
                            'token_metadata',
                            [
                                'customer_id' => $vault->getCustomerId(),
                                'public_hash' => $vault->getPublicHash(),
                            ]
                        );
                    }
                } else {
                    throw new \Magento\Framework\Exception\LocalizedException(
                        __('Could not find payment card.')
                    );
                }
            }

            // Set Vault card payment info on the quote.
            $card = $this->vaultHelper->getQuoteCard($quote);
            $expires = strtotime($this->vaultHelper->getCardExpires($card));
            $payment->setData('cc_type', $this->vaultHelper->getCardType($card));
            $payment->setData('cc_last_4', $this->vaultHelper->getCardLast4($card));
            $payment->setData('cc_exp_year', date('Y', $expires));
            $payment->setData('cc_exp_month', date('m', $expires));
        }

        return $this;
    }

    /**
     * Get the Vault order payment extension (Vault card), if any.
     *
     * @param \Magento\Sales\Api\Data\OrderPaymentExtensionInterface|null $extensionAttributes
     * @return \Magento\Vault\Api\Data\PaymentTokenInterface|null
     */
    protected function getVaultExtension(
        \Magento\Sales\Api\Data\OrderPaymentExtensionInterface $extensionAttributes = null
    ) {
        if ($extensionAttributes === null) {
            return null;
        }

        $card = $extensionAttributes->getVaultPaymentToken();
        if ($card === null || empty($card->getGatewayToken())) {
            return null;
        }

        return $card;
    }

    /**
     * Register customer account for the order if not already registered. Subscriptions must have an account.
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return $this
     */
    protected function registerCustomer(
        \Magento\Sales\Api\Data\OrderInterface $order
    ) {
        /** @var \Magento\Sales\Model\Order $order */

        if ($order->getCustomerId() === null) {
            // Load customer by email, create if not exists.
            try {
                $customerData = $this->customerRepository->get($order->getCustomerEmail());
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $customerData = $this->orderCustomerManager->create($order->getId());
            }

            $order->setCustomerId($customerData->getId());
        }

        return $this;
    }
}
