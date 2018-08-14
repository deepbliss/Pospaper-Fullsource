<?php
namespace Magento\Catalog\Helper\Product\Configuration;

/**
 * Interceptor class for @see \Magento\Catalog\Helper\Product\Configuration
 */
class Interceptor extends \Magento\Catalog\Helper\Product\Configuration implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory, \Magento\Framework\Filter\FilterManager $filter, \Magento\Framework\Stdlib\StringUtils $string, \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($context, $productOptionFactory, $filter, $string, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(\Magento\Catalog\Model\Product\Configuration\Item\ItemInterface $item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOptions');
        if (!$pluginInfo) {
            return parent::getOptions($item);
        } else {
            return $this->___callPlugins('getOptions', func_get_args(), $pluginInfo);
        }
    }
}
