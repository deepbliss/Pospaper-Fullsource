<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Megamenu
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Megamenu\Model;

/**
 * Model Status
 */
class Status
{
    const STATUS_ENABLED = 1;

    const STATUS_DISABLED = 2;


    /**
     * Get available statuses.
     *
     * @return void
     */
    public static function getAvailableStatuses()
    {
        return [
            self::STATUS_ENABLED => __('Enabled'),
            self::STATUS_DISABLED => __('Disabled')
        ];
    }

    public static function getSubmenualignOptions() {
        return [
            [
                'label' => 'From left menu',
                'value' => 0
            ],
            [
                'label' => 'From right menu',
                'value' => 1
            ],
            [
                'label' => 'From left item',
                'value' => 2
            ],
            [
                'label' => 'From right item',
                'value' => 3
            ],
        ];
    }

    public static function getMenutypeOptions() {
        return [
            [
                'label' => 'Anchor Text',
                'value' => self::ANCHOR_TEXT
            ],
            [
                'label' => 'Default Category Listing',
                'value' => self::CATEGORY_LEVEL
            ],
            [
                'label' => 'Static Category Listing',
                'value' => self::CATEGORY_LISTING
            ],
            [
                'label' => 'Dynamic Category Listing',
                'value' => self::CATEGORY_DYNAMIC
            ],
           [
                'label' => 'Product Listing',
                'value' => self::PRODUCT_LISTING
            ],
            [
                'label' => 'Product Grid',
                'value' => self::PRODUCT_GRID
            ],
           [
                'label' => 'Dynamic products listing by category',
                'value' => self::PRODUCT_BY_CATEGORY_FILTER
            ],
            [
                'label' => 'Content',
                'value' => self::CONTENT_ONLY
            ],
        ];
    }

}
