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

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * IntervalRepository Class
 */
class IntervalRepository implements \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval
     */
    protected $resource;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterfaceFactory
     */
    protected $intervalFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval\CollectionFactory
     */
    protected $intervalCollectionFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalSearchResultsInterfaceFactory
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
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval $resource
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterfaceFactory $intervalFactory
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval\CollectionFactory $intervalCollectionFactory
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval $resource,
        \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterfaceFactory $intervalFactory,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval\CollectionFactory $intervalCollectionFactory,
        \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->resource = $resource;
        $this->intervalFactory = $intervalFactory;
        $this->intervalCollectionFactory = $intervalCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * Save interval.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval)
    {
        try {
            $this->resource->save($interval);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $interval;
    }

    /**
     * Retrieve interval.
     *
     * @param int $intervalId
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($intervalId)
    {
        /** @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval */
        $interval = $this->intervalFactory->create();

        $this->resource->load($interval, $intervalId);

        if (!$interval->getId()) {
            throw new NoSuchEntityException(__('Subscription with id "%1" does not exist.', $intervalId));
        }

        return $interval;
    }

    /**
     * Retrieve interval.
     *
     * @param int $intervalId
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function load($intervalId)
    {
        return $this->getById($intervalId);
    }

    /**
     * Retrieve intervals for a given product ID.
     *
     * @param int $productId
     * @param int|null $storeId
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalSearchResultsInterface
     */
    public function getIntervalsByProductId($productId, $storeId = null)
    {
        /** @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval\Collection $collection */
        $collection = $this->intervalCollectionFactory->create();
        $collection->addFieldToFilter('product_id', $productId);
        $collection->addFieldToFilter('store_id', $storeId ?: 0);
        $collection->applyValueSortOrder();
        $collection->load();

        /** @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * Retrieve intervals for a given option ID.
     *
     * @param int $optionId
     * @param int|null $storeId
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalSearchResultsInterface
     */
    public function getIntervalsByOptionId($optionId, $storeId = null)
    {
        /** @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval\Collection $collection */
        $collection = $this->intervalCollectionFactory->create();
        $collection->addFieldToFilter('option_id', $optionId);
        $collection->addFieldToFilter('store_id', $storeId ?: 0);
        $collection->applyValueSortOrder();
        $collection->load();

        /** @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * Retrieve intervals matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval\Collection $collection */
        $collection = $this->intervalCollectionFactory->create();

        // Add filters from root filter group to the collection
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }

        // Add sort order(s)
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() === SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        } else {
            $collection->applyValueSortOrder();
        }

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $collection->load();

        /** @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * Delete interval.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval)
    {
        try {
            $this->resource->delete($interval);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete interval by ID.
     *
     * @param int $intervalId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($intervalId)
    {
        return $this->delete($this->getById($intervalId));
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval\Collection $collection
     * @return void
     */
    protected function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval\Collection $collection
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
