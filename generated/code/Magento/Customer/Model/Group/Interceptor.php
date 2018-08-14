<?php
namespace Magento\Customer\Model\Group;

/**
 * Interceptor class for @see \Magento\Customer\Model\Group
 */
class Interceptor extends \Magento\Customer\Model\Group implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Store\Model\StoresConfig $storesConfig, \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor, \Magento\Tax\Model\ClassModelFactory $classModelFactory, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $storesConfig, $dataObjectProcessor, $classModelFactory, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete();
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }
}
