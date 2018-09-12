<?php
namespace Klarna\Core\Helper\KlarnaConfig;

/**
 * Interceptor class for @see \Klarna\Core\Helper\KlarnaConfig
 */
class Interceptor extends \Klarna\Core\Helper\KlarnaConfig implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Framework\Config\DataInterface $config, \Klarna\Core\Api\VersionInterfaceFactory $versionFactory, \Magento\Framework\DataObjectFactory $dataObjectFactory, $eventPrefix = 'kp')
    {
        $this->___init();
        parent::__construct($context, $config, $versionFactory, $dataObjectFactory, $eventPrefix);
    }

    /**
     * {@inheritdoc}
     */
    public function getCheckoutType($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCheckoutType');
        if (!$pluginInfo) {
            return parent::getCheckoutType($store);
        } else {
            return $this->___callPlugins('getCheckoutType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getVersionConfig($store)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getVersionConfig');
        if (!$pluginInfo) {
            return parent::getVersionConfig($store);
        } else {
            return $this->___callPlugins('getVersionConfig', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCheckoutVersionDetails($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCheckoutVersionDetails');
        if (!$pluginInfo) {
            return parent::getCheckoutVersionDetails($code);
        } else {
            return $this->___callPlugins('getCheckoutVersionDetails', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getApiTypeConfig($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getApiTypeConfig');
        if (!$pluginInfo) {
            return parent::getApiTypeConfig($code);
        } else {
            return $this->___callPlugins('getApiTypeConfig', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderlines($checkoutType)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOrderlines');
        if (!$pluginInfo) {
            return parent::getOrderlines($checkoutType);
        } else {
            return $this->___callPlugins('getOrderlines', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMerchantCheckboxMethodConfig($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMerchantCheckboxMethodConfig');
        if (!$pluginInfo) {
            return parent::getMerchantCheckboxMethodConfig($code);
        } else {
            return $this->___callPlugins('getMerchantCheckboxMethodConfig', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isShippingCallbackSupport($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isShippingCallbackSupport');
        if (!$pluginInfo) {
            return parent::isShippingCallbackSupport($store);
        } else {
            return $this->___callPlugins('isShippingCallbackSupport', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isMerchantCheckboxSupport($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isMerchantCheckboxSupport');
        if (!$pluginInfo) {
            return parent::isMerchantCheckboxSupport($store);
        } else {
            return $this->___callPlugins('isMerchantCheckboxSupport', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isDateOfBirthMandatorySupport($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isDateOfBirthMandatorySupport');
        if (!$pluginInfo) {
            return parent::isDateOfBirthMandatorySupport($store);
        } else {
            return $this->___callPlugins('isDateOfBirthMandatorySupport', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isPhoneMandatorySupport($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isPhoneMandatorySupport');
        if (!$pluginInfo) {
            return parent::isPhoneMandatorySupport($store);
        } else {
            return $this->___callPlugins('isPhoneMandatorySupport', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderMangagementClass($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOrderMangagementClass');
        if (!$pluginInfo) {
            return parent::getOrderMangagementClass($store);
        } else {
            return $this->___callPlugins('getOrderMangagementClass', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isTitleMandatorySupport($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isTitleMandatorySupport');
        if (!$pluginInfo) {
            return parent::isTitleMandatorySupport($store);
        } else {
            return $this->___callPlugins('isTitleMandatorySupport', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isDelayedPushNotification($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isDelayedPushNotification');
        if (!$pluginInfo) {
            return parent::isDelayedPushNotification($store);
        } else {
            return $this->___callPlugins('isDelayedPushNotification', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isPartialPaymentSupport($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isPartialPaymentSupport');
        if (!$pluginInfo) {
            return parent::isPartialPaymentSupport($store);
        } else {
            return $this->___callPlugins('isPartialPaymentSupport', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOmBuilderType(\Klarna\Core\Api\VersionInterface $versionConfig, $methodCode = 'klarna_kp')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOmBuilderType');
        if (!$pluginInfo) {
            return parent::getOmBuilderType($versionConfig, $methodCode);
        } else {
            return $this->___callPlugins('getOmBuilderType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isSeparateTaxLine($store)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isSeparateTaxLine');
        if (!$pluginInfo) {
            return parent::isSeparateTaxLine($store);
        } else {
            return $this->___callPlugins('isSeparateTaxLine', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isShippingInIframe($store)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isShippingInIframe');
        if (!$pluginInfo) {
            return parent::isShippingInIframe($store);
        } else {
            return $this->___callPlugins('isShippingInIframe', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalPaymentOptions($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getExternalPaymentOptions');
        if (!$pluginInfo) {
            return parent::getExternalPaymentOptions($code);
        } else {
            return $this->___callPlugins('getExternalPaymentOptions', func_get_args(), $pluginInfo);
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
