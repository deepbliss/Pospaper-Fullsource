<?php
namespace Magento\Catalog\Model\Product\Attribute\Backend\Stock;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Product\Attribute\Backend\Stock
 */
class Interceptor extends \Magento\Catalog\Model\Product\Attribute\Backend\Stock implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry)
    {
        $this->___init();
        parent::__construct($stockRegistry);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate($object);
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }
}
