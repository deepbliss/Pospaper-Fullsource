<?php
namespace Magento\Payment\Helper\Data;

/**
 * Interceptor class for @see \Magento\Payment\Helper\Data
 */
class Interceptor extends \Magento\Payment\Helper\Data implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Payment\Model\Method\Factory $paymentMethodFactory, \Magento\Store\Model\App\Emulation $appEmulation, \Magento\Payment\Model\Config $paymentConfig, \Magento\Framework\App\Config\Initial $initialConfig)
    {
        $this->___init();
        parent::__construct($context, $layoutFactory, $paymentMethodFactory, $appEmulation, $paymentConfig, $initialConfig);
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
