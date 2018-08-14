<?php
namespace Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Websites;

/**
 * Interceptor class for @see \Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Websites
 */
class Interceptor extends \Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Websites implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Store\Model\WebsiteFactory $websiteFactory, \Magento\Store\Model\GroupFactory $storeGroupFactory, \Magento\Store\Model\StoreFactory $storeFactory, \Magento\Framework\Registry $coreRegistry, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $websiteFactory, $storeGroupFactory, $storeFactory, $coreRegistry, $data);
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
