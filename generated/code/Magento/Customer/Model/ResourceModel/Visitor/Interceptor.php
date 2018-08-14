<?php
namespace Magento\Customer\Model\ResourceModel\Visitor;

/**
 * Interceptor class for @see \Magento\Customer\Model\ResourceModel\Visitor
 */
class Interceptor extends \Magento\Customer\Model\ResourceModel\Visitor implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, \Magento\Framework\Stdlib\DateTime\DateTime $date, \Magento\Framework\Stdlib\DateTime $dateTime, $connectionName = null)
    {
        $this->___init();
        parent::__construct($context, $date, $dateTime, $connectionName);
    }

    /**
     * {@inheritdoc}
     */
    public function clean(\Magento\Customer\Model\Visitor $object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clean');
        if (!$pluginInfo) {
            return parent::clean($object);
        } else {
            return $this->___callPlugins('clean', func_get_args(), $pluginInfo);
        }
    }
}
