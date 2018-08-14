<?php
namespace Magento\Checkout\Block\Checkout\LayoutProcessor;

/**
 * Interceptor class for @see \Magento\Checkout\Block\Checkout\LayoutProcessor
 */
class Interceptor extends \Magento\Checkout\Block\Checkout\LayoutProcessor implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider, \Magento\Ui\Component\Form\AttributeMapper $attributeMapper, \Magento\Checkout\Block\Checkout\AttributeMerger $merger)
    {
        $this->___init();
        parent::__construct($attributeMetadataDataProvider, $attributeMapper, $merger);
    }

    /**
     * {@inheritdoc}
     */
    public function process($jsLayout)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'process');
        if (!$pluginInfo) {
            return parent::process($jsLayout);
        } else {
            return $this->___callPlugins('process', func_get_args(), $pluginInfo);
        }
    }
}
