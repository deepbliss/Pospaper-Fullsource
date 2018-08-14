<?php
namespace Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolver;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolver
 */
class Interceptor extends \Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolver implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
    }

    /**
     * {@inheritdoc}
     */
    public function isSalable(\Magento\Framework\Pricing\SaleableInterface $salableItem)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isSalable');
        if (!$pluginInfo) {
            return parent::isSalable($salableItem);
        } else {
            return $this->___callPlugins('isSalable', func_get_args(), $pluginInfo);
        }
    }
}
