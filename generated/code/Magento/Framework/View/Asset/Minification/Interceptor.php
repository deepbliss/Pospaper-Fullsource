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
