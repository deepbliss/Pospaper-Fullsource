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
 * CustomerLogRepository Class
 */
class CustomerLogRepository implements \ParadoxLabs\Subscriptions\Api\CustomerLogRepositoryInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Api\LogRepositoryInterface
     */
    protected $logRepository;

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
     * @param \ParadoxLabs\Subscriptions\Api\LogRepositoryInterface $logRepository
     * @param \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Api\LogRepositoryInterface $logRepository,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->logRepository      = $logRepository;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->filterBuilder      = $filterBuilder;
        $this->config = $config;
    }

    /**
     * Retrieve logs matching the specified criteria.
     *
     * @param int $customerId
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogSearchResultsInterface
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

        // Add mandatory filter to limit results to billing-related logs
        $orderFilter = $this->filterBuilder->setField('order_increment_id')
                                           ->setValue(true)
                                           ->setConditionType('notnull')
                                           ->create();

        $filterGroups = $searchCriteria->getFilterGroups();
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$customerFilter])->create();
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$orderFilter])->create();

        $searchCriteria->setFilterGroups($filterGroups);

        return $this->logRepository->getList($searchCriteria);
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
