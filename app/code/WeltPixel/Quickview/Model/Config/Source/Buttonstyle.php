<?php

namespace WeltPixel\Quickview\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Buttonstyle
 *
 * @package WeltPixel\Quickview\Model\Config\Source
 */
class Buttonstyle implements ArrayInterface
{

    /**
     * Return list of Buttonstyle Style Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'v1',
                'label' => 'Version 1',
            ),
            array(
                'value' => 'v2',
                'label' => 'Version 2',
            )
        );
    }
}