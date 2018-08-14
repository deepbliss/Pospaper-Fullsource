<?php
namespace Magento\Customer\Model\ResourceModel\Customer;

/**
 * Interceptor class for @see \Magento\Customer\Model\ResourceModel\Customer
 */
class Interceptor extends \Magento\Customer\Model\ResourceModel\Customer implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Eav\Model\Entity\Context $context, \Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot $entitySnapshot, \Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationComposite $entityRelationComposite, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Validator\Factory $validatorFactory, \Magento\Framework\Stdlib\DateTime $dateTime, \Magento\Store\Model\StoreManagerInterface $storeManager, $data = array())
    {
        $this->___init();
        parent::__construct($context, $entitySnapshot, $entityRelationComposite, $scopeConfig, $validatorFactory, $dateTime, $storeManager, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Magento\Framework\Model\AbstractModel $object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save($object);
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete($object);
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }
}
