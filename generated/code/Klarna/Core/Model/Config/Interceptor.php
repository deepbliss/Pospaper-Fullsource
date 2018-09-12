<?php
namespace Klarna\Core\Model\Config;

/**
 * Interceptor class for @see \Klarna\Core\Model\Config
 */
class Interceptor extends \Klarna\Core\Model\Config implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->___init();
        parent::__construct($scopeConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function storeAddressSet($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'storeAddressSet');
        if (!$pluginInfo) {
            return parent::storeAddressSet($store);
        } else {
            return $this->___callPlugins('storeAddressSet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function debugModeWhileLive($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'debugModeWhileLive');
        if (!$pluginInfo) {
            return parent::debugModeWhileLive($store);
        } else {
            return $this->___callPlugins('debugModeWhileLive', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function testMode($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'testMode');
        if (!$pluginInfo) {
            return parent::testMode($store);
        } else {
            return $this->___callPlugins('testMode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function debugMode($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'debugMode');
        if (!$pluginInfo) {
            return parent::debugMode($store);
        } else {
            return $this->___callPlugins('debugMode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function requiredRegions()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'requiredRegions');
        if (!$pluginInfo) {
            return parent::requiredRegions();
        } else {
            return $this->___callPlugins('requiredRegions', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function klarnaEnabled($store = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'klarnaEnabled');
        if (!$pluginInfo) {
            return parent::klarnaEnabled($store);
        } else {
            return $this->___callPlugins('klarnaEnabled', func_get_args(), $pluginInfo);
        }
    }
}
