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

namespace ParadoxLabs\Subscriptions\Model\Api;

/**
 * CustomerSubscriptionRepository Class
 */
class CustomerSubscriptionRepository implements \ParadoxLabs\Subscriptions\Api\CustomerSubscriptionRepositoryInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface
     */
    protected $subscriptionRepository;

    /**
     * @var \Magento\Framework\Api\Search\FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * CustomerSubscriptionRepository constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository
     * @param \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->config = $config;
    }

    /**
     * Save subscription.
     *
     * @param int $customerId The customer ID.
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save($customerId, \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        $this->validateEnabled();

        // Validate original record so it can't be overwritten maliciously
        if ($subscription->getId()) {
            $originalSub = $this->subscriptionRepository->getById($subscription->getId());
            $this->validate($customerId, $subscription);
        }

        $this->validate($customerId, $subscription);

        return $this->subscriptionRepository->save($subscription);
    }

    /**
     * Retrieve subscription.
     *
     * @param int $customerId The customer ID.
     * @param int $subscriptionId
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($customerId, $subscriptionId)
    {
        $this->validateEnabled();

        $subscription = $this->subscriptionRepository->getById($subscriptionId);

        $this->validate($customerId, $subscription);

        return $subscription;
    }

    /**
     * Retrieve subscriptions matching the specified criteria.
     *
     * @param int $customerId The customer ID.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList($customerId, \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $this->validateEnabled();

        // Add mandatory filter to limit results to the current user
        $customerFilter = $this->filterBuilder->setField('customer_id')
                                              ->setValue($customerId)
                                              ->setConditionType('eq')
                                              ->create();

        $filterGroups = $searchCriteria->getFilterGroups();
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$customerFilter])->create();

        $searchCriteria->setFilterGroups($filterGroups);

        return $this->subscriptionRepository->getList($searchCriteria);
    }

    /**
     * Delete subscription by ID.
     *
     * @param int $customerId The customer ID.
     * @param int $subscriptionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($customerId, $subscriptionId)
    {
        $this->validateEnabled();

        $card = $this->getById($customerId, $subscriptionId);

        return $this->subscriptionRepository->delete($card);
    }

    /**
     * Do not allow customers to fetch or modify subscriptions belonging to others.
     *
     * @param int $customerId
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     */
    protected function validate(
        $customerId,
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
    ) {
        if ((int)$subscription->getCustomerId() !== (int)$customerId) {
            throw new \Magento\Framework\Exception\InputException(__('You do not have permission for this action.'));
        }
    }

    /**
     * Verify that the public API is enabled.
     *
     * @return void
     * @throws \Magento\Framework\Exception\AuthorizationException
     */
    protected function validateEnabled()
    {
        if ($this->config->isPublicApiEnabled() !== true) {
            throw new \Magento\Framework\Exception\AuthorizationException(
                __('The public TokenBase API is not enabled.')
            );
        }
    }
}
