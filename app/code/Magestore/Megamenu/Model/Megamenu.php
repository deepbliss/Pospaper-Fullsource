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
 * Model Megamenu
 */
class Megamenu extends \Magento\Framework\Model\AbstractModel
{
    const TOP_MENU = 0;

    const LEFT_MENU = 1;

    const NO_RESPONSIVE = 0;

    const RESPONSIVE = 1;

    const MOBILE_SLIDE = 0;

    const MOBILE_BLIND = 1;

    const MEGAMENU_ICON_PATH = 'magestore/megamenu/images/megamenu/icon';

    const XML_PATH_THEME_ID = 'design/theme/theme_id';

    const CONTENT_ONLY = 1;

    const PRODUCT_LISTING = 2;

    const CATEGORY_LISTING = 3;

    const ANCHOR_TEXT = 4;

    const PRODUCT_GRID = 5;

    const CATEGORY_LEVEL = 6;

    const CATEGORY_DYNAMIC = 7;

    const PRODUCT_BY_CATEGORY_FILTER = 8;

    const FEATURED_NONE = 0;

    const FEATURED_PRODUCTS = 1;

    const FEATURED_CATEGORIES = 2;

    const FEATURED_CONTENT = 3;

    /**
     * @var \Magento\Framework\View\DesignInterface
     */
    protected $_designInterface;
    /**
     * @var \Magestore\Megamenu\Helper\Data
     */
    protected $_megaHelper;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_configResoure;
    /**
     * Parent layout of the block
     *
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;

    /**
     * Model constructor
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magestore\Megamenu\Helper\Data $megaHelper,
        \Magento\Framework\View\DesignInterface $designInterface,
        \Magento\Framework\View\LayoutInterface $layoutInterface,
        \Magento\Config\Model\ResourceModel\Config $configResoure,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,

        array $data = array()
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_storeManager = $storeManager;
        $this->_objectManager = $objectManager;
        $this->_layout = $layoutInterface;
        $this->_megaHelper = $megaHelper;
        $this->_designInterface = $designInterface;
        $this->_configResoure = $configResoure;
    }

    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magestore\Megamenu\Model\ResourceModel\Megamenu');
    }

    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * get Menu type
     * @return array
     */
    public function getMenutypeOptions()
    {
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

    /**
     * get Category type
     * @return array
     */
    public function getCategorytypeOptions()
    {
        return [
            [
                'label' => 'List all items of each category in one column',
                'value' => 0
            ],
            [
                'label' => 'Automatically arrange items of category in columns equally',
                'value' => 1
            ],
        ];
    }

    /**
     * get Category Image
     * @return array
     */
    public function getCategoryImageOptions()
    {
        return [

            [
                'label' => 'Yes',
                'value' => 1
            ],
            [
                'label' => 'No',
                'value' => 0
            ],
        ];
    }

    /**
     * get featured type: none, product, category
     * @return array
     */
    public function getFeaturedTypes()
    {
        return array(
            array(
                'label' => 'None',
                'value' => self::FEATURED_NONE
            ),
            array(
                'label' => 'Product',
                'value' => self::FEATURED_PRODUCTS
            ),
            array(
                'label' => 'Category',
                'value' => self::FEATURED_CATEGORIES
            ),
            array(
                'label' => 'Content',
                'value' => self::FEATURED_CONTENT
            )
        );
    }

    public function getTemplateFilename()
    {
        $filename = '';
        if ($this->getId()) {
            $menu_type = $this->getMenuType();
            if ($menu_type == self::CONTENT_ONLY) {
                $filename = 'content_only/default.phtml';
            } elseif ($menu_type == self::PRODUCT_LISTING) {
                $filename = 'product_listing/general_products.phtml';
            } elseif ($menu_type == self::CATEGORY_LISTING) {
                $filename = 'category_listing/categories_static.phtml';
            } elseif ($menu_type == self::ANCHOR_TEXT) {
                $filename = 'anchor_text/default.phtml';
            } elseif ($menu_type == self::PRODUCT_GRID) {
                $filename = 'product_listing/detailed_products.phtml';
            } elseif ($menu_type == self::CATEGORY_LEVEL) {
                $filename = 'category_listing/categories_level.phtml';
            } elseif ($menu_type == self::CATEGORY_DYNAMIC) {
                $filename = 'category_listing/categories_dynamic.phtml';
            } elseif ($menu_type == self::PRODUCT_BY_CATEGORY_FILTER) {
                $filename = 'product_listing/products_by_category_filter.phtml';
            } else {
                $filename = 'content_only/default.phtml';
            }
        }
        return $filename;
    }

    public function getTemplateFilenameforMobile()
    {
        $filename = '';
        if ($this->getId()) {
            $menu_type = $this->getMenuType();
            if ($menu_type == self::CONTENT_ONLY) {
                $filename = 'content_only/default.phtml';
            } elseif ($menu_type == self::PRODUCT_LISTING) {
                $filename = 'product_listing/general_products.phtml';
            } elseif ($menu_type == self::CATEGORY_LISTING) {
                $filename = 'category_listing/m_categories.phtml';
            } elseif ($menu_type == self::ANCHOR_TEXT) {
                $filename = 'anchor_text/default.phtml';
            } elseif ($menu_type == self::PRODUCT_GRID) {
                $filename = 'product_listing/detailed_products.phtml';
            } elseif ($menu_type == self::CATEGORY_LEVEL) {
                $filename = 'category_listing/m_categories.phtml';
            } elseif ($menu_type == self::CATEGORY_DYNAMIC) {
                $filename = 'category_listing/m_categories.phtml';
            } elseif ($menu_type == self::PRODUCT_BY_CATEGORY_FILTER) {
                $filename = 'product_listing/m_products_by_category_filter.phtml';
            } else {
                $filename = 'content_only/default.phtml';
            }
        }
        return $filename;
    }

    /**
     * get left sub menu align
     * @return array
     */
    public function getLeftSubmenualignOptions()
    {
        return [
            [
                'label' => 'From top menu',
                'value' => 0
            ],
            [
                'label' => 'From top item',
                'value' => 1
            ],

        ];
    }

    /**
     * get sub menu algin
     * @return array
     */
    public function getSubmenualignOptions()
    {
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

    /**
     * get megamenu type
     * @return array
     */
    public function getMegamenutypeOptions()
    {
        return [
            [
                'label' => 'Top Menu',
                'value' => self::TOP_MENU
            ],
            [
                'label' => 'Left Menu',
                'value' => self::LEFT_MENU
            ],

        ];
    }

    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getStoreId();
    }

    public function setConfigIds()
    {
        $ids = $this->_megaHelper->getConfig('megamenu/general/ids', $store = null);
        if (!$ids) {
            $ids = array();
        } else {
            $ids = explode(',', $ids);
        }
        $ids[] = $this->getId();
        $this->_configResoure->saveConfig('megamenu/general/ids', implode(',', $ids), 'default', 0);
        return $this;
    }

    /* -- Save Static block for Item   */
    public function saveItem()
    {
        if ($this->getMenuType() != self::ANCHOR_TEXT) {
            $currentStore = $this->getStoreId();
            $datastores = explode(',', $this->getStores());
            $stores = $this->_storeManager->getStores();
            $theme = $this->_megaHelper->getConfig('design/theme/theme_id', $this->getStoreId());
            if (!$theme)
                $theme = 1;
            foreach ($stores as $id => $store) {
                $this->_storeManager->setCurrentStore($store->getId());
                if (in_array(0, $datastores) || in_array($this->getStoreId(), $datastores)) {
                    $this->_designInterface->setArea('frontend')
                        ->setDesignTheme($theme);
                    $html = $this->getLayout()->createBlock('Magestore\Megamenu\Block\Item')
                        ->setArea('frontend')
                        ->setStore($store->getId())
                        ->setItem($this)
                        ->setTemplate('Magestore_Megamenu::item/item.phtml')
                        ->toHtml();
                    if (strlen($html) >= 1000000) {
                        $html = '<div  class="ms-submenu col-xs-12 sub_left">
                                        <div class="ms-content">
                                        <div class="ms-maincontent">
                                            <p>Content very long, please change content for this menu item!</p></div>
                                            </div>
                                    </div>';
                    }
                    $data = array();
                    $staticBlock = $this->_objectManager->create('Magento\Cms\Model\Block')->load('mega_item_' . $this->getId() . '_store_' . $store->getId(), 'identifier');
                    $data['title'] = 'Mega Item ' . $this->getNameMenu();
                    $data['identifier'] = 'mega_item_' . $this->getId() . '_store_' . $store->getId();
                    $data['stores'] = array($store->getId());
                    $data['content'] = $html;
                    if ($staticBlock->getId()) {
                        $staticBlock->setData('content', $data['content'])->save();
                    } else {
                        $staticBlock->setData($data)->save();
                    }
                } else {
                    $staticBlock = $this->_objectManager->create('Magento\Cms\Model\Block')
                        ->load('mega_item_' . $this->getId() . '_store_' . $store->getId(), 'identifier');
                    if ($staticBlock->getId())
                        $staticBlock->delete();
                }
            }
            $this->_storeManager->setCurrentStore($currentStore);
        }
        return $this;
    }

    public function deleteItem()
    {
        if ($this->getMenuType() != self::ANCHOR_TEXT) {
            $datastores = explode(',', $this->getStores());
            $stores = $this->_storeManager->getStores(false);
            foreach ($stores as $id => $store) {
                if (in_array(0, $datastores) || in_array($store->getId(), $datastores)) {
                    $staticBlock = $this->_objectManager->create('Magento\Cms\Model\Block')->load('mega_item_' . $this->getId() . '_store_' . $store->getId(), 'identifier');
                    $staticBlock->delete();
                }
            }
        }
        return $this;
    }
}
