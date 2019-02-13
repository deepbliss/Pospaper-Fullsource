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

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use ParadoxLabs\Subscriptions\Api\Data\LogInterface;

/**
 * Subscription log - change record
 */
class Log extends \Magento\Framework\Model\AbstractExtensibleModel implements LogInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'paradoxlabs_subscription_log';

    /**
     * @var string
     */
    protected $_eventObject = 'log';

    /**
     * @var Source\Status
     */
    protected $statusSource;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $backendSession;

    /**
     * @var TimezoneInterface
     */
    protected $dateProcessor;

    /**
     * @var array
     */
    protected $additionalInfo;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param Source\Status $statusSource
     * @param \Magento\Backend\Model\Auth\Session $backendSession *Proxy
     * @param TimezoneInterface $dateProcessor
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \ParadoxLabs\Subscriptions\Model\Source\Status $statusSource,
        \Magento\Backend\Model\Auth\Session $backendSession,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->statusSource = $statusSource;
        $this->backendSession = $backendSession;
        $this->dateProcessor = $dateProcessor;

        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Set subscription log is associated to.
     *
     * @param Subscription $subscription
     * @return $this
     */
    public function setSubscription(Subscription $subscription)
    {
        $this->setSubscriptionId($subscription->getId());

        return $this;
    }

    /**
     * Set subscription ID.
     *
     * @param int $subscriptionId
     * @return LogInterface
     */
    public function setSubscriptionId($subscriptionId)
    {
        return $this->setData('subscription_id', $subscriptionId);
    }

    /**
     * Get subscription ID.
     *
     * @return int
     */
    public function getSubscriptionId()
    {
        return $this->getData('subscription_id');
    }

    /**
     * Set subscription status.
     *
     * @param string $newStatus
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setStatus($newStatus)
    {
        if ($this->statusSource->isAllowedStatus($newStatus)) {
            $this->setData('status', $newStatus);
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid status "%1"', $newStatus));
        }

        return $this;
    }

    /**
     * Get subscription status.
     *
     * @return string $this
     */
    public function getStatus()
    {
        return $this->getData('status');
    }

    /**
     * Set associated order Increment ID. Used for display purposes (grids, etc.).
     *
     * @param string $orderIncrementId
     * @return $this
     */
    public function setOrderIncrementId($orderIncrementId)
    {
        return $this->setData('order_increment_id', $orderIncrementId);
    }

    /**
     * Get associated order increment ID.
     *
     * @return string
     */
    public function getOrderIncrementId()
    {
        return $this->getData('order_increment_id');
    }

    /**
     * Set associated order ID. Used for link purposes (going from log to order).
     *
     * We can't join by order ID or order increment ID because of split DB restrictions.
     *
     * @param string $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        return $this->setData('order_id', $orderId);
    }

    /**
     * Get associated order ID.
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->getData('order_id');
    }

    /**
     * Set log message.
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        return $this->setData('description', $description);
    }

    /**
     * Get log message.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getData('description');
    }

    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\ParadoxLabs\Subscriptions\Model\ResourceModel\Log::class);
    }

    /**
     * Finalize before saving.
     *
     * @return $this
     */
    public function beforeSave()
    {
        parent::beforeSave();

        if ($this->isObjectNew()) {
            /**
             * Set date.
             */
            $now = $this->dateProcessor->date(null, null, false);
            $this->setCreatedAt($now->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));

            /**
             * Set agent (if any).
             */
            if ($this->hasData('agent_id') === false) {
                $this->determineAgent();
            }
        }

        return $this;
    }

    /**
     * Attempt to determine whether this action was triggered by the customer, an admin, or neither. Result is stored
     * with the log.
     *
     * @return $this
     */
    protected function determineAgent()
    {
        if ($this->_appState->getAreaCode() === \Magento\Framework\App\Area::AREA_FRONTEND) {
            // Frontend: Customer action.
            $this->setAgentId(-1);
        } elseif ($this->_appState->getAreaCode() === \Magento\Framework\App\Area::AREA_ADMINHTML) {
            // Admin: Admin user action (record who).
            $this->setAgentId($this->backendSession->getUser()->getId());
        } else {
            // Other: Don't know, or none. API, cron, etc.
            $this->setAgentId(0);
        }

        return $this;
    }

    /**
     * Set ID of agent responsible for the logged action. admin user_id, or -1 for customer.
     *
     * @param int $agentId
     * @return $this
     */
    public function setAgentId($agentId)
    {
        return $this->setData('agent_id', $agentId);
    }

    /**
     * Get ID of agent responsible for the logged action. admin user_id, or -1 for customer.
     *
     * @return int
     */
    public function getAgentId()
    {
        return $this->getData('agent_id');
    }

    /**
     * Set created-at date.
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->setData('created_at', $createdAt);

        return $this;
    }

    /**
     * Get created-at date.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData('created_at');
    }

    /**
     * Get additional information.
     *
     * If $key is set, will return that value or null; otherwise, will return an array of all additional date.
     *
     * @param string|null $key
     * @return mixed|null
     */
    public function getAdditionalInformation($key = null)
    {
        if ($this->additionalInfo === null) {
            $this->additionalInfo = json_decode(parent::getData('additional_information'), 1);

            if (empty($this->additionalInfo)) {
                $this->additionalInfo = [];
            }
        }

        if ($key !== null) {
            return (isset($this->additionalInfo[$key]) ? $this->additionalInfo[$key] : null);
        }

        return $this->additionalInfo;
    }

    /**
     * Set additional information.
     *
     * Can pass in a key-value pair to set one value, or a single parameter (associative array) to overwrite all data.
     *
     * @param string|array $key
     * @param string|null $value
     * @return $this
     */
    public function setAdditionalInformation($key, $value = null)
    {
        if ($value !== null) {
            if ($this->additionalInfo === null) {
                $this->getAdditionalInformation();
            }

            $this->additionalInfo[$key] = $value;
        } elseif (is_array($key)) {
            $this->additionalInfo = $key;
        }

        parent::setData('additional_information', json_encode($this->additionalInfo));

        return $this;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\LogExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \ParadoxLabs\Subscriptions\Api\Data\LogExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
