<?php

namespace WeltPixel\GoogleTagManager\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class ParentVsChild
 *
 * @package WeltPixel\GoogleTagManager\Model\Config\Source
 */
class ParentVsChild implements ArrayInterface
{

    const CHILD = 'child';
    const PARENT = 'parent';

    /**
     * Return list of Id Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => self::CHILD,
                'label' => __('Child')
            ),
            array(
                'value' => self::PARENT,
                'label' => __('Parent')
            )
        );
    }
}