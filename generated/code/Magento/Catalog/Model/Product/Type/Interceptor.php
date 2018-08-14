<?php
namespace Magento\Catalog\Model\Product\Type;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Product\Type
 */
class Interceptor extends \Magento\Catalog\Model\Product\Type implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Model\ProductTypes\ConfigInterface $config, \Magento\Catalog\Model\Product\Type\Pool $productTypePool, \Magento\Catalog\Model\Product\Type\Price\Factory $priceFactory, \Magento\Framework\Pricing\PriceInfo\Factory $priceInfoFactory)
    {
        $this->___init();
        parent::__construct($config, $productTypePool, $priceFactory, $priceInfoFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionArray()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOptionArray');
        if (!$pluginInfo) {
            return parent::getOptionArray();
        } else {
            return $this->___callPlugins('getOptionArray', func_get_args(), $pluginInfo);
        }
    }
}
