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

namespace ParadoxLabs\Subscriptions\Model\ResourceModel\Log;

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

        // Join the subscription table
        $this->join(
            [
                'paradoxlabs_subscription' => $this->getTable('paradoxlabs_subscription')
            ],
            'paradoxlabs_subscription.entity_id=main_table.subscription_id',
            [
                'name' => 'description',
                'store_id',
                'increment_id',
            ]
        );
        
        // Map fields to avoid ambiguous column errors on filtering
        $this->addFilterToMap(
            'name',
            'paradoxlabs_subscription.description'
        );
        
        $this->addFilterToMap(
            'description',
            'main_table.description'
        );
        
        $this->addFilterToMap(
            'status',
            'main_table.status'
        );
        
        $this->addFilterToMap(
            'created_at',
            'main_table.created_at'
        );

        return $this;
    }
}
