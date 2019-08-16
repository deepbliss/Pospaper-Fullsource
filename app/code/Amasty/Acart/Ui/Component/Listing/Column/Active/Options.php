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

namespace Amasty\Acart\Ui\Component\Listing\Column\Active;

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
            RULE::RULE_ACTIVE => __("Active"),
            RULE::RULE_INACTIVE => __("Inactive"),
        ];
    }

    public function toOptionArray()
    {
        return [
            [
                'value' => RULE::RULE_ACTIVE,
                'label' => __("Active")
            ],
            [
                'value' => RULE::RULE_INACTIVE,
                'label' => __("Inactive")
            ],
        ];
    }
}
