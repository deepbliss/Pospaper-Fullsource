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

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * GraphQL Class
 */
class GraphQL
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\CustomerLogRepositoryInterface
     */
    protected $customerLogRepository;

    /**
     * GraphQL constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \ParadoxLabs\Subscriptions\Api\CustomerLogRepositoryInterface $customerLogRepository
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Config $config,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \ParadoxLabs\Subscriptions\Api\CustomerLogRepositoryInterface $customerLogRepository
    ) {
        $this->config                = $config;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->customerLogRepository = $customerLogRepository;
    }

    /**
     * Verify the requester is authorized to request data.
     *
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @return void
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException
     */
    public function authenticate(
        \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
    ) {
        if ($this->config->isPublicApiEnabled() !== true) {
            throw new GraphQlAuthorizationException(
                __('The subscriptions API is not enabled.')
            );
        }

        /** @var ContextInterface $context */
        if ((!$context->getUserId()) || $context->getUserType() === UserContextInterface::USER_TYPE_GUEST) {
            throw new GraphQlAuthorizationException(
                __(
                    'Current customer does not have access to the resource "%1"',
                    'subscriptions'
                )
            );
        }
    }

    /**
     * Convert the given subscription into an array format suitable for GraphQL.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @param string[] $requestedFields
     * @return array
     */
    public function convertSubscriptionForGraphQL(
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription,
        $requestedFields
    ) {
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
        $data                      = $subscription->toArray();

        // Expose any additional info to GraphQL. Note, will not handle nested data without further work.
        $data['additional'] = $this->toKeyValueArray($subscription->getAdditionalInformation());

        // Add quote data, if requested
        if (in_array('quote', $requestedFields, true)) {
            try {
                $data['quote'] = $this->getQuoteData($subscription);
            } catch (\Exception $e) {
                // No subscription quote -- no-op
            }
        }

        // Add log data, if requested
        if (in_array('logs', $requestedFields, true)) {
            $data['logs'] = $this->getLogData($subscription);
        }

        return $data;
    }

    /**
     * Return field names for all requested product fields.
     *
     * @param ResolveInfo $info
     * @return string[]
     */
    public function getSubscriptionFields(ResolveInfo $info)
    {
        if ($info->operation->operation === 'mutation') {
            return array_keys($info->getFieldSelection());
        }

        $fieldNames = [];
        foreach ($info->fieldNodes as $node) {
            if ($node->name->value !== 'subscriptions') {
                continue;
            }
            foreach ($node->selectionSet->selections as $selectionNode) {
                if ($selectionNode->kind === 'InlineFragment') {
                    foreach ($selectionNode->selectionSet->selections as $inlineSelection) {
                        if ($inlineSelection->kind === 'InlineFragment') {
                            continue;
                        }
                        $fieldNames[] = $inlineSelection->name->value;
                    }
                    continue;
                }
                $fieldNames[] = $selectionNode->name->value;
            }
        }

        return $fieldNames;
    }

    /**
     * Convert a single-dimensional assoc array into a key/value options array
     *
     * @param array $inputArray
     * @return array
     */
    protected function toKeyValueArray($inputArray)
    {
        $output = [];
        if (is_array($inputArray)) {
            foreach ($inputArray as $key => $value) {
                $output[] = [
                    'key' => $key,
                    'value' => $value,
                ];
            }
        }

        return $output;
    }

    /**
     * Get quote data as GraphQL-ready array
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return array
     */
    public function getQuoteData(
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
    ) {
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote     = $subscription->getQuote();
        $quoteData = $quote->toArray();

        $quoteData['items'] = [];
        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($quote->getAllItems() as $item) {
            $quoteData['items'][] = $item->toArray();
        }

        $quoteData['billing_address']           = $quote->getBillingAddress()->toArray();
        $quoteData['billing_address']['street'] = $quote->getBillingAddress()->getStreet();

        $quoteData['shipping_address']           = $quote->getShippingAddress()->toArray();
        $quoteData['shipping_address']['street'] = $quote->getShippingAddress()->getStreet();

        $quoteData['payment']                           = $quote->getPayment()->toArray();
        $quoteData['payment']['additional_data']        = $this->toKeyValueArray(
            $quote->getPayment()->getAdditionalData()
        );
        $quoteData['payment']['additional_information'] = $this->toKeyValueArray(
            $quote->getPayment()->getAdditionalInformation()
        );

        return $quoteData;
    }

    /**
     * Get logs data as GraphQL-ready array
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLogData(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        $logSearchCriteria = $this->searchCriteriaBuilder
            ->addFilter('subscription_id', $subscription->getId())
            ->create();

        $logs = [];

        $logList = $this->customerLogRepository->getList($subscription->getCustomerId(), $logSearchCriteria);
        /** @var \ParadoxLabs\Subscriptions\Model\Log $log */
        foreach ($logList->getItems() as $log) {
            $logArray                           = $log->toArray();
            $logArray['additional_information'] = $this->toKeyValueArray($log->getAdditionalInformation());
            $logs[]                             = $logArray;
        }

        return $logs;
    }
}
