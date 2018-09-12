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
    public function get($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'get');
        if (!$pluginInfo) {
            return parent::get($id);
        } else {
            return $this->___callPlugins('get', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getByTransactionType($transactionType, $paymentId, $orderId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getByTransactionType');
        if (!$pluginInfo) {
            return parent::getByTransactionType($transactionType, $paymentId, $orderId);
        } else {
            return $this->___callPlugins('getByTransactionType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getByTransactionId($transactionId, $paymentId, $orderId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getByTransactionId');
        if (!$pluginInfo) {
            return parent::getByTransactionId($transactionId, $paymentId, $orderId);
        } else {
            return $this->___callPlugins('getByTransactionId', func_get_args(), $pluginInfo);
        }
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

    /**
     * {@inheritdoc}
     */
    public function delete(\Magento\Sales\Api\Data\TransactionInterface $entity)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete($entity);
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Magento\Sales\Api\Data\TransactionInterface $entity)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save($entity);
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'create');
        if (!$pluginInfo) {
            return parent::create();
        } else {
            return $this->___callPlugins('create', func_get_args(), $pluginInfo);
        }
    }
}
