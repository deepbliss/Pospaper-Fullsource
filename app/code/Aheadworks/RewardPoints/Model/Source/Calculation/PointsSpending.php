<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Source\Calculation;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class PointsSpending
 *
 * @package Aheadworks\RewardPoints\Model\Source\Calculation
 */
class PointsSpending implements ArrayInterface
{
    /**#@+
     * Points spending values
     */
    const DEFAULT_TAX = 1;
    const BEFORE_TAX = 2;
    const AFTER_TAX = 3;
    /**#@-*/

    /**
     *  {@inheritDoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::DEFAULT_TAX,
                'label' => __('Default')
            ],
            [
                'value' => self::BEFORE_TAX,
                'label' => __('Before Tax')
            ],
            [
                'value' => self::AFTER_TAX,
                'label' => __('After Tax')
            ]
        ];
    }
}
