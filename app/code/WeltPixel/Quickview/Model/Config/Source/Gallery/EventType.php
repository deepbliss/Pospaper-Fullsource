<?php

namespace WeltPixel\Quickview\Model\Config\Source\Gallery;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class EventType
 *
 * @package WeltPixel\Quickview\Model\Config\Source\Gallery
 */
class EventType implements ArrayInterface
{
    /**
     * Return list of EventType Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'hover',
                'label' => __('Hover')
            ),
            array(
                'value' => 'click',
                'label' => __('Click')
            )
        );
    }
}