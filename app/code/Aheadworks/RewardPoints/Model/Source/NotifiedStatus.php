<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class NotifiedStatus
 *
 * @package Aheadworks\RewardPoints\Model\Source
 */
class NotifiedStatus implements ArrayInterface
{
    /**#@+
     * Notified status values
     */
    const YES = 1;
    const NO = 2;
    const NOT_SUBSCRIBED = 3;
    const WAITING = 4;
    const CANCELLED = 5;
    /**#@-*/

    /**
     *  {@inheritDoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::YES,
                'label' => __('Yes')
            ],
            [
                'value' => self::NO,
                'label' => __('No')
            ],
            [
                'value' => self::NOT_SUBSCRIBED,
                'label' => __('Not Subscribed')
            ],
            [
                'value' => self::WAITING,
                'label' => __('Waiting')
            ],
            [
                'value' => self::CANCELLED,
                'label' => __('Cancelled')
            ]
        ];
    }
}
