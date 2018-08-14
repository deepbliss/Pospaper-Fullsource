<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Source\Calculation;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class PointsEarning
 *
 * @package Aheadworks\RewardPoints\Model\Source\Calculation
 */
class PointsEarning implements ArrayInterface
{
    /**#@+
     * Points earning values
     */
    const BEFORE_TAX = 1;
    const AFTER_TAX = 2;
    /**#@-*/

    /**
     *  {@inheritDoc}
     */
    public function toOptionArray()
    {
        return [
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
