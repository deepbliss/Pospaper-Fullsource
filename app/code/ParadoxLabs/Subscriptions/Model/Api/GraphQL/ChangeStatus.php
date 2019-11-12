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

use Magento\Framework\GraphQl\Exception\GraphQlInputException;

/**
 * ChangeStatus Class
 *
 * NB: NOT implementing ResolverInterface because it's not enforced (as of 2.3.1), and it breaks 2.1/2.2 compat.
 */
class ChangeStatus
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\Api\GraphQL
     */
    protected $graphQL;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\CustomerSubscriptionRepositoryInterface
     */
    protected $customerSubscriptionRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Status
     */
    protected $statusSource;

    /**
     * ChangeStatus constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Api\GraphQL $graphQL
     * @param \ParadoxLabs\Subscriptions\Api\CustomerSubscriptionRepositoryInterface $customerSubscriptionRepository
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param \ParadoxLabs\Subscriptions\Model\Source\Status $statusSource
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Api\GraphQL $graphQL,
        \ParadoxLabs\Subscriptions\Api\CustomerSubscriptionRepositoryInterface $customerSubscriptionRepository,
        \ParadoxLabs\Subscriptions\Model\Config $config,
        \ParadoxLabs\Subscriptions\Model\Source\Status $statusSource
    ) {
        $this->graphQL = $graphQL;
        $this->customerSubscriptionRepository = $customerSubscriptionRepository;
        $this->config = $config;
        $this->statusSource = $statusSource;
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

        $this->validateInput($args);

        $subscription = $this->customerSubscriptionRepository->getById(
            $context->getUserId(),
            $args['entity_id']
        );

        $this->validateStatus($subscription, $args['status']);
        $subscription->setStatus($args['status']);

        $this->customerSubscriptionRepository->save(
            $context->getUserId(),
            $subscription
        );

        $requestedFields = $this->graphQL->getSubscriptionFields($info);
        return $this->graphQL->convertSubscriptionForGraphQL($subscription, $requestedFields);
    }

    /**
     * Make sure we have permission to apply the new requested status.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @param string $newStatus
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function validateStatus(
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription,
        $newStatus
    ) {
        if ($this->statusSource->canSetStatusAsCustomer($subscription, $newStatus) !== true) {
            throw new GraphQlInputException(__('Cannot change the status to "%1".', $newStatus));
        }
    }

    /**
     * Validate GraphQL request input.
     *
     * @param array $args
     * @return void
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlInputException
     */
    protected function validateInput(array $args)
    {
        $requiredFields = ['entity_id', 'status'];
        foreach ($requiredFields as $v) {
            if (!isset($args[ $v ]) || empty($args[ $v ])) {
                throw new GraphQlInputException(__('"%1" value must be specified', $v));
            }
        }
    }
}
