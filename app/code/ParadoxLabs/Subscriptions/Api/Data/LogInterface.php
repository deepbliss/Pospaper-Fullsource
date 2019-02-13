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

namespace ParadoxLabs\Subscriptions\Api\Data;

use ParadoxLabs\Subscriptions\Model\Subscription;

/**
 * Subscription log - change record
 *
 * @api
 */
interface LogInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Get log ID.
     *
     * @return int
     */
    public function getId();

    /**
     * Set log ID
     *
     * @param int $logId
     * @return LogInterface
     */
    public function setId($logId);

    /**
     * Get subscription ID.
     *
     * @return int
     */
    public function getSubscriptionId();

    /**
     * Set subscription ID.
     *
     * @param int $subscriptionId
     * @return LogInterface
     */
    public function setSubscriptionId($subscriptionId);

    /**
     * Set subscription log is associated to.
     *
     * @param Subscription $subscription
     * @return $this
     */
    public function setSubscription(Subscription $subscription);

    /**
     * Set subscription status.
     *
     * @param string $newStatus
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setStatus($newStatus);

    /**
     * Get subscription status.
     *
     * @return string $this
     */
    public function getStatus();

    /**
     * Set associated order increment ID.
     *
     * @param string $orderIncrementId
     * @return $this
     */
    public function setOrderIncrementId($orderIncrementId);

    /**
     * Get associated order increment ID.
     *
     * @return string
     */
    public function getOrderIncrementId();

    /**
     * Set associated order ID. Used for link purposes (going from log to order).
     *
     * @param string $orderId
     * @return $this
     */
    public function setOrderId($orderId);

    /**
     * Get associated order ID.
     *
     * @return string
     */
    public function getOrderId();

    /**
     * Set log message.
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Get log message.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set ID of agent responsible for the logged action. admin user_id, or -1 for customer.
     *
     * @param int $agentId
     * @return $this
     */
    public function setAgentId($agentId);

    /**
     * Get ID of agent responsible for the logged action. admin user_id, or -1 for customer.
     *
     * @return int
     */
    public function getAgentId();

    /**
     * Set created-at date
     *
     * @param $createdAt
     * @return LogInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get created-at date.
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Get additional information.
     *
     * If $key is set, will return that value or null; otherwise, will return an array of all additional date.
     *
     * @param string|null $key
     * @return mixed|null
     */
    public function getAdditionalInformation($key = null);

    /**
     * Set additional information.
     *
     * Can pass in a key-value pair to set one value, or a single parameter (associative array) to overwrite all data.
     *
     * @param string|array $key
     * @param string|null $value
     * @return LogInterface
     */
    public function setAdditionalInformation($key, $value = null);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\LogExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \ParadoxLabs\Subscriptions\Api\Data\LogExtensionInterface $extensionAttributes
    );
}
