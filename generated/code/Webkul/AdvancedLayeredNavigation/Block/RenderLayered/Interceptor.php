<?php
namespace Webkul\AdvancedLayeredNavigation\Block\RenderLayered;

/**
 * Interceptor class for @see \Webkul\AdvancedLayeredNavigation\Block\RenderLayered
 */
class Interceptor extends \Webkul\AdvancedLayeredNavigation\Block\RenderLayered implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Eav\Model\Entity\Attribute $eavAttribute, \Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory $layerAttribute, \Magento\Swatches\Helper\Data $swatchHelper, \Magento\Swatches\Helper\Media $mediaHelper, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $eavAttribute, $layerAttribute, $swatchHelper, $mediaHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getSwatchData()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSwatchData');
        if (!$pluginInfo) {
            return parent::getSwatchData();
        } else {
            return $this->___callPlugins('getSwatchData', func_get_args(), $pluginInfo);
        }
    }
}
