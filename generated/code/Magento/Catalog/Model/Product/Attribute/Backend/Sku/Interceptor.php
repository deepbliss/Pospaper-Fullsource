<?php
namespace Magento\Catalog\Model\Product\Attribute\Backend\Sku;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Product\Attribute\Backend\Sku
 */
class Interceptor extends \Magento\Catalog\Model\Product\Attribute\Backend\Sku implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Stdlib\StringUtils $string)
    {
        $this->___init();
        parent::__construct($string);
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
