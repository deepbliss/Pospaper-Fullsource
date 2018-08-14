<?php
namespace Magento\Catalog\Model\Product\Attribute\Backend\Category;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Product\Attribute\Backend\Category
 */
class Interceptor extends \Magento\Catalog\Model\Product\Attribute\Backend\Category implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
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
