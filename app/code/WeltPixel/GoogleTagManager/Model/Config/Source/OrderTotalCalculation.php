<?php

namespace WeltPixel\GoogleTagManager\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class OrderTotalCalculation
 *
 * @package WeltPixel\GoogleTagManager\Model\Config\Source
 */
class OrderTotalCalculation implements ArrayInterface
{

    const CALCULATE_SUBTOTAL = 'subtotal';
    const CALCULATE_GRANDTOTAL = 'grandtotal';

    /**
     * Return list of Id Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => self::CALCULATE_SUBTOTAL,
                'label' => __('Subtotal')
            ),
            array(
                'value' => self::CALCULATE_GRANDTOTAL,
                'label' => __('Grandtotal')
            )
        );
    }
}