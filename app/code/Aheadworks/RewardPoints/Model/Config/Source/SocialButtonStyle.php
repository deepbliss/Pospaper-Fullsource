<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Config\Source;

/**
 * Class Aheadworks\RewardPoints\Model\Config\Source\SocialButtonStyle
 */
class SocialButtonStyle implements \Magento\Framework\Option\ArrayInterface
{
    /**#@+
     * Social Button Style
     */
    const ICONS_ONLY_STYLE = 'icons_only';
    const ICONS_WITH_COUNTER_V_STYLE = 'icons_with_counter_v';
    const ICONS_WITH_COUNTER_H_STYLE = 'icons_with_counter_h';
    /**#@-*/

    /**
     * Return array of options as value-label pairs
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            self::ICONS_ONLY_STYLE => __('Icons Only'),
            self::ICONS_WITH_COUNTER_V_STYLE => __('Icons with Counter (vertical)'),
            self::ICONS_WITH_COUNTER_H_STYLE => __('Icons with Counter (horizontal)'),
        ];
    }
}
