<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Model\Config;

/**
 * System > Configuration > Simple Google Shopping dropdown url componenet
 */
class UrlRewrite implements \Magento\Framework\Option\ArrayInterface
{

    const PRODUCT_URL = 1;
    const SHORTEST_URL = 2;
    const LONGEST_URL = 3;

    /**
     * Get values
     * @return array
     */
    public function getValues()
    {
        return [
            self::PRODUCT_URL => __('Individual product urls'),
            self::SHORTEST_URL => __('Shortest category urls'),
            self::LONGEST_URL => __('Longest category urls')
        ];
    }

    /**
     * Get Options
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getValues();
    }
}
