<?php
namespace Magento\Customer\Model\Attribute\Backend\Data\Boolean;

/**
 * Interceptor class for @see \Magento\Customer\Model\Attribute\Backend\Data\Boolean
 */
class Interceptor extends \Magento\Customer\Model\Attribute\Backend\Data\Boolean implements \Magento\Framework\Interception\InterceptorInterface
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
