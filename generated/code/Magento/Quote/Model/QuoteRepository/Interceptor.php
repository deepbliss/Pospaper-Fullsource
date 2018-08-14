<?php
namespace Magento\Quote\Model\QuoteRepository;

/**
 * Interceptor class for @see \Magento\Quote\Model\QuoteRepository
 */
class Interceptor extends \Magento\Quote\Model\QuoteRepository implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Quote\Model\QuoteFactory $quoteFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Quote\Model\ResourceModel\Quote\Collection $quoteCollection, \Magento\Quote\Api\Data\CartSearchResultsInterfaceFactory $searchResultsDataFactory, \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor, \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor = null, \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory = null)
    {
        $this->___init();
        parent::__construct($quoteFactory, $storeManager, $quoteCollection, $searchResultsDataFactory, $extensionAttributesJoinProcessor, $collectionProcessor, $quoteCollectionFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getActive($cartId, array $sharedStoreIds = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActive');
        if (!$pluginInfo) {
            return parent::getActive($cartId, $sharedStoreIds);
        } else {
            return $this->___callPlugins('getActive', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveForCustomer($customerId, array $sharedStoreIds = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActiveForCustomer');
        if (!$pluginInfo) {
            return parent::getActiveForCustomer($customerId, $sharedStoreIds);
        } else {
            return $this->___callPlugins('getActiveForCustomer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Magento\Quote\Api\Data\CartInterface $quote)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save($quote);
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }
}
