<?php
namespace Magento\Catalog\Model\ResourceModel\Config;

/**
 * Interceptor class for @see \Magento\Catalog\Model\ResourceModel\Config
 */
class Interceptor extends \Magento\Catalog\Model\ResourceModel\Config implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Eav\Model\Config $eavConfig, $connectionName = null)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $eavConfig, $connectionName);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributesUsedInListing()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttributesUsedInListing');
        if (!$pluginInfo) {
            return parent::getAttributesUsedInListing();
        } else {
            return $this->___callPlugins('getAttributesUsedInListing', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributesUsedForSortBy()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttributesUsedForSortBy');
        if (!$pluginInfo) {
            return parent::getAttributesUsedForSortBy();
        } else {
            return $this->___callPlugins('getAttributesUsedForSortBy', func_get_args(), $pluginInfo);
        }
    }
}
