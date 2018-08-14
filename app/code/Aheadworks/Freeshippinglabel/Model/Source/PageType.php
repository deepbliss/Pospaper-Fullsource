<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model\Source;

/**
 * Class PageType
 *
 * @package Aheadworks\Freeshippinglabel\Model\Source
 */
class PageType implements \Magento\Framework\Data\OptionSourceInterface
{
    /**#@+
     * Page type values
     */
    const ALL_PAGES = 'all';
    const HOME_PAGE = 'home';
    const CATEGORY_PAGES = 'category';
    const PRODUCT_PAGES = 'product';
    const SHOPPING_CART = 'cart';
    const CHECKOUT = 'checkout';
    /**#@-*/

    /**
     * @return array
     */
    public function getOptionArray()
    {
        $optionArray = [];
        foreach ($this->toOptionArray() as $option) {
            $optionArray[$option['value']] = $option['label'];
        }
        return $optionArray;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ALL_PAGES,  'label' => __('All Pages')],
            ['value' => self::HOME_PAGE,  'label' => __('Home Page')],
            ['value' => self::CATEGORY_PAGES,  'label' => __('Catalog Pages')],
            ['value' => self::PRODUCT_PAGES,  'label' => __('Product Pages')],
            ['value' => self::SHOPPING_CART,  'label' => __('Shopping cart')],
            ['value' => self::CHECKOUT,  'label' => __('Checkout')]
        ];
    }

    /**
     * @param string $actionName
     * @return string
     */
    public static function getTypeByActionName($actionName)
    {
        $pageType = '';
        switch ($actionName) {
            case 'cms_index_index':
                $pageType = self::HOME_PAGE;
                break;
            case 'catalog_category_view':
                $pageType = self::CATEGORY_PAGES;
                break;
            case 'catalog_product_view':
                $pageType = self::PRODUCT_PAGES;
                break;
            case 'checkout_cart_index':
                $pageType = self::SHOPPING_CART;
                break;
            case 'checkout_index_index':
                $pageType = self::CHECKOUT;
                break;
        }

        return $pageType;
    }
}
