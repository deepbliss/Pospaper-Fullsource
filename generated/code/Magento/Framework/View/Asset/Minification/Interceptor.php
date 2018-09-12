<?php
namespace Magento\Framework\View\Asset\Minification;

/**
 * Interceptor class for @see \Magento\Framework\View\Asset\Minification
 */
class Interceptor extends \Magento\Framework\View\Asset\Minification implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\App\State $appState, $scope = 'store')
    {
        $this->___init();
        parent::__construct($scopeConfig, $appState, $scope);
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled($contentType)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isEnabled');
        if (!$pluginInfo) {
            return parent::isEnabled($contentType);
        } else {
            return $this->___callPlugins('isEnabled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addMinifiedSign($filename)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addMinifiedSign');
        if (!$pluginInfo) {
            return parent::addMinifiedSign($filename);
        } else {
            return $this->___callPlugins('addMinifiedSign', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeMinifiedSign($filename)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeMinifiedSign');
        if (!$pluginInfo) {
            return parent::removeMinifiedSign($filename);
        } else {
            return $this->___callPlugins('removeMinifiedSign', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isMinifiedFilename($filename)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isMinifiedFilename');
        if (!$pluginInfo) {
            return parent::isMinifiedFilename($filename);
        } else {
            return $this->___callPlugins('isMinifiedFilename', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isExcluded($filename)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isExcluded');
        if (!$pluginInfo) {
            return parent::isExcluded($filename);
        } else {
            return $this->___callPlugins('isExcluded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExcludes($contentType)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getExcludes');
        if (!$pluginInfo) {
            return parent::getExcludes($contentType);
        } else {
            return $this->___callPlugins('getExcludes', func_get_args(), $pluginInfo);
        }
    }
}
