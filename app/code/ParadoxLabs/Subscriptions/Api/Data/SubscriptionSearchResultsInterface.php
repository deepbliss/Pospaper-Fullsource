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
 * Interface for subscription search results.
 *
 * @api
 */
interface SubscriptionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get subscriptions.
     *
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface[]
     */
    public function getItems();

    /**
     * Set subscriptions.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
