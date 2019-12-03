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

namespace ParadoxLabs\Subscriptions\Model\ResourceModel\Log;

/**
 * Log collection
 */

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'log_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'paradoxlabs_subscription_log_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'log_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_init(
            \ParadoxLabs\Subscriptions\Model\Log::class,
            \ParadoxLabs\Subscriptions\Model\ResourceModel\Log::class
        );
    }

    /**
     * Add subscription filter to the current collection.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return $this
     */
    public function addSubscriptionFilter(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        $this->addFieldToFilter('main_table.subscription_id', $subscription->getId());

        return $this;
    }

    /**
     * Join the subscription table to the current collection.
     *
     * @return void
     */
    public function joinSubscriptionTable()
    {
        $this->join(
            [
                'paradoxlabs_subscription' => $this->getTable('paradoxlabs_subscription')
            ],
            'paradoxlabs_subscription.entity_id=main_table.subscription_id',
            [
                'store_id',
                'increment_id',
                'customer_id',
            ]
        );
    }
}
