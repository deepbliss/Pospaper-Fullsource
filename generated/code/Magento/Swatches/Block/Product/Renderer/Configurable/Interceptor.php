<?php
namespace Magento\Swatches\Block\Product\Renderer\Configurable;

/**
 * Interceptor class for @see \Magento\Swatches\Block\Product\Renderer\Configurable
 */
class Interceptor extends \Magento\Swatches\Block\Product\Renderer\Configurable implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Stdlib\ArrayUtils $arrayUtils, \Magento\Framework\Json\EncoderInterface $jsonEncoder, \Magento\ConfigurableProduct\Helper\Data $helper, \Magento\Catalog\Helper\Product $catalogProduct, \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, \Magento\ConfigurableProduct\Model\ConfigurableAttributeData $configurableAttributeData, \Magento\Swatches\Helper\Data $swatchHelper, \Magento\Swatches\Helper\Media $swatchMediaHelper, array $data = array(), \Magento\Swatches\Model\SwatchAttributesProvider $swatchAttributesProvider = null)
    {
        $this->___init();
        parent::__construct($context, $arrayUtils, $jsonEncoder, $helper, $catalogProduct, $currentCustomer, $priceCurrency, $configurableAttributeData, $swatchHelper, $swatchMediaHelper, $data, $swatchAttributesProvider);
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirectToCartEnabled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isRedirectToCartEnabled');
        if (!$pluginInfo) {
            return parent::isRedirectToCartEnabled();
        } else {
            return $this->___callPlugins('isRedirectToCartEnabled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        if (!$pluginInfo) {
            return parent::getImage($product, $imageId, $attributes);
        } else {
            return $this->___callPlugins('getImage', func_get_args(), $pluginInfo);
        }
    }
}
