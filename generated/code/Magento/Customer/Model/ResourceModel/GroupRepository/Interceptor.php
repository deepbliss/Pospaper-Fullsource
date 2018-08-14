<?php
namespace Magento\Customer\Model\ResourceModel\GroupRepository;

/**
 * Interceptor class for @see \Magento\Customer\Model\ResourceModel\GroupRepository
 */
class Interceptor extends \Magento\Customer\Model\ResourceModel\GroupRepository implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Customer\Model\GroupRegistry $groupRegistry, \Magento\Customer\Model\GroupFactory $groupFactory, \Magento\Customer\Api\Data\GroupInterfaceFactory $groupDataFactory, \Magento\Customer\Model\ResourceModel\Group $groupResourceModel, \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor, \Magento\Customer\Api\Data\GroupSearchResultsInterfaceFactory $searchResultsFactory, \Magento\Tax\Api\TaxClassRepositoryInterface $taxClassRepositoryInterface, \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor, \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor = null)
    {
        $this->___init();
        parent::__construct($groupRegistry, $groupFactory, $groupDataFactory, $groupResourceModel, $dataObjectProcessor, $searchResultsFactory, $taxClassRepositoryInterface, $extensionAttributesJoinProcessor, $collectionProcessor);
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Magento\Customer\Api\Data\GroupInterface $group)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save($group);
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }
}
