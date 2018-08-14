<?php
namespace Magento\Backend\Block\Store\Switcher;

/**
 * Interceptor class for @see \Magento\Backend\Block\Store\Switcher
 */
class Interceptor extends \Magento\Backend\Block\Store\Switcher implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Store\Model\WebsiteFactory $websiteFactory, \Magento\Store\Model\GroupFactory $storeGroupFactory, \Magento\Store\Model\StoreFactory $storeFactory, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $websiteFactory, $storeGroupFactory, $storeFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($route = '', $params = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUrl');
        if (!$pluginInfo) {
            return parent::getUrl($route, $params);
        } else {
            return $this->___callPlugins('getUrl', func_get_args(), $pluginInfo);
        }
    }
}
