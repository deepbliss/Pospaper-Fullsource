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

namespace ParadoxLabs\Subscriptions\Plugin\Catalog\Model\Product\Copier;

/**
 * Plugin Class
 */
class Plugin
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\IntervalDuplicator
     */
    protected $intervalDuplicator;

    /**
     * Plugin constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Service\IntervalDuplicator $intervalDuplicator
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Service\IntervalDuplicator $intervalDuplicator
    ) {
        $this->intervalDuplicator = $intervalDuplicator;
    }

    /**
     * When duplicating a product, duplicate any intervals we have associated with that product as well.
     *
     * @param \Magento\Catalog\Model\Product\Copier $copier
     * @param \Magento\Catalog\Model\Product $newProduct
     * @return \Magento\Catalog\Model\Product
     */
    public function afterCopy(
        \Magento\Catalog\Model\Product\Copier $copier,
        \Magento\Catalog\Model\Product $newProduct
    ) {
        $this->intervalDuplicator->duplicateProductIntervals($newProduct);

        return $newProduct;
    }
}
