<?php
namespace Magento\Catalog\Pricing\Render\PriceBox;

/**
 * Interceptor class for @see \Magento\Catalog\Pricing\Render\PriceBox
 */
class Interceptor extends \Magento\Catalog\Pricing\Render\PriceBox implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Catalog\Model\Product $saleableItem, \Magento\Framework\Pricing\Price\PriceInterface $price, \Magento\Framework\Pricing\Render\RendererPool $rendererPool, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Framework\Math\Random $mathRandom, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $saleableItem, $price, $rendererPool, $jsonHelper, $mathRandom, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKey()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCacheKey');
        if (!$pluginInfo) {
            return parent::getCacheKey();
        } else {
            return $this->___callPlugins('getCacheKey', func_get_args(), $pluginInfo);
        }
    }
}
