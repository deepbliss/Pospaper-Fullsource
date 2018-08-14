<?php
namespace Magento\Eav\Model\Entity\Attribute\Set;

/**
 * Interceptor class for @see \Magento\Eav\Model\Entity\Attribute\Set
 */
class Interceptor extends \Magento\Eav\Model\Entity\Attribute\Set implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Eav\Model\Config $eavConfig, \Magento\Eav\Model\Entity\Attribute\GroupFactory $attrGroupFactory, \Magento\Eav\Model\Entity\AttributeFactory $attributeFactory, \Magento\Eav\Model\ResourceModel\Entity\Attribute $resourceAttribute, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $eavConfig, $attrGroupFactory, $attributeFactory, $resourceAttribute, $resource, $resourceCollection, $data);
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
