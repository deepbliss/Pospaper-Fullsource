<?php

namespace Adroll\Pixel\Block;

use \Adroll\Pixel\Helper\Config as ConfigHelper;
use \Adroll\Pixel\Helper\Payload as PayloadHelper;
use \Magento\Framework\Locale\Resolver;
use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;

class Pixel extends Template
{
    public function __construct(
        Context $context,
        Resolver $localeResolver,
        ConfigHelper $configHelper,
        PayloadHelper $payloadHelper)
    {
        parent::__construct($context);
        $this->_request = $context->getRequest();
        $this->_storeManager = $context->getStoreManager();
        $this->_localeResolver = $localeResolver;
        $this->_configHelper = $configHelper;
        $this->_payloadHelper = $payloadHelper;
    }

    public function isConfigured()
    {
        return $this->getAdvertisableEid() !== null && $this->getPixelEid() !== null;
    }

    public function getAdvertisableEid()
    {
        $groupId = $this->_storeManager->getStore()->getGroup()->getId();
        return $this->_configHelper->getAdvertisableEid($groupId);
    }

    public function getPixelEid()
    {
        $groupId = $this->_storeManager->getStore()->getGroup()->getId();
        return $this->_configHelper->getPixelEid($groupId);
    }

    private function makeEvent($name, $payload = null)
    {
        return array('name' => $name, 'payload' => $payload);
    }

    private function makePageViewEvent($segmentName = null, $extraPayload = null)
    {
        $event = $this->makeEvent('pageView');
        $payload = array();
        if ($segmentName) {
            $payload['segment_name'] = $segmentName;
        }
        if ($extraPayload) {
            $payload = array_merge($payload, $extraPayload);
        }
        if (!empty($payload)) {
            $event['payload'] = $payload;
        }
        return $event;
    }

    public function getGlobalVars()
    {
        $globalVars = array();
        $globalVars['adroll_adv_id'] = $this->getAdvertisableEid();
        $globalVars['adroll_pix_id'] = $this->getPixelEid();
        $globalVars['adroll_version'] = '2.0';
        return $globalVars;
    }

    public function getCustomerEmail() {
        return $this->_payloadHelper->getCustomerEmail();
    }

    public function getPixelProperties()
    {
        return array(
            'currency' => $this->_storeManager->getStore()->getCurrentCurrencyCode(),
            'language' => $this->_localeResolver->getLocale()
        );
    }

    public function getEvents()
    {
        $events = array();
        $action = $this->_request->getFullActionName();

        switch ($action) {
            case 'catalog_product_view':
                $events[] = $this->makePageViewEvent('magento_viewed_product');
                $events[] = $this->makeEvent('productView', $this->_payloadHelper->getProductViewPayload());
                break;
            case 'checkout_cart_index':
                $events[] = $this->makePageViewEvent('magento_viewed_cart');
                $events[] = $this->makeEvent('cartView', $this->_payloadHelper->getCartViewPayload());
                break;
//            TODO: investigate if there are any de facto checkout plugins that we must account for
//            case 'onestepcheckout_index_index':
//            case 'iwd_opc_index_index':
//                # If the store has a onestep checkout plugin, fire viewed_checkout and order_review segments together.
//                # Currently supported onestep-checkout extensions:
//                # https://marketplace.magento.com/onestepcheckout-idev-onestepcheckout.html
//                # https://www.iwdagency.com/extensions/checkout-suite-m1.html
//                $events[] = $this->makePageViewEvent('magento_order_reviewed');
//                $events[] = $this->makePageViewEvent('magento_viewed_checkout');
//                $events[] = $this->makeEvent('checkoutStart', $this->_payloadHelper->getCheckoutStartPayload());
//                break;
            case 'checkout_index_index':
                $events[] = $this->makePageViewEvent('magento_viewed_checkout');
                $events[] = $this->makeEvent('checkoutStart', $this->_payloadHelper->getCheckoutStartPayload());
                break;
            case 'checkout_onepage_success':
                $purchasePayload = $this->_payloadHelper->getPurchasePayload();
                $events[] = $this->makePageViewEvent('magento_order_received', $purchasePayload);
                $events[] = $this->makeEvent('purchase', $purchasePayload);
                break;
            case 'cms_index_index':
                $events[] = $this->makePageViewEvent();
                $events[] = $this->makeEvent('homeView');
                break;
            case 'catalogsearch_result_index':
                $events[] = $this->makePageViewEvent();
                $events[] = $this->makeEvent('search', $this->_payloadHelper->getSimpleSearchPayload());
                break;
            default:
                $events[] = $this->makePageViewEvent();
                break;
        }
        return $events;
    }
}
