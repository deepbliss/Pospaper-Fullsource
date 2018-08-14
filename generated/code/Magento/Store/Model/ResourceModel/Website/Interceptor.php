<?php
namespace Magento\Store\Model\ResourceModel\Website;

/**
 * Interceptor class for @see \Magento\Store\Model\ResourceModel\Website
 */
class Interceptor extends \Magento\Store\Model\ResourceModel\Website implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, $connectionName = null)
    {
        $this->___init();
        parent::__construct($context, $connectionName);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Magento\Framework\Model\AbstractModel $object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete($object);
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }
}
