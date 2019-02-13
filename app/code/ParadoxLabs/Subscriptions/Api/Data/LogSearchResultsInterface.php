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
 * Interface for log search results.
 *
 * @api
 */
interface LogSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get subscriptions.
     *
     * @return \ParadoxLabs\Subscriptions\Api\Data\LogInterface[]
     */
    public function getItems();

    /**
     * Set subscriptions.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\LogInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
