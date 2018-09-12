<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model\Source;

class NotificationType implements \Magento\Framework\Option\ArrayInterface
{
    const GENERAL = 'INFO';
    const SPECIAL_DEALS = 'PROMO';
    const AVAILABLE_UPDATE = 'INSTALLED_UPDATE';
    const UNSUBSCRIBE_ALL = 'UNSUBSCRIBE_ALL';
    const TIPS_TRICKS = 'TIPS_TRICKS';

    public function toOptionArray()
    {
        $types = [
            [
                'value' => self::GENERAL,
                'label' => __('General Info')
            ],
            [
                'value' => self::SPECIAL_DEALS,
                'label' => __('Special Deals')
            ],
            [
                'value' => self::AVAILABLE_UPDATE,
                'label' => __('Available Updates')
            ],
            [
                'value' => self::TIPS_TRICKS,
                'label' => __('Magento Tips & Tricks')
            ],
            [
                'value' => self::UNSUBSCRIBE_ALL,
                'label' => __('Unsubscribe from all')
            ]
        ];

        return $types;
    }
}
