<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model\Source;

/**
 * Class FontWeight
 *
 * @package Aheadworks\Freeshippinglabel\Model\Source
 */
class FontWeight implements \Magento\Framework\Data\OptionSourceInterface
{
    /**#@+
     * Font weight values
     */
    const THIN = 100;
    const LIGHT = 300;
    const REGULAR = 400;
    const MEDIUM = 500;
    const BOLD = 700;
    const BLACK = 900;
    /**#@-*/

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::THIN,  'label' => __('Thin, 100')],
            ['value' => self::LIGHT,  'label' => __('Light, 300')],
            ['value' => self::REGULAR,  'label' => __('Regular, 400')],
            ['value' => self::MEDIUM,  'label' => __('Medium, 500')],
            ['value' => self::BOLD,  'label' => __('Bold, 700')],
            ['value' => self::BLACK,  'label' => __('Black, 900')]
        ];
    }
}
