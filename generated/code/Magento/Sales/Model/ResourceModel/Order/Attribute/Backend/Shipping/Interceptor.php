<?php
namespace Magento\Sales\Model\ResourceModel\Order\Attribute\Backend\Shipping;

/**
 * Interceptor class for @see \Magento\Sales\Model\ResourceModel\Order\Attribute\Backend\Shipping
 */
class Interceptor extends \Magento\Sales\Model\ResourceModel\Order\Attribute\Backend\Shipping implements \Magento\Framework\Interception\InterceptorInterface
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
