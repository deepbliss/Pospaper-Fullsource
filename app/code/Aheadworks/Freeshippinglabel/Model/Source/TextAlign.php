<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model\Source;

/**
 * Class TextAlign
 *
 * @package Aheadworks\Freeshippinglabel\Model\Source
 */
class TextAlign implements \Magento\Framework\Data\OptionSourceInterface
{
    /**#@+
     * Text align types
     */
    const LEFT = 'left';
    const CENTER = 'center';
    const RIGHT = 'right';
    /**#@-*/

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::LEFT,  'label' => __('Left')],
            ['value' => self::CENTER,  'label' => __('Center')],
            ['value' => self::RIGHT,  'label' => __('Right')]
        ];
    }
}
