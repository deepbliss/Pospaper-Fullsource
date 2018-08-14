<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model\Source;

/**
 * Class Position
 *
 * @package Aheadworks\Freeshippinglabel\Model\Source
 */
class Position implements \Magento\Framework\Data\OptionSourceInterface
{
    /**#@+
     * Position values
     */
    const PAGE_TOP = 'aw_fslabel_page_top';
    const PAGE_TOP_FIXED = 'aw_fslabel_page_top_fixed';
    const PAGE_BOTTOM = 'aw_fslabel_page_bottom';
    const PAGE_BOTTOM_FIXED = 'aw_fslabel_page_bottom_fixed';
    const CONTENT_TOP = 'aw_fslabel_content_top';
    const CONTENT_BOTTOM = 'aw_fslabel_content_bottom';
    /**#@-*/

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::PAGE_TOP,  'label' => __('Page Top')],
            ['value' => self::PAGE_TOP_FIXED,  'label' => __('Page top, fixed (sticky header)')],
            ['value' => self::PAGE_BOTTOM,  'label' => __('Page bottom')],
            ['value' => self::PAGE_BOTTOM_FIXED,  'label' => __('Page bottom, fixed (sticky footer)')],
            ['value' => self::CONTENT_TOP,  'label' => __('Content top')],
            ['value' => self::CONTENT_BOTTOM,  'label' => __('Content bottom')]
        ];
    }
}
