<?php
namespace Magento\Eav\Model\Entity\Attribute\Backend\Time\Updated;

/**
 * Interceptor class for @see \Magento\Eav\Model\Entity\Attribute\Backend\Time\Updated
 */
class Interceptor extends \Magento\Eav\Model\Entity\Attribute\Backend\Time\Updated implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Stdlib\DateTime $dateTime)
    {
        $this->___init();
        parent::__construct($dateTime);
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
