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

namespace ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription;

/**
 * UiCollection Class
 */
class UiCollection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    /**
     * Init collection select
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        // Join the quote table for all that good stuff
        $this->join(
            [
                'quote' => $this->getTable('quote')
            ],
            'quote.entity_id=main_table.quote_id',
            [
                'items_count',
                'items_qty',
                'customer_group_id',
                'customer_email',
                'customer_firstname',
                'customer_lastname',
                'base_currency_code' => 'quote_currency_code',
            ]
        );
        
        // Map fields to avoid ambiguous column errors on filtering
        $this->addFilterToMap(
            'entity_id',
            'main_table.entity_id'
        );

        $this->addFilterToMap(
            'created_at',
            'main_table.created_at'
        );
        
        $this->addFilterToMap(
            'subtotal',
            'main_table.subtotal'
        );

        return $this;
    }
}
