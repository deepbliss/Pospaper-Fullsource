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

namespace ParadoxLabs\Subscriptions\Api;

/**
 * IntervalManager Class
 *
 * Note: Likely to be marked API in a future release, but not yet.
 */
interface IntervalManagerInterface
{
    /**
     * Map of attribute codes to interval columns
     */
    const ATTRIBUTE_INTERVAL_MAP = [
        'subscription_intervals' => 'frequency_count',
        'subscription_unit' => 'frequency_unit',
        'subscription_length' => 'length',
        'subscription_price' => 'installment_price',
        'subscription_init_adjustment' => 'adjustment_price',
    ];

    /**
     * Add/remove intervals to match subscription option values for the given product.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return IntervalManagerInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function updateProductIntervals(\Magento\Catalog\Api\Data\ProductInterface $product);

    /**
     * Remove all stored intervals for the given product.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return IntervalManagerInterface
     */
    public function removeProductIntervals(\Magento\Catalog\Api\Data\ProductInterface $product);

    /**
     * Find the given product's subscription custom option, if any.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param array $intervals
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface|null
     */
    public function getSubscriptionOption(\Magento\Catalog\Api\Data\ProductInterface $product, $intervals = null);

    /**
     * Get intervals for the given product (if any).
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface[]
     */
    public function getProductIntervals(\Magento\Catalog\Api\Data\ProductInterface $product);

    /**
     * Create an Interval model from the given data.
     *
     * @param array $data
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface
     */
    public function createIntervalModel(array $data);

    /**
     * Save an interval record for the given option and value (if possible).
     *
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface $option
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionValuesInterface|array $value
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveIntervalForOptionValue(\Magento\Catalog\Api\Data\ProductCustomOptionInterface $option, $value);

    /**
     * Check defaults -- set values in place of interval nulls.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param array $intervalData
     * @return array
     */
    public function hydrateIntervalData(
        \Magento\Catalog\Api\Data\ProductInterface $product,
        array $intervalData
    );

    /**
     * Check defaults -- remove matches from interval values.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param array $intervalData
     * @return array
     */
    public function dehydrateIntervalData(
        \Magento\Catalog\Api\Data\ProductInterface $product,
        array $intervalData
    );

    /**
     * Set interval values on product attributes. Used for single no-option subscription saving.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function hydrateProduct(
        \Magento\Catalog\Api\Data\ProductInterface $product,
        \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval
    );

    /**
     * Get relations between product attributes and interval columns.
     *
     * @return array
     */
    public function getAttributeIntervalMap();

    /**
     * Determine whether the given product is eligible for a subscription intervals grid.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return bool
     */
    public function isProductIntervalGridEligible(\Magento\Catalog\Api\Data\ProductInterface $product);
}
