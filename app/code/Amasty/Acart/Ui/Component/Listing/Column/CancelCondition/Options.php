<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Amasty\Acart\Ui\Component\Listing\Column\CancelCondition;

use Magento\Framework\Data\OptionSourceInterface;
use Amasty\Acart\Model\Rule as Rule;

/**
 * Class Options
 */
class Options implements OptionSourceInterface
{
    public function toArray()
    {
        return [
            Rule::CANCEL_CONDITION_CLICKED => __("Link from Email Clicked"),
            Rule::CANCEL_CONDITION_ANY_PRODUCT_WENT_OUT_OF_STOCK => __("Any product went out of stock"),
            Rule::CANCEL_CONDITION_ALL_PRODUCTS_WENT_OUT_OF_STOCK => __("All products went out of stock")
        ];
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];
        $arr = $this->toArray();
        foreach ($arr as $value => $label) {
            $optionArray[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $optionArray;
    }
}
