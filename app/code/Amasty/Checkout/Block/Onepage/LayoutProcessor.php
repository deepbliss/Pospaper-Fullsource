<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Onepage;

use Amasty\Checkout\Model\Config;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Checkout\Helper\Data as CheckoutHelper;

class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;
    /**
     * @var \Amasty\Checkout\Model\Gift\Messages
     */
    protected $giftMessages;
    /**
     * @var \Amasty\Checkout\Api\FeeRepositoryInterface
     */
    protected $feeRepository;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;
    /**
     * @var \Amasty\Checkout\Model\DeliveryDate
     */
    protected $deliveryDate;
    /**
     * @var \Amasty\Checkout\Model\Delivery
     */
    protected $delivery;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Amasty\Checkout\Plugin\AttributeMerger
     */
    protected $attributeMerger;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Magento\Newsletter\Model\Subscriber
     */
    protected $subscriber;

    /**
     * @var \Amasty\Checkout\Model\Config
     */
    private $checkoutConfig;

    /**
     * @var \Amasty\Checkout\Model\Utility
     */
    private $utility;

    /**
     * @var CheckoutHelper
     */
    private $checkoutHelper;

    /**
     * @var \Amasty\Checkout\Model\ModuleEnable
     */
    private $moduleEnable;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        PriceCurrencyInterface $priceCurrency,
        CheckoutHelper $checkoutHelper,
        \Amasty\Checkout\Model\Gift\Messages $giftMessages,
        \Amasty\Checkout\Api\FeeRepositoryInterface $feeRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Amasty\Checkout\Model\DeliveryDate $deliveryDate,
        StoreManagerInterface $storeManager,
        \Amasty\Checkout\Model\Delivery $delivery,
        \Amasty\Checkout\Plugin\AttributeMerger $attributeMerger,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\Subscriber $subscriber,
        \Amasty\Checkout\Model\Config $checkoutConfig,
        \Amasty\Checkout\Model\Utility $utility,
        \Amasty\Checkout\Model\ModuleEnable $moduleEnable
    ) {
        $this->checkoutHelper = $checkoutHelper;
        $this->scopeConfig = $scopeConfig;
        $this->priceCurrency = $priceCurrency;
        $this->giftMessages = $giftMessages;
        $this->feeRepository = $feeRepository;
        $this->checkoutSession = $checkoutSession;
        $this->deliveryDate = $deliveryDate;
        $this->delivery = $delivery;
        $this->storeManager = $storeManager;
        $this->attributeMerger = $attributeMerger;
        $this->customerSession = $customerSession;
        $this->subscriber = $subscriber;
        $this->checkoutConfig = $checkoutConfig;
        $this->utility = $utility;
        $this->moduleEnable = $moduleEnable;
    }

    public function process($jsLayout)
    {
        if ($this->scopeConfig->isSetFlag('amasty_checkout/general/enabled', ScopeInterface::SCOPE_STORE)) {
            $attributeConfig = $this->attributeMerger->getFieldConfig();

            if ($this->checkoutSession->getQuote()->isVirtual()) {
                $layout = 'virtual';
            } else {
                $layout = $this->scopeConfig->getValue('amasty_checkout/design/layout', ScopeInterface::SCOPE_STORE);
            }

            $shippingStep = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step'];
            if (isset($shippingStep['children'])) {
                $stepConfig = &$shippingStep['children']['step-config'];
                if (isset($stepConfig['children'])) {
                    $shippingRatesValidation = &$stepConfig['children']['shipping-rates-validation'];
                    if (isset($shippingRatesValidation['children'])) {
                        $shippingRatesValidationChildren = &$shippingRatesValidation['children'];
                        if (isset($shippingRatesValidationChildren['flatrate-rates-validation'])) {
                            $flatRateValidation = &$shippingRatesValidationChildren['flatrate-rates-validation'];
                            $flatRateValidation['component'] = 'Amasty_Checkout/js/view/override/shipping-rates-validation/flatrate';
                        }
                    }
                }
            }
            $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['component'] =
                'Amasty_Checkout/js/view/summary';

            $jsLayout['components']['checkout']['config']['template'] = 'Amasty_Checkout/onepage/' . $layout;
            $jsLayout['components']['checkout']['component'] = 'Amasty_Checkout/js/view/onepage';

            $shippingAddress = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress'];
            $shippingAddress['component'] = $this->moduleEnable->isPostNlEnable()
                ? 'Amasty_Checkout/js/view/shipping-postnl'
                : 'Amasty_Checkout/js/view/shipping';
            $shippingAddress['reloadPayments'] =
                $this->scopeConfig->isSetFlag('amasty_checkout/general/reload_payments', ScopeInterface::SCOPE_STORE);
            $shippingAddress['children']['shipping-address-fieldset']['children']['region_id']['component'] = 'Amasty_Checkout/js/form/element/region';

            if (isset($attributeConfig['postcode'])) {
                $shippingAddress['children']['shipping-address-fieldset']['children']['postcode']['component'] = 'Amasty_Checkout/js/form/element/post-code';
                $shippingAddress['children']['shipping-address-fieldset']['children']['postcode']['skipValidation'] = !$attributeConfig['postcode']->getData('required');
                $postcodeShippingAddress = &$shippingAddress['children']['shipping-address-fieldset']['children']['postcode'];
                if (isset($postcodeShippingAddress['validation']) && isset($postcodeShippingAddress['validation']['required-entry'])) {
                    $requiedEntry = $postcodeShippingAddress['validation']['required-entry'];
                    $postcodeShippingAddress['skipValidation'] = !$requiedEntry;
                }
            }

            $shippingAddressFields = &$shippingAddress['children']['shipping-address-fieldset']['children'];
            $shippingAddressFields = $this->setRequiredField($shippingAddressFields);
            
            $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['component'] = 'Amasty_Checkout/js/view/payment';

            $newsletterConfig = $this->scopeConfig->isSetFlag(
                'amasty_checkout/additional_options/newsletter',
                ScopeInterface::SCOPE_STORE
            );

            if ($this->customerSession->isLoggedIn() && $newsletterConfig) {
                $customerId = $this->customerSession->getCustomerId();
                $this->subscriber->loadByCustomerId($customerId);
                $newsletterConfig = !$this->subscriber->isSubscribed();
            }

            //OSC-128
            /*$subscribeAuthorization = $this->scopeConfig->getValue(
                'amasty_checkout/general/subscribe_authorization',
                ScopeInterface::SCOPE_STORE
            );*/

            //Hidden for OSC-128
            //if (!$newsletterConfig && !$subscribeAuthorization) {
            if (!$newsletterConfig) {
                unset($jsLayout['components']['checkout']['children']['additional']['children']['subscribe']);
            } else {
                $checked = $this->scopeConfig->isSetFlag(
                    'amasty_checkout/additional_options/newsletter_checked', ScopeInterface::SCOPE_STORE
                );

                if (!$checked) {
                    unset($jsLayout['components']['checkout']['children']['additional']['children']['subscribe']['checked']);
                }
            }

            if (!$this->scopeConfig->isSetFlag(
                'amasty_checkout/additional_options/discount', ScopeInterface::SCOPE_STORE
            )) {
                unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['afterMethods']['children']['discount']);
            }

            if (!$this->scopeConfig->isSetFlag(
                'amasty_checkout/additional_options/comment', ScopeInterface::SCOPE_STORE
            )) {
                unset($jsLayout['components']['checkout']['children']['additional']['children']['comment']);
            }

            if (!$this->scopeConfig->isSetFlag(
                'amasty_checkout/gifts/gift_wrap', ScopeInterface::SCOPE_STORE
            )) {
                unset($jsLayout['components']['checkout']['children']['additional']['children']['gift_wrap']);
            } else {
                $amount = +$this->scopeConfig->getValue(
                    'amasty_checkout/gifts/gift_wrap_fee', ScopeInterface::SCOPE_STORE
                );

                $rate = $this->storeManager->getStore()->getBaseCurrency()->getRate(
                    $this->storeManager->getStore()->getCurrentCurrency()
                );

                $amount *= $rate;

                $formattedPrice = $this->priceCurrency->format($amount, false);

                $jsLayout['components']['checkout']['children']['additional']['children']['gift_wrap']['description']
                    = __('Gift wrap %1', $formattedPrice);

                $jsLayout['components']['checkout']['children']['additional']['children']['gift_wrap']['fee'] = $amount;

                $fee = $this->feeRepository->getByQuoteId($this->checkoutSession->getQuoteId());

                if ($fee->getId()) {
                    $jsLayout['components']['checkout']['children']['additional']['children']['gift_wrap']['checked'] = true;
                }
            }

            if (empty($messages = $this->giftMessages->getGiftMessages())) {
                unset($jsLayout['components']['checkout']['children']['additional']['children']['gift_message_container']);
            } else {
                $giftRoot = &$jsLayout['components']['checkout']['children']['additional']['children']
                ['gift_message_container']['children'];

                $giftRoot['item_messages'] = $giftRoot['quote_message'] = [
                    'component' => 'uiComponent',
                    'children' => [],
                ];

                /** @var \Magento\GiftMessage\Model\Message $message */
                foreach ($messages as $key => $message) {
                    if ($message->getId()) {
                        $jsLayout['components']['checkout']['children']['additional']['children']
                        ['gift_message_container']['children']['checkbox']['checked'] = true;
                    }

                    $node = $message
                        ->setData('item_id', $key)
                        ->toArray(['item_id', 'sender', 'recipient', 'message', 'title']);

                    $node['component'] = 'Amasty_Checkout/js/form/element/gift-messages/message';

                    $giftRoot[$key ? 'item_messages' : 'quote_message']['children'][] = $node;
                }
            }
            if (!$this->scopeConfig->isSetFlag(
                'amasty_checkout/delivery_date/enabled', ScopeInterface::SCOPE_STORE
            )) {
                unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['amcheckout-delivery-date']);
            } else {
                $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                ['amcheckout-delivery-date']['children']['fieldset']['children']['date']['amcheckout_days'] = $this->deliveryDate->getDeliveryDays();

                if ($this->scopeConfig->isSetFlag(
                    'amasty_checkout/delivery_date/date_required', ScopeInterface::SCOPE_STORE
                )) {
                    $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                    ['amcheckout-delivery-date']['children']['fieldset']['children']['date']['validation']['required-entry'] = 'true';
                }

                $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                ['amcheckout-delivery-date']['children']['fieldset']['children']['date']['required-entry'] = true;

                $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                ['amcheckout-delivery-date']['children']['fieldset']['children']['time']['options'] = $this->deliveryDate->getDeliveryHours();

                $delivery = $this->delivery->findByQuoteId($this->checkoutSession->getQuoteId());

                $jsLayout['components']['checkoutProvider']['amcheckoutDelivery'] = [
                    'date' => $delivery->getData('date'),
                    'time' => $delivery->getData('time'),
                ];

                if (!$this->scopeConfig->isSetFlag(
                    'amasty_checkout/delivery_date/delivery_comment_enable', ScopeInterface::SCOPE_STORE)
                ) {
                    unset(
                        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
                        ['children']['amcheckout-delivery-date']['children']['fieldset']['children']['comment']
                    );
                } else {
                    $comment = $this->scopeConfig->getValue(
                        'amasty_checkout/delivery_date/delivery_comment_default',
                        ScopeInterface::SCOPE_STORE
                    );
                    $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                    ['amcheckout-delivery-date']['children']['fieldset']['children']['comment']['placeholder']
                        = (string)$comment;
                }
            }

            $jsLayout['components']['checkoutProvider']['amdiscount']['isNeedToReloadShipping'] =
                (bool)$this->scopeConfig->isSetFlag(
                    'amasty_checkout/general/reload_shipping',
                    ScopeInterface::SCOPE_STORE
                );

            if (!$this->scopeConfig->isSetFlag('amasty_checkout/additional_options/create_account', ScopeInterface::SCOPE_STORE)
                || $this->checkoutSession->getQuote()->getCustomer()->getId() !== null
            ) {
                unset($jsLayout['components']['checkout']['children']['additional']['children']['register']);
            } else {
                if (!$this->scopeConfig->isSetFlag(
                    'amasty_checkout/additional_options/create_account_checked', ScopeInterface::SCOPE_STORE
                )
                ) {
                    unset($jsLayout['components']['checkout']['children']['additional']['children']['register']['checked']);
                }
            }

            if ($this->scopeConfig->getValue('amasty_checkout/general/allow_edit_options', ScopeInterface::SCOPE_STORE)) {
                $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']
                ['cart_items']['children']['details']['component']
                    = 'Amasty_Checkout/js/view/checkout/summary/item/details';

                $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']
                ['cart_items']['component']
                    = 'Amasty_Checkout/js/view/checkout/summary/cart-items';
            }

            $amastyBillingAddressComponent = 'Amasty_Checkout/js/view/billing-address';
            if (method_exists($this->checkoutHelper, 'isDisplayBillingOnPaymentMethodAvailable')
                && !$this->checkoutHelper->isDisplayBillingOnPaymentMethodAvailable()
            ) {
                $afterMethods = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                                ['payment']['children']['afterMethods'];
                $billingAddressForm = &$afterMethods['children']['billing-address-form'];
                $billingAddressForm['component'] = $amastyBillingAddressComponent;
                $amastyBillingAddressComponent = '';
            }

            foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                     ['payment']['children']['payments-list']['children'] as &$paymentMethod) {
                if ($paymentMethod['component'] == 'Magento_Checkout/js/view/billing-address') {
                    $paymentMethod['component'] = $amastyBillingAddressComponent;
                }
            }

            $jsLayout = $this->agreementsMoveToReviewBlock($jsLayout);
        }

        // move totals to end of summary block
        $summary = &$jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children'];

        $totalsSection = $summary['totals'];
        unset($summary['totals']);
        $summary['totals'] = $totalsSection;

        return $jsLayout;
    }

    /**
     * The method sets field  as required
     *
     * @param array $components
     *
     * @return array
     */
    private function setRequiredField($components = [])
    {
        $attributeConfig = $this->attributeMerger->getFieldConfig();
        foreach ($attributeConfig as $key => &$config) {
            if (isset($components[$key]) && !isset($components[$key]['skipValidation'])) {
                $components[$key]['skipValidation'] = !$config->isRequired();
                $components[$key]['validation']['required-entry'] = $config->isRequired();
            }
        }

        return $components;
    }

    /**
     * The method moves to review block
     *
     * @param array $jsLayout
     *
     * @return array
     */
    private function agreementsMoveToReviewBlock($jsLayout = [])
    {
        if (empty($jsLayout)) {
            return $jsLayout;
        }

        $paymentListComponent = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list'];
        if ($paymentListComponent) {
            $checkedAgreement = $this->checkoutConfig->isSetAgreements();
            $agreementsHasToMove = $this->checkoutConfig->getPlaceDisplayTermsAndConditions();

            if ($checkedAgreement && $agreementsHasToMove == Config::VALUE_ORDER_TOTALS) {
                $agreementComponentConfigs = $paymentListComponent['children']['before-place-order']['children']['agreements'];
                $agreementComponent = ['agreements' => $agreementComponentConfigs];
                $additionalChildren = $jsLayout['components']['checkout']['children']['additional']['children'];
                $additionalChildren = $this->utility->arrayInsertBeforeKey($additionalChildren, 'comment', $agreementComponent);
                $jsLayout['components']['checkout']['children']['additional']['children'] = $additionalChildren;
                unset($paymentListComponent['children']['before-place-order']['children']['agreements']);
            }
        }

        return $jsLayout;
    }
}
