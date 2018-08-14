<?php
namespace Magento\CatalogRule\Model\Rule;

/**
 * Interceptor class for @see \Magento\CatalogRule\Model\Rule
 */
class Interceptor extends \Magento\CatalogRule\Model\Rule implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\CatalogRule\Model\Rule\Condition\CombineFactory $combineFactory, \Magento\CatalogRule\Model\Rule\Action\CollectionFactory $actionCollectionFactory, \Magento\Catalog\Model\ProductFactory $productFactory, \Magento\Framework\Model\ResourceModel\Iterator $resourceIterator, \Magento\Customer\Model\Session $customerSession, \Magento\CatalogRule\Helper\Data $catalogRuleData, \Magento\Framework\App\Cache\TypeListInterface $cacheTypesList, \Magento\Framework\Stdlib\DateTime $dateTime, \Magento\CatalogRule\Model\Indexer\Rule\RuleProductProcessor $ruleProductProcessor, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $relatedCacheTypes = array(), array $data = array(), \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory = null, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory = null, \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($context, $registry, $formFactory, $localeDate, $productCollectionFactory, $storeManager, $combineFactory, $actionCollectionFactory, $productFactory, $resourceIterator, $customerSession, $catalogRuleData, $cacheTypesList, $dateTime, $ruleProductProcessor, $resource, $resourceCollection, $relatedCacheTypes, $data, $extensionFactory, $customAttributeFactory, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function getMatchingProductIds()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMatchingProductIds');
        if (!$pluginInfo) {
            return parent::getMatchingProductIds();
        } else {
            return $this->___callPlugins('getMatchingProductIds', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate(\Magento\Framework\DataObject $object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate($object);
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }
}
