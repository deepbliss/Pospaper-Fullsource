<?php
namespace Magento\Checkout\Helper\Data;

/**
 * Interceptor class for @see \Magento\Checkout\Helper\Data
 */
class Interceptor extends \Magento\Checkout\Helper\Data implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate, \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder, \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $checkoutSession, $localeDate, $transportBuilder, $inlineTranslation, $priceCurrency);
    }

    /**
     * {@inheritdoc}
     */
    public function getCheckout()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCheckout');
        if (!$pluginInfo) {
            return parent::getCheckout();
        } else {
            return $this->___callPlugins('getCheckout', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQuote()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQuote');
        if (!$pluginInfo) {
            return parent::getQuote();
        } else {
            return $this->___callPlugins('getQuote', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function formatPrice($price)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'formatPrice');
        if (!$pluginInfo) {
            return parent::formatPrice($price);
        } else {
            return $this->___callPlugins('formatPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertPrice($price, $format = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertPrice');
        if (!$pluginInfo) {
            return parent::convertPrice($price, $format);
        } else {
            return $this->___callPlugins('convertPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canOnepageCheckout()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canOnepageCheckout');
        if (!$pluginInfo) {
            return parent::canOnepageCheckout();
        } else {
            return $this->___callPlugins('canOnepageCheckout', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPriceInclTax($item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPriceInclTax');
        if (!$pluginInfo) {
            return parent::getPriceInclTax($item);
        } else {
            return $this->___callPlugins('getPriceInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubtotalInclTax($item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSubtotalInclTax');
        if (!$pluginInfo) {
            return parent::getSubtotalInclTax($item);
        } else {
            return $this->___callPlugins('getSubtotalInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBasePriceInclTax($item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBasePriceInclTax');
        if (!$pluginInfo) {
            return parent::getBasePriceInclTax($item);
        } else {
            return $this->___callPlugins('getBasePriceInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseSubtotalInclTax($item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseSubtotalInclTax');
        if (!$pluginInfo) {
            return parent::getBaseSubtotalInclTax($item);
        } else {
            return $this->___callPlugins('getBaseSubtotalInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendPaymentFailedEmail($checkout, $message, $checkoutType = 'onepage')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'sendPaymentFailedEmail');
        if (!$pluginInfo) {
            return parent::sendPaymentFailedEmail($checkout, $message, $checkoutType);
        } else {
            return $this->___callPlugins('sendPaymentFailedEmail', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowedGuestCheckout(\Magento\Quote\Model\Quote $quote, $store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isAllowedGuestCheckout');
        if (!$pluginInfo) {
            return parent::isAllowedGuestCheckout($quote, $store);
        } else {
            return $this->___callPlugins('isAllowedGuestCheckout', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isContextCheckout()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isContextCheckout');
        if (!$pluginInfo) {
            return parent::isContextCheckout();
        } else {
            return $this->___callPlugins('isContextCheckout', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isCustomerMustBeLogged()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isCustomerMustBeLogged');
        if (!$pluginInfo) {
            return parent::isCustomerMustBeLogged();
        } else {
            return $this->___callPlugins('isCustomerMustBeLogged', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isDisplayBillingOnPaymentMethodAvailable()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isDisplayBillingOnPaymentMethodAvailable');
        if (!$pluginInfo) {
            return parent::isDisplayBillingOnPaymentMethodAvailable();
        } else {
            return $this->___callPlugins('isDisplayBillingOnPaymentMethodAvailable', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isModuleOutputEnabled($moduleName = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isModuleOutputEnabled');
        if (!$pluginInfo) {
            return parent::isModuleOutputEnabled($moduleName);
        } else {
            return $this->___callPlugins('isModuleOutputEnabled', func_get_args(), $pluginInfo);
        }
    }
}
