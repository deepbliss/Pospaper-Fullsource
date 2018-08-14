<?php
namespace Magento\Catalog\Model\Layer\Search\FilterableAttributeList;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Layer\Search\FilterableAttributeList
 */
class Interceptor extends \Magento\Catalog\Model\Layer\Search\FilterableAttributeList implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory, \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->___init();
        parent::__construct($collectionFactory, $storeManager);
    }

    /**
     * {@inheritdoc}
     */
    public function getList()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getList');
        if (!$pluginInfo) {
            return parent::getList();
        } else {
            return $this->___callPlugins('getList', func_get_args(), $pluginInfo);
        }
    }
}
