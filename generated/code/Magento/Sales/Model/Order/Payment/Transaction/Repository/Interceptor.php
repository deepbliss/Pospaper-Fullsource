<?php
namespace Magento\Sales\Model\Order\Payment\Transaction\Repository;

/**
 * Interceptor class for @see \Magento\Sales\Model\Order\Payment\Transaction\Repository
 */
class Interceptor extends \Magento\Sales\Model\Order\Payment\Transaction\Repository implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Sales\Api\Data\TransactionSearchResultInterfaceFactory $searchResultFactory, \Magento\Framework\Api\FilterBuilder $filterBuilder, \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder, \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder, \Magento\Sales\Model\ResourceModel\Metadata $metaData, \Magento\Sales\Model\EntityStorageFactory $entityStorageFactory, \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor = null)
    {
        $this->___init();
        parent::__construct($searchResultFactory, $filterBuilder, $searchCriteriaBuilder, $sortOrderBuilder, $metaData, $entityStorageFactory, $collectionProcessor);
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getList');
        if (!$pluginInfo) {
            return parent::getList($searchCriteria);
        } else {
            return $this->___callPlugins('getList', func_get_args(), $pluginInfo);
        }
    }
}
