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
use ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class SubscriptionRepository
 */
class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription
     */
    protected $resource;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterfaceFactory
     */
    protected $subscriptionFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Data\SubscriptionSearchResultsInterfaceFactory
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
     * @var Service\Subscription
     */
    protected $subscriptionService;

    /**
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription $resource
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterfaceFactory $subscriptionFactory
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $collectionFactory
     * @param Data\SubscriptionSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param Service\Subscription $subscriptionService
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription $resource,
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterfaceFactory $subscriptionFactory,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $collectionFactory,
        Data\SubscriptionSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        \ParadoxLabs\Subscriptions\Model\Service\Subscription $subscriptionService
    ) {
        $this->resource = $resource;
        $this->subscriptionFactory = $subscriptionFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Save Subscription data
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface
     * @throws CouldNotSaveException
     */
    public function save(Data\SubscriptionInterface $subscription)
    {
        try {
            $this->resource->save($subscription);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $subscription;
    }

    /**
     * Load Subscription data by given ID
     *
     * @param string $subscriptionId
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($subscriptionId)
    {
        $subscription = $this->subscriptionFactory->create();

        $this->resource->load($subscription, $subscriptionId);

        if (!$subscription->getId()) {
            throw new NoSuchEntityException(__('Subscription with id "%1" does not exist.', $subscriptionId));
        }

        return $subscription;
    }

    /**
     * Load Subscription data by given Subscription Identity
     *
     * @param string $subscriptionId
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function load($subscriptionId)
    {
        return $this->getById($subscriptionId);
    }

    /**
     * Load Subscription data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteriaInterface $criteria
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\Collection $collection */
        $collection = $this->collectionFactory->create();

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

        /** @var \ParadoxLabs\Subscriptions\Api\Data\SubscriptionSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * Delete Subscription
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\SubscriptionInterface $subscription)
    {
        try {
            $this->resource->delete($subscription);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete Subscription by given Subscription Identity
     *
     * @param string $subscriptionId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($subscriptionId)
    {
        return $this->delete($this->getById($subscriptionId));
    }

    /**
     * Run a billing for the given subscription
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return bool
     * @throws CouldNotSaveException
     */
    public function bill(Data\SubscriptionInterface $subscription)
    {
        try {
            return $this->subscriptionService->generateOrder([$subscription]);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
    }

    /**
     * Run a billing for the given subscription ID
     *
     * @param string $subscriptionId
     * @return bool
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function billById($subscriptionId)
    {
        return $this->bill($this->getById($subscriptionId));
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\Collection $collection
     * @return void
     */
    protected function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\Collection $collection
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
