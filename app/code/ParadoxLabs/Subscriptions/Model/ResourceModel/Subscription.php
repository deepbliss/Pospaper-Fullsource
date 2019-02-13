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

namespace ParadoxLabs\Subscriptions\Model\ResourceModel;

/**
 * Subscription resource model
 */
class Subscription extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'paradoxlabs_subscription_resource';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'resource';

    /**
     * @var \Magento\SalesSequence\Model\Manager
     */
    protected $sequenceManager;

    /**
     * Class constructor
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\SalesSequence\Model\Manager $sequenceManager
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\SalesSequence\Model\Manager $sequenceManager,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);

        $this->sequenceManager = $sequenceManager;
    }

    /**
     * Get reserved subscription id
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return string
     */
    public function reserveIncrementId(\Magento\Quote\Api\Data\CartInterface $quote)
    {
        return $this->sequenceManager->getSequence(
            'subscription',
            $quote->getStore()->getGroup()->getDefaultStoreId()
        )->getNextValue();
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('paradoxlabs_subscription', 'entity_id');
    }
}
