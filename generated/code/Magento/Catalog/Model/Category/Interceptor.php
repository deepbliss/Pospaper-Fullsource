<?php
namespace Magento\Catalog\Model\Category;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Category
 */
class Interceptor extends \Magento\Catalog\Model\Category implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Api\CategoryAttributeRepositoryInterface $metadataService, \Magento\Catalog\Model\ResourceModel\Category\Tree $categoryTreeResource, \Magento\Catalog\Model\ResourceModel\Category\TreeFactory $categoryTreeFactory, \Magento\Store\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory, \Magento\Framework\UrlInterface $url, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \Magento\Catalog\Model\Config $catalogConfig, \Magento\Framework\Filter\FilterManager $filter, \Magento\Catalog\Model\Indexer\Category\Flat\State $flatState, \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator $categoryUrlPathGenerator, \Magento\UrlRewrite\Model\UrlFinderInterface $urlFinder, \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry, \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $storeManager, $metadataService, $categoryTreeResource, $categoryTreeFactory, $storeCollectionFactory, $url, $productCollectionFactory, $catalogConfig, $filter, $flatState, $categoryUrlPathGenerator, $urlFinder, $indexerRegistry, $categoryRepository, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save();
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete();
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }
}
