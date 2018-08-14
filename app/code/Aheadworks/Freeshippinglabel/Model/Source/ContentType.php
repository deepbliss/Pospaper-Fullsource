<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model\Source;

/**
 * Class ContentType
 *
 * @package Aheadworks\Freeshippinglabel\Model\Source
 */
class ContentType implements \Magento\Framework\Data\OptionSourceInterface
{
    /**#@+
     * Message type values
     */
    const EMPTY_CART = 'empty_cart';
    const NOT_EMPTY_CART = 'not_empty_cart';
    const GOAL_REACHED = 'goal_reached';
    /**#@-*/

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::EMPTY_CART,  'label' => __('Content when cart is empty')],
            ['value' => self::NOT_EMPTY_CART,  'label' => __('Content when cart is not empty')],
            ['value' => self::GOAL_REACHED,  'label' => __('Content when goal is reached')],
        ];
    }
}
