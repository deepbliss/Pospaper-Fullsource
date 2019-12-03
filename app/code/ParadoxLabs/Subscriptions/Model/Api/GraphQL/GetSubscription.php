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

namespace ParadoxLabs\Subscriptions\Model\Api\GraphQL;

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;

/**
 * Subscription Class
 *
 * NB: NOT implementing ResolverInterface because it's not enforced (as of 2.3.1), and it breaks 2.1/2.2 compat.
 */
class GetSubscription /*implements \Magento\Framework\GraphQl\Query\ResolverInterface*/
{
    /**
     * @var \ParadoxLabs\Subscriptions\Api\CustomerSubscriptionRepositoryInterface
     */
    protected $customerSubscriptionRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Api\GraphQL
     */
    protected $graphQL;

    /**
     * Subscription constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Api\CustomerSubscriptionRepositoryInterface $customerSubscriptionRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \ParadoxLabs\Subscriptions\Model\Api\GraphQL $graphQL
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Api\CustomerSubscriptionRepositoryInterface $customerSubscriptionRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \ParadoxLabs\Subscriptions\Model\Api\GraphQL $graphQL
    ) {
        $this->customerSubscriptionRepository = $customerSubscriptionRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->graphQL = $graphQL;
    }

    /**
     * Fetches the data from persistence models and format it according to the GraphQL schema.
     *
     * @param \Magento\Framework\GraphQl\Config\Element\Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param \Magento\Framework\GraphQl\Schema\Type\ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @throws \Exception
     * @return mixed|\Magento\Framework\GraphQl\Query\Resolver\Value
     */
    public function resolve(
        \Magento\Framework\GraphQl\Config\Element\Field $field,
        \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context,
        \Magento\Framework\GraphQl\Schema\Type\ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $this->graphQL->authenticate($context);

        $subscriptions = $this->getSubscriptions(
            $context->getUserId(),
            isset($args['entity_id']) ? $args['entity_id'] : null
        );
        $requestedFields = $this->graphQL->getSubscriptionFields($info);

        /** @var \ParadoxLabs\Subscriptions\Model\Subscription[] $subscriptions */
        $output = [];
        foreach ($subscriptions as $subscription) {
            $output[] = $this->graphQL->convertSubscriptionForGraphQL($subscription, $requestedFields);
        }

        return $output;
    }

    /**
     * Get subscription(s) for the given GraphQL request.
     *
     * @param int $customerId
     * @param int|null $id
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getSubscriptions($customerId, $id = null)
    {
        $searchCriteria = $this->searchCriteriaBuilder;
        if ($id !== null) {
            $searchCriteria->addFilter('entity_id', $id);
        }

        $subscriptions  = $this->customerSubscriptionRepository->getList(
            $customerId,
            $searchCriteria->create()
        )->getItems();

        return $subscriptions;
    }
}
