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
namespace Magestore\Megamenu\Helper;

/**
 * Helper Data
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $context->getScopeConfig();
    }

    /**
     * get Megamenu type
     * @return array
     */
    public function getMegamenutypeOptions() {
        return [
            [
                'label' => 'Top Menu',
                'value' => 0
            ],
            [
                'label' => 'Left Menu',
                'value' => 1
            ],

        ];
    }

    /**
     * Megamenu type to Option
     * @return array
     */
    public function megamenuTypeToOptionArray() {
        $result = [];
        $array = $this->getMegamenutypeOptions();
        foreach ($array as $item) {
            $result[$item['value']] = $item['label'];
        }
        return $result;
    }
    /**
     * get menu type
     * @return menu type array
     */
    public function getMenutypeOptions() {
        return [
            [
                'label' => 'Anchor Text',
                'value' => \Magestore\Megamenu\Model\Megamenu::ANCHOR_TEXT
            ],
            [
                'label' => 'Default Category Listing',
                'value' => \Magestore\Megamenu\Model\Megamenu::CATEGORY_LEVEL
            ],
            [
                'label' => 'Static Category Listing',
                'value' => \Magestore\Megamenu\Model\Megamenu::CATEGORY_LISTING
            ],
            [
                'label' => 'Dynamic Category Listing',
                'value' => \Magestore\Megamenu\Model\Megamenu::CATEGORY_DYNAMIC
            ],
            [
                'label' => 'Products Listing',
                'value' => \Magestore\Megamenu\Model\Megamenu::PRODUCT_LISTING
            ],
            [
                'label' => 'Products Grid',
                'value' => \Magestore\Megamenu\Model\Megamenu::PRODUCT_GRID
            ],
            [
                'label' => 'Dynamic products listing by category',
                'value' => \Magestore\Megamenu\Model\Megamenu::PRODUCT_BY_CATEGORY_FILTER
            ],
            [
                'label' => 'Content',
                'value' => \Magestore\Megamenu\Model\Megamenu::CONTENT_ONLY
            ],
        ];
    }
    /**
     * get menu type options for grid menu item
     * @return menu type options
     */
    public function menuTypeToOptionArray() {
        $result = [];
        $array = $this->getMenutypeOptions();
        foreach ($array as $item) {
            $result[$item['value']] = $item['label'];
        }
        return $result;
    }
    /**
     * Get store config
     * @param mixed $store
     * @return mixed
     */
    public function getConfig($key, $store = null) {
        return $this->_scopeConfig->getValue(
             $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
