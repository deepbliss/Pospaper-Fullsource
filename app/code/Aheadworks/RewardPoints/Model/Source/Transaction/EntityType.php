<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Source\Transaction;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class EntityType
 *
 * @package Aheadworks\RewardPoints\Model\Source
 */
class EntityType implements ArrayInterface
{
    /**#@+
     * Entity type values
     */
    const ORDER_ID = 1;
    const CREDIT_MEMO_ID = 2;
    const TRANSACTION_ID = 3;
    /**#@-*/

    /**
     * {@inheritDoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::ORDER_ID,
                'label' => __('Order Id')
            ],
            [
                'value' => self::CREDIT_MEMO_ID,
                'label' => __('Credit Memo Id')
            ],
            [
                'value' => self::TRANSACTION_ID,
                'label' => __('Transaction Id')
            ]
        ];
    }

    /**
     * Retrieve entity types
     *
     * @return array
     */
    public function getEntityTypes()
    {
        return [
            self::ORDER_ID,
            self::CREDIT_MEMO_ID,
            self::TRANSACTION_ID
        ];
    }
}
