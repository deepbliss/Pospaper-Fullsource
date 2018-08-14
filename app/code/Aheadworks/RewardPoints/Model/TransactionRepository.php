<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Aheadworks\RewardPoints\Api\TransactionRepositoryInterface;
use Aheadworks\RewardPoints\Api\Data\TransactionInterfaceFactory;
use Aheadworks\RewardPoints\Api\Data\TransactionSearchResultsInterfaceFactory;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Aheadworks\RewardPoints\Model\ResourceModel\Transaction as TransactionResource;
use Aheadworks\RewardPoints\Model\ResourceModel\Transaction\CollectionFactory as TransactionCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Reflection\DataObjectProcessor;
use Aheadworks\RewardPoints\Model\Source\Transaction\EntityType as TransactionEntityType;

/**
 * Class Aheadworks\RewardPoints\Model\TransactionRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @var TransactionResource
     */
    private $resource;

    /**
     * @var TransactionInterfaceFactory
     */
    private $transactionFactory;

    /**
     * @var TransactionCollectionFactory
     */
    private $transactionCollectionFactory;

    /**
     * @var TransactionSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var TransactionEntityType
     */
    private $transactionEntityType;

    /**
     * @var TransactionInterface[]
     */
    private $instancesById = [];

    /**
     * @param TransactionResource $resource
     * @param TransactionInterfaceFactory $transactionFactory
     * @param TransactionCollectionFactory $transactionCollectionFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param TransactionSearchResultsInterfaceFactory $searchResultsFactory
     * @param EntityManager $entityManager
     * @param DateTime $dateTime
     * @param TransactionEntityType $transactionEntityType
     */
    public function __construct(
        TransactionResource $resource,
        TransactionInterfaceFactory $transactionFactory,
        TransactionCollectionFactory $transactionCollectionFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        TransactionSearchResultsInterfaceFactory $searchResultsFactory,
        EntityManager $entityManager,
        DateTime $dateTime,
        TransactionEntityType $transactionEntityType
    ) {
        $this->resource = $resource;
        $this->transactionFactory = $transactionFactory;
        $this->transactionCollectionFactory = $transactionCollectionFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->entityManager = $entityManager;
        $this->dateTime = $dateTime;
        $this->transactionEntityType = $transactionEntityType;
    }

    /**
     *  {@inheritDoc}
     */
    public function save(TransactionInterface $transaction, $arguments = [])
    {
        try {
            if (!$transaction->getTransactionDate()) {
                $transaction->setTransactionDate($this->dateTime->getTodayDate(true));
            }
            $this->entityManager->save($transaction, $arguments);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $transaction;
    }

    /**
     *  {@inheritDoc}
     */
    public function create()
    {
        return $this->transactionFactory->create();
    }

    /**
     *  {@inheritDoc}
     */
    public function getById($id, $cached = true)
    {
        if ($cached && isset($this->instancesById[$id])) {
            return $this->instancesById[$id];
        }
        $transaction = $this->create();
        $this->entityManager->load($transaction, $id);

        if (!$transaction->getTransactionId()) {
            throw new NoSuchEntityException(__('Requested transaction doesn\'t exist'));
        }

        $this->instancesById[$transaction->getTransactionId()] = $transaction;
        return $transaction;
    }

    /**
     * {@inheritDoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->transactionCollectionFactory->create();

        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() == TransactionInterface::EXPIRATION_DATE && $filter->getValue() == 'expired') {
                    $collection->addExpiredTransactionFilter();
                } elseif ($filter->getField() == TransactionInterface::EXPIRATION_DATE
                    && $filter->getValue() == 'will_expire'
                ) {
                    $collection->addWillExpireTransactionFilter();
                } elseif ($filter->getField() == TransactionInterface::BALANCE && $filter->getValue() == 'positive') {
                    $collection->addPositiveBalanceFilter();
                } elseif (in_array($filter->getField(), $this->transactionEntityType->getEntityTypes())) {
                    $collection->addFilterByEntity($filter->getField(), $filter->getValue());
                } else {
                    $condition = $filter->getConditionType() ?: 'eq';
                    $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
                }
            }
        }

        if ($sortOrders = $criteria->getSortOrders()) {
            foreach ($sortOrders as $order) {
                $collection->addOrder($order->getField(), $order->getDirection());
            }
        }

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());

        $transactions = [];
        /** @var Transaction $transactionModel */
        foreach ($collection as $transactionModel) {
            $transactions[] = $transactionModel;
        }

        $searchResults->setItems($transactions);

        return $searchResults;
    }
}
