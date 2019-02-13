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
 * CustomOptionManager Class
 *
 * Note: Likely to be marked API in a future release, but not yet.
 */
interface CustomOptionManagerInterface
{
    /**
     * Generate a fresh custom option for the current subscription settings.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface
     */
    public function generateSubscriptionOption(\Magento\Catalog\Api\Data\ProductInterface $product);

    /**
     * Create/update values on the given existing subscription custom option.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface $option
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface
     */
    public function generateSubscriptionOptionValues(
        \Magento\Catalog\Api\Data\ProductInterface $product,
        \Magento\Catalog\Api\Data\ProductCustomOptionInterface $option
    );

    /**
     * Find existing subscription custom option, if any.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface|null
     */
    public function getSubscriptionOption(\Magento\Catalog\Api\Data\ProductInterface $product);

    /**
     * Check if we are allowed to skip a single option, and if so, if that's all the current product has.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return bool
     */
    public function skipSingleOption(\Magento\Catalog\Api\Data\ProductInterface $product);

    /**
     * Create, update, or remove (as appropriate) the subscription custom option for the given product.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function updateProductCustomOption(\Magento\Catalog\Api\Data\ProductInterface $product);
}
