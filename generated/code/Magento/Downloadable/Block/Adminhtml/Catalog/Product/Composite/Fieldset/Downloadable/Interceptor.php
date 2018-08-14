<?php
namespace Magento\Downloadable\Block\Adminhtml\Catalog\Product\Composite\Fieldset\Downloadable;

/**
 * Interceptor class for @see \Magento\Downloadable\Block\Adminhtml\Catalog\Product\Composite\Fieldset\Downloadable
 */
class Interceptor extends \Magento\Downloadable\Block\Adminhtml\Catalog\Product\Composite\Fieldset\Downloadable implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Pricing\Helper\Data $pricingHelper, \Magento\Framework\Json\EncoderInterface $encoder, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $pricingHelper, $encoder, $data);
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
