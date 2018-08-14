<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Source\Transaction;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 *
 * @package Aheadworks\RewardPoints\Model\Source
 */
class Status implements ArrayInterface
{
    /**#@+
     * Entity type values
     */
    const ACTIVE = 1;
    const USED = 2;
    const EXPIRED = 3;
    /**#@-*/

    /**
     * {@inheritDoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::ACTIVE,
                'label' => __('Active')
            ],
            [
                'value' => self::USED,
                'label' => __('Used')
            ],
            [
                'value' => self::EXPIRED,
                'label' => __('Expired')
            ]
        ];
    }
}
