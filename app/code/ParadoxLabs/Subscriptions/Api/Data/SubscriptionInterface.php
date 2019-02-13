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

/**
 * Subscription data storage and processing
 */
interface SubscriptionInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Get subscription ID.
     *
     * @return int
     */
    public function getId();

    /**
     * Set subscription ID.
     *
     * @param int $subscriptionId
     * @return SubscriptionInterface
     */
    public function setId($subscriptionId);

    /**
     * Get subscription increment ID.
     *
     * @return string|int
     */
    public function getIncrementId();

    /**
     * Set subscription increment ID.
     *
     * @param string|int $incrementId
     * @return SubscriptionInterface
     */
    public function setIncrementId($incrementId);

    /**
     * Set source quote
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return SubscriptionInterface
     */
    public function setQuote(\Magento\Quote\Api\Data\CartInterface $quote);

    /**
     * Set source quote ID
     *
     * @param int|null $quoteId
     * @return SubscriptionInterface
     */
    public function setQuoteId($quoteId);

    /**
     * Get source quote ID
     *
     * @return int
     */
    public function getQuoteId();

    /**
     * Set subscription frequency count
     *
     * @param int $frequencyCount
     * @return SubscriptionInterface
     */
    public function setFrequencyCount($frequencyCount);

    /**
     * Set subscription frequency unit
     *
     * @param string $frequencyUnit
     * @return SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setFrequencyUnit($frequencyUnit);

    /**
     * Set subscription length (number of billings to last for)
     *
     * @param int $length
     * @return SubscriptionInterface
     */
    public function setLength($length);

    /**
     * Set subscription description. This will typically (but not necessarily) be the item name.
     *
     * @param string $description
     * @return SubscriptionInterface
     */
    public function setDescription($description);

    /**
     * Get subscription description. This will typically (but not necessarily) be the item name.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Associate a given order with the subscription, and record the transaction details.
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @param string|null $message
     * @return SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function recordBilling(\Magento\Sales\Api\Data\OrderInterface $order, $message = null);

    /**
     * Set subscription status.
     *
     * @param string $newStatus
     * @param string $message Message to log for the change (optional)
     * @return SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setStatus($newStatus, $message = null);

    /**
     * Calculate and set next run date for the subscription.
     *
     * @return SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function calculateNextRun();

    /**
     * Set subscription customer ID
     *
     * @param $customerId
     * @return SubscriptionInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get subscription customer ID
     *
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Get created-at date.
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set created-at date
     *
     * @param $createdAt
     * @return SubscriptionInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated-at date.
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * Set updated-at date
     *
     * @param $updatedAt
     * @return SubscriptionInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Increment run_count by one.
     *
     * @return SubscriptionInterface
     */
    public function incrementRunCount();

    /**
     * Get subscription store ID.
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Set subscription store ID
     *
     * @param int $storeId
     * @return SubscriptionInterface
     */
    public function setStoreId($storeId);

    /**
     * Get next-run date.
     *
     * @return string
     */
    public function getNextRun();

    /**
     * Set the next run date for the subscription.
     *
     * @param string|int $nextRun Next run date (date or timestamp) IN UTC
     * @return SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setNextRun($nextRun);

    /**
     * Get last-run date.
     *
     * @return string
     */
    public function getLastRun();

    /**
     * Set the last run date for the subscription.
     *
     * @param string|int $lastRun Last run date (date or timestamp) IN UTC
     * @return SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setLastRun($lastRun);

    /**
     * Get last-notified date.
     *
     * @return string
     */
    public function getLastNotified();

    /**
     * Set the last notified date for the subscription.
     *
     * @param string|int $lastNotified Last notified date (date or timestamp) IN UTC
     * @return SubscriptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setLastNotified($lastNotified);

    /**
     * Get subscription subtotal.
     *
     * @return float
     */
    public function getSubtotal();

    /**
     * Set subscription subtotal. Purely for reference purposes.
     *
     * @param float $subtotal
     * @return SubscriptionInterface
     */
    public function setSubtotal($subtotal);

    /**
     * Check whether subscription has billed to the prescribed length.
     *
     * @return bool
     */
    public function isComplete();

    /**
     * Get subscription length.
     *
     * @return int
     */
    public function getLength();

    /**
     * Get number of times the subscription has run.
     *
     * @return int
     */
    public function getRunCount();

    /**
     * Set number of times the subscription has run.
     *
     * @param int $runCount
     * @return SubscriptionInterface
     */
    public function setRunCount($runCount);

    /**
     * Get subscription status.
     *
     * @return string
     */
    public function getStatus();

    /**
     * Set last_run to the current datetime.
     *
     * @return SubscriptionInterface
     */
    public function updateLastRunTime();

    /**
     * Get subscription frequency count
     *
     * @return int
     */
    public function getFrequencyCount();

    /**
     * Get subscription frequency unit
     *
     * @return string
     */
    public function getFrequencyUnit();

    /**
     * Add a new log to the subscription.
     *
     * @param string $message
     * @param string $incrementId
     * @param string $orderId
     * @return SubscriptionInterface
     */
    public function addLog($message, $incrementId = null, $orderId = null);

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
     * @return SubscriptionInterface
     */
    public function setAdditionalInformation($key, $value = null);

    /**
     * Get data for admin keyword search.
     *
     * @return string
     */
    public function getKeywordFulltext();

    /**
     * Set data for admin keyword search.
     *
     * @param string $data
     * @return SubscriptionInterface
     */
    public function setKeywordFulltext($data);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionExtensionInterface $extensionAttributes
    );
}
