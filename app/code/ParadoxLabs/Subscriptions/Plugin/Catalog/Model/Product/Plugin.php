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

namespace ParadoxLabs\Subscriptions\Plugin\Catalog\Model\Product;

/**
 * \Magento\Catalog\Model\Product plugin
 */
class Plugin
{
    /**
     * @var \ParadoxLabs\Subscriptions\Api\CustomOptionManagerInterface
     */
    protected $customOptionManager;

    /**
     * Product plugin constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Api\CustomOptionManagerInterface $customOptionManager
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Api\CustomOptionManagerInterface $customOptionManager
    ) {
        $this->customOptionManager = $customOptionManager;
    }

    /**
     * Before product save, inject subscription custom option changes (if any).
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return void
     */
    public function beforeBeforeSave(
        \Magento\Catalog\Model\Product $product
    ) {
        // Skip if duplicating existing product.
        if ($product->getData('is_duplicate')) {
            return;
        }

        $this->customOptionManager->updateProductCustomOption($product);
    }
}
