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
 * Interval interface
 *
 * Record data and associations between product subscription options and specific custom option values.
 *
 * @api
 */
interface ProductIntervalInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Identifier setter
     *
     * @param int $value
     * @return ProductIntervalInterface
     */
    public function setId($value);

    /**
     * Identifier getter
     *
     * @return int
     */
    public function getId();

    /**
     * Get product ID
     *
     * @return int
     */
    public function getProductId();

    /**
     * Set product ID
     *
     * @param int $productId
     * @return ProductIntervalInterface
     */
    public function setProductId($productId);

    /**
     * Get option ID
     *
     * @return int
     */
    public function getOptionId();

    /**
     * Set option ID
     *
     * @param int $optionId
     * @return ProductIntervalInterface
     */
    public function setOptionId($optionId);

    /**
     * Get value ID
     *
     * @return int
     */
    public function getValueId();

    /**
     * Set value ID
     *
     * @param int $valueId
     * @return ProductIntervalInterface
     */
    public function setValueId($valueId);

    /**
     * Get store ID
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Set store ID
     *
     * @param int $storeId
     * @return ProductIntervalInterface
     */
    public function setStoreId($storeId);

    /**
     * Get frequency count
     *
     * @return int
     */
    public function getFrequencyCount();

    /**
     * Set frequency count
     *
     * @param int $frequencyCount
     * @return ProductIntervalInterface
     */
    public function setFrequencyCount($frequencyCount);

    /**
     * Get frequency unit
     *
     * @return string
     */
    public function getFrequencyUnit();

    /**
     * Set frequency unit
     *
     * @param string $frequencyUnit
     * @return ProductIntervalInterface
     */
    public function setFrequencyUnit($frequencyUnit);

    /**
     * Get length
     *
     * @return int
     */
    public function getLength();

    /**
     * Set length
     *
     * @param int $length
     * @return ProductIntervalInterface
     */
    public function setLength($length);

    /**
     * Get installment price
     *
     * @return float
     */
    public function getInstallmentPrice();

    /**
     * Set installment price
     *
     * @param float $installmentPrice
     * @return ProductIntervalInterface
     */
    public function setInstallmentPrice($installmentPrice);

    /**
     * Get adjustment price
     *
     * @return float
     */
    public function getAdjustmentPrice();

    /**
     * Set adjustment price
     *
     * @param float $adjustmentPrice
     * @return ProductIntervalInterface
     */
    public function setAdjustmentPrice($adjustmentPrice);

    /**
     * Get created-at date.
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set created-at date.
     *
     * @param string $createdAt
     * @return ProductIntervalInterface
     */
    public function setCreatedAt($createdAt);

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
     * @return ProductIntervalInterface
     */
    public function setAdditionalInformation($key, $value = null);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalExtensionInterface $extensionAttributes
     * @return ProductIntervalInterface
     */
    public function setExtensionAttributes(
        \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalExtensionInterface $extensionAttributes
    );
}
