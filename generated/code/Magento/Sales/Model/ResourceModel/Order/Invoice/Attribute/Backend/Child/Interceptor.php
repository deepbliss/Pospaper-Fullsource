<?php
namespace Magento\Sales\Model\ResourceModel\Order\Invoice\Attribute\Backend\Child;

/**
 * Interceptor class for @see \Magento\Sales\Model\ResourceModel\Order\Invoice\Attribute\Backend\Child
 */
class Interceptor extends \Magento\Sales\Model\ResourceModel\Order\Invoice\Attribute\Backend\Child implements \Magento\Framework\Interception\InterceptorInterface
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
