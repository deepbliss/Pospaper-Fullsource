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

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for card search results.
 *
 * @api
 */
interface ProductIntervalSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get cards.
     *
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface[]
     */
    public function getItems();

    /**
     * Set cards.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
