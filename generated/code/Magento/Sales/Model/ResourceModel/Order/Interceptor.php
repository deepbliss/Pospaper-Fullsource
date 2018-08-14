<?php
namespace Magento\Sales\Model\ResourceModel\Order;

/**
 * Interceptor class for @see \Magento\Sales\Model\ResourceModel\Order
 */
class Interceptor extends \Magento\Sales\Model\ResourceModel\Order implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, \Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot $entitySnapshot, \Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationComposite $entityRelationComposite, \Magento\Sales\Model\ResourceModel\Attribute $attribute, \Magento\SalesSequence\Model\Manager $sequenceManager, \Magento\Sales\Model\ResourceModel\Order\Handler\State $stateHandler, $connectionName = null)
    {
        $this->___init();
        parent::__construct($context, $entitySnapshot, $entityRelationComposite, $attribute, $sequenceManager, $stateHandler, $connectionName);
    }

    /**
     * {@inheritdoc}
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'load');
        if (!$pluginInfo) {
            return parent::load($object, $value, $field);
        } else {
            return $this->___callPlugins('load', func_get_args(), $pluginInfo);
        }
    }
}
