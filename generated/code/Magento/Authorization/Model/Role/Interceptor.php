<?php
namespace Magento\Authorization\Model\Role;

/**
 * Interceptor class for @see \Magento\Authorization\Model\Role
 */
class Interceptor extends \Magento\Authorization\Model\Role implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Authorization\Model\ResourceModel\Role $resource, \Magento\Authorization\Model\ResourceModel\Role\Collection $resourceCollection, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save();
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }
}
