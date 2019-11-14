<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Model;

use ParadoxLabs\Subscriptions\Api\Data;
use ParadoxLabs\Subscriptions\Api\LogRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class LogRepository
 */
class LogRepository implements LogRepositoryInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Log
     */
    protected $resource;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\Data\LogInterfaceFactory
     */
    protected $logFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\CollectionFactory
     */
    protected $logCollectionFactory;

    /**
     * @var Data\LogSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Log $resource
     * @param \ParadoxLabs\Subscriptions\Api\Data\LogInterfaceFactory $logFactory
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\CollectionFactory $logCollectionFactory
     * @param Data\LogSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Log $resource,
        \ParadoxLabs\Subscriptions\Api\Data\LogInterfaceFactory $logFactory,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\CollectionFactory $logCollectionFactory,
        Data\LogSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->resource = $resource;
        $this->logFactory = $logFactory;
        $this->logCollectionFactory = $logCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * Save log data
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\LogInterface $log
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogInterface
     * @throws CouldNotSaveException
     */
    public function save(Data\LogInterface $log)
    {
        try {
            $this->resource->save($log);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $log;
    }

    /**
     * Load log data by given ID
     *
     * @param string $logId
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($logId)
    {
        $log = $this->logFactory->create();

        $this->resource->load($log, $logId);

        if (!$log->getId()) {
            throw new NoSuchEntityException(__('Subscription with id "%1" does not exist.', $logId));
        }

        return $log;
    }

    /**
     * Load log data by given log ID
     *
     * @param string $logId
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function load($logId)
    {
        return $this->getById($logId);
    }

    /**
     * Load log data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteriaInterface $criteria
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\Collection $collection */
        $collection = $this->logCollectionFactory->create();

        // Add filters from root filter group to the collection
        foreach ($criteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }

        // Add sort order(s)
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() === SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }

        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());

        $collection->load();

        /** @var \ParadoxLabs\Subscriptions\Api\Data\LogSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * Delete log
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\LogInterface $log
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\LogInterface $log)
    {
        try {
            $this->resource->delete($log);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete log by given log ID
     *
     * @param string $logId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($logId)
    {
        return $this->delete($this->getById($logId));
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\Collection $collection
     * @return void
     */
    protected function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\Collection $collection
    ) {
        $fields = [];
        $conds  = [];

        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ?: 'eq';
            $fields[]  = $filter->getField();
            $conds[]   = [$condition => $filter->getValue()];
        }

        if ($fields) {
            $collection->addFieldToFilter($fields, $conds);
        }
    }
}
