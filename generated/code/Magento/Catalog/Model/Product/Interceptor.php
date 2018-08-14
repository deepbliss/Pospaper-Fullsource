<?php
namespace Magento\Catalog\Model\Product;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Product
 */
class Interceptor extends \Magento\Catalog\Model\Product implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Api\ProductAttributeRepositoryInterface $metadataService, \Magento\Catalog\Model\Product\Url $url, \Magento\Catalog\Model\Product\Link $productLink, \Magento\Catalog\Model\Product\Configuration\Item\OptionFactory $itemOptionFactory, \Magento\CatalogInventory\Api\Data\StockItemInterfaceFactory $stockItemFactory, \Magento\Catalog\Model\Product\OptionFactory $catalogProductOptionFactory, \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility, \Magento\Catalog\Model\Product\Attribute\Source\Status $catalogProductStatus, \Magento\Catalog\Model\Product\Media\Config $catalogProductMediaConfig, \Magento\Catalog\Model\Product\Type $catalogProductType, \Magento\Framework\Module\Manager $moduleManager, \Magento\Catalog\Helper\Product $catalogProduct, \Magento\Catalog\Model\ResourceModel\Product $resource, \Magento\Catalog\Model\ResourceModel\Product\Collection $resourceCollection, \Magento\Framework\Data\CollectionFactory $collectionFactory, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry, \Magento\Catalog\Model\Indexer\Product\Flat\Processor $productFlatIndexerProcessor, \Magento\Catalog\Model\Indexer\Product\Price\Processor $productPriceIndexerProcessor, \Magento\Catalog\Model\Indexer\Product\Eav\Processor $productEavIndexerProcessor, \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository, \Magento\Catalog\Model\Product\Image\CacheFactory $imageCacheFactory, \Magento\Catalog\Model\ProductLink\CollectionProvider $entityCollectionProvider, \Magento\Catalog\Model\Product\LinkTypeProvider $linkTypeProvider, \Magento\Catalog\Api\Data\ProductLinkInterfaceFactory $productLinkFactory, \Magento\Catalog\Api\Data\ProductLinkExtensionFactory $productLinkExtensionFactory, \Magento\Catalog\Model\Product\Attribute\Backend\Media\EntryConverterPool $mediaGalleryEntryConverterPool, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $joinProcessor, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $storeManager, $metadataService, $url, $productLink, $itemOptionFactory, $stockItemFactory, $catalogProductOptionFactory, $catalogProductVisibility, $catalogProductStatus, $catalogProductMediaConfig, $catalogProductType, $moduleManager, $catalogProduct, $resource, $resourceCollection, $collectionFactory, $filesystem, $indexerRegistry, $productFlatIndexerProcessor, $productPriceIndexerProcessor, $productEavIndexerProcessor, $categoryRepository, $imageCacheFactory, $entityCollectionProvider, $linkTypeProvider, $productLinkFactory, $productLinkExtensionFactory, $mediaGalleryEntryConverterPool, $dataObjectHelper, $joinProcessor, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function reindex()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'reindex');
        if (!$pluginInfo) {
            return parent::reindex();
        } else {
            return $this->___callPlugins('reindex', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaAttributes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMediaAttributes');
        if (!$pluginInfo) {
            return parent::getMediaAttributes();
        } else {
            return $this->___callPlugins('getMediaAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIdentities');
        if (!$pluginInfo) {
            return parent::getIdentities();
        } else {
            return $this->___callPlugins('getIdentities', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load($modelId, $field = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'load');
        if (!$pluginInfo) {
            return parent::load($modelId, $field);
        } else {
            return $this->___callPlugins('load', func_get_args(), $pluginInfo);
        }
    }
}
