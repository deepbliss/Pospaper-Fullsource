<?php
namespace Magento\Catalog\Controller\Adminhtml\Product\Action\Attribute\Save;

/**
 * Interceptor class for @see \Magento\Catalog\Controller\Adminhtml\Product\Action\Attribute\Save
 */
class Interceptor extends \Magento\Catalog\Controller\Adminhtml\Product\Action\Attribute\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Catalog\Helper\Product\Edit\Action\Attribute $attributeHelper, \Magento\Catalog\Model\Indexer\Product\Flat\Processor $productFlatIndexerProcessor, \Magento\Catalog\Model\Indexer\Product\Price\Processor $productPriceIndexerProcessor, \Magento\CatalogInventory\Model\Indexer\Stock\Processor $stockIndexerProcessor, \Magento\Catalog\Helper\Product $catalogProduct, \Magento\CatalogInventory\Api\Data\StockItemInterfaceFactory $stockItemFactory, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper)
    {
        $this->___init();
        parent::__construct($context, $attributeHelper, $productFlatIndexerProcessor, $productPriceIndexerProcessor, $stockIndexerProcessor, $catalogProduct, $stockItemFactory, $dataObjectHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
