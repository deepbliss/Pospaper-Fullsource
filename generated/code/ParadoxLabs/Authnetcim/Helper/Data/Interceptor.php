<?php
namespace ParadoxLabs\Authnetcim\Helper\Data;

/**
 * Interceptor class for @see \ParadoxLabs\Authnetcim\Helper\Data
 */
class Interceptor extends \ParadoxLabs\Authnetcim\Helper\Data implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Payment\Model\Method\Factory $paymentMethodFactory, \Magento\Store\Model\App\Emulation $appEmulation, \Magento\Payment\Model\Config $paymentConfig, \Magento\Framework\App\Config\Initial $initialConfig, \Magento\Framework\App\State $appState, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Registry $registry, \Magento\Store\Model\WebsiteFactory $websiteFactory, \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Quote\Model\Quote\PaymentFactory $paymentFactory, \Magento\Backend\Model\Session\Quote $backendSession, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Customer\Model\Session $customerSession, \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomerSession, \ParadoxLabs\TokenBase\Model\CardFactory $cardFactory, \ParadoxLabs\TokenBase\Model\ResourceModel\Card\CollectionFactory $cardCollectionFactory, \ParadoxLabs\TokenBase\Helper\Address $addressHelper, \ParadoxLabs\TokenBase\Helper\Operation $operationHelper)
    {
        $this->___init();
        parent::__construct($context, $layoutFactory, $paymentMethodFactory, $appEmulation, $paymentConfig, $initialConfig, $appState, $storeManager, $registry, $websiteFactory, $customerFactory, $customerRepository, $paymentFactory, $backendSession, $checkoutSession, $customerSession, $currentCustomerSession, $cardFactory, $cardCollectionFactory, $addressHelper, $operationHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function translateAvs($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'translateAvs');
        if (!$pluginInfo) {
            return parent::translateAvs($code);
        } else {
            return $this->___callPlugins('translateAvs', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function translateCcv($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'translateCcv');
        if (!$pluginInfo) {
            return parent::translateCcv($code);
        } else {
            return $this->___callPlugins('translateCcv', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function translateCavv($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'translateCavv');
        if (!$pluginInfo) {
            return parent::translateCavv($code);
        } else {
            return $this->___callPlugins('translateCavv', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function mapCcTypeToMagento($type)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'mapCcTypeToMagento');
        if (!$pluginInfo) {
            return parent::mapCcTypeToMagento($type);
        } else {
            return $this->___callPlugins('mapCcTypeToMagento', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveMethods()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActiveMethods');
        if (!$pluginInfo) {
            return parent::getActiveMethods();
        } else {
            return $this->___callPlugins('getActiveMethods', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllMethods()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllMethods');
        if (!$pluginInfo) {
            return parent::getAllMethods();
        } else {
            return $this->___callPlugins('getAllMethods', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentStoreId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCurrentStoreId');
        if (!$pluginInfo) {
            return parent::getCurrentStoreId();
        } else {
            return $this->___callPlugins('getCurrentStoreId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentCustomer()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCurrentCustomer');
        if (!$pluginInfo) {
            return parent::getCurrentCustomer();
        } else {
            return $this->___callPlugins('getCurrentCustomer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveCard($method = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActiveCard');
        if (!$pluginInfo) {
            return parent::getActiveCard($method);
        } else {
            return $this->___callPlugins('getActiveCard', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveCustomerCardsByMethod($method = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActiveCustomerCardsByMethod');
        if (!$pluginInfo) {
            return parent::getActiveCustomerCardsByMethod($method);
        } else {
            return $this->___callPlugins('getActiveCustomerCardsByMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIsFrontend()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIsFrontend');
        if (!$pluginInfo) {
            return parent::getIsFrontend();
        } else {
            return $this->___callPlugins('getIsFrontend', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIsAccount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIsAccount');
        if (!$pluginInfo) {
            return parent::getIsAccount();
        } else {
            return $this->___callPlugins('getIsAccount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function translateCardType($type)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'translateCardType');
        if (!$pluginInfo) {
            return parent::translateCardType($type);
        } else {
            return $this->___callPlugins('translateCardType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAchAccountTypes($code = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAchAccountTypes');
        if (!$pluginInfo) {
            return parent::getAchAccountTypes($code);
        } else {
            return $this->___callPlugins('getAchAccountTypes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function cleanupArray(&$array)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'cleanupArray');
        if (!$pluginInfo) {
            return parent::cleanupArray($array);
        } else {
            return $this->___callPlugins('cleanupArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getArrayValue($data, $path, $default = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getArrayValue');
        if (!$pluginInfo) {
            return parent::getArrayValue($data, $path, $default);
        } else {
            return $this->___callPlugins('getArrayValue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function log($code, $message, $debug = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'log');
        if (!$pluginInfo) {
            return parent::log($code, $message, $debug);
        } else {
            return $this->___callPlugins('log', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodInstance($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMethodInstance');
        if (!$pluginInfo) {
            return parent::getMethodInstance($code);
        } else {
            return $this->___callPlugins('getMethodInstance', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreMethods($store = null, $quote = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoreMethods');
        if (!$pluginInfo) {
            return parent::getStoreMethods($store, $quote);
        } else {
            return $this->___callPlugins('getStoreMethods', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodFormBlock(\Magento\Payment\Model\MethodInterface $method, \Magento\Framework\View\LayoutInterface $layout)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMethodFormBlock');
        if (!$pluginInfo) {
            return parent::getMethodFormBlock($method, $layout);
        } else {
            return $this->___callPlugins('getMethodFormBlock', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getInfoBlock(\Magento\Payment\Model\InfoInterface $info, \Magento\Framework\View\LayoutInterface $layout = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getInfoBlock');
        if (!$pluginInfo) {
            return parent::getInfoBlock($info, $layout);
        } else {
            return $this->___callPlugins('getInfoBlock', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getInfoBlockHtml(\Magento\Payment\Model\InfoInterface $info, $storeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getInfoBlockHtml');
        if (!$pluginInfo) {
            return parent::getInfoBlockHtml($info, $storeId);
        } else {
            return $this->___callPlugins('getInfoBlockHtml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentMethods()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPaymentMethods');
        if (!$pluginInfo) {
            return parent::getPaymentMethods();
        } else {
            return $this->___callPlugins('getPaymentMethods', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentMethodList($sorted = true, $asLabelValue = false, $withGroups = false, $store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPaymentMethodList');
        if (!$pluginInfo) {
            return parent::getPaymentMethodList($sorted, $asLabelValue, $withGroups, $store);
        } else {
            return $this->___callPlugins('getPaymentMethodList', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isZeroSubTotal($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isZeroSubTotal');
        if (!$pluginInfo) {
            return parent::isZeroSubTotal($store);
        } else {
            return $this->___callPlugins('isZeroSubTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getZeroSubTotalOrderStatus($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getZeroSubTotalOrderStatus');
        if (!$pluginInfo) {
            return parent::getZeroSubTotalOrderStatus($store);
        } else {
            return $this->___callPlugins('getZeroSubTotalOrderStatus', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getZeroSubTotalPaymentAutomaticInvoice($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getZeroSubTotalPaymentAutomaticInvoice');
        if (!$pluginInfo) {
            return parent::getZeroSubTotalPaymentAutomaticInvoice($store);
        } else {
            return $this->___callPlugins('getZeroSubTotalPaymentAutomaticInvoice', func_get_args(), $pluginInfo);
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
