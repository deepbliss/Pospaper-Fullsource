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
 * UpdateSubscription Class
 *
 * NB: NOT implementing ResolverInterface because it's not enforced (as of 2.3.1), and it breaks 2.1/2.2 compat.
 */
class UpdateSubscription
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
     * @var \ParadoxLabs\Subscriptions\Model\Service\Subscription
     */
    protected $subscriptionService;

    /**
     * UpdateSubscription constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Api\GraphQL $graphQL
     * @param \ParadoxLabs\Subscriptions\Api\CustomerSubscriptionRepositoryInterface $customerSubscriptionRepository
     * @param \ParadoxLabs\Subscriptions\Model\Service\Subscription $subscriptionService
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Api\GraphQL $graphQL,
        \ParadoxLabs\Subscriptions\Api\CustomerSubscriptionRepositoryInterface $customerSubscriptionRepository,
        \ParadoxLabs\Subscriptions\Model\Service\Subscription $subscriptionService
    ) {
        $this->graphQL = $graphQL;
        $this->customerSubscriptionRepository = $customerSubscriptionRepository;
        $this->subscriptionService = $subscriptionService;
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
            $args['input']['entity_id']
        );

        $this->updatePayment($subscription, $args);
        $this->updateShippingAddress($subscription, $args);

        $this->customerSubscriptionRepository->save(
            $context->getUserId(),
            $subscription
        );

        $requestedFields = $this->graphQL->getSubscriptionFields($info);
        return $this->graphQL->convertSubscriptionForGraphQL($subscription, $requestedFields);
    }

    /**
     * @param array $args
     * @return void
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlInputException
     */
    protected function validateInput($args)
    {
        if (!isset($args['input']) || !is_array($args['input']) || empty($args['input'])) {
            throw new GraphQlInputException(__('"input" value must be specified'));
        }

        if (!isset($args['input']['entity_id']) || empty($args['input']['entity_id'])) {
            throw new GraphQlInputException(__('"entity_id" value must be specified'));
        }
    }

    /**
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @param array $args
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function updatePayment(
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription,
        array $args
    ) {
        if (!empty($args['input']['payment_account'])) {
            $this->subscriptionService->changePaymentId($subscription, $args['input']['payment_account']);
        }
    }

    /**
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @param array $args
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function updateShippingAddress(
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription,
        array $args
    ) {
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
        $quote = $subscription->getQuote();

        if ((bool)$quote->getIsVirtual() === false) {
            $addressData = [];
            if (!empty($args['input']['shipping_address'])) {
                $addressData = $args['input']['shipping_address'];
            }

            if (!empty($args['input']['shipping_address_id'])) {
                $addressData['address_id'] = $args['input']['shipping_address_id'];
            }

            if (isset($addressData['region']['region_id'])) {
                $addressData['region_id'] = $addressData['region']['region_id'];
            }

            if (!empty($addressData)) {
                $this->subscriptionService->changeShippingAddress($subscription, $addressData);
            }
        }
    }
}
