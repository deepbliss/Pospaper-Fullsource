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
namespace Magestore\Megamenu\Block;

/**
 * Block BlockTest
 */
class Item extends \Magento\Framework\View\Element\Template
{
    /**
     * Block constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_catalogProductListing;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;
    /**
     * @var \Magento\Checkout\Helper\Datad
     */
    protected $_helperCheckoutData;

    /**
     * Item constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Block\Product\ListProduct $productList
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Block\Product\ListProduct $productList,
        \Magento\Checkout\Helper\Data $checkoutDataHelper,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        array $data = array()
    ) {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductListing = $productList;
        $this->_storeManager = $context->getStoreManager();
        $this->_helperCheckoutData = $checkoutDataHelper;
        $this->_filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    public function getStore(){
        return $this->_storeManager->getStore();
    }
    public function getCategoryCollection(){
        $storeId = $this->getStore()->getId();
        $catIds = array(0);
        $catIds = explode(', ',$this->getItem()->getCategories());
        $collection = $this->_categoryCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', ['in' => $catIds])
            ->addFieldToFilter('is_active', 1)
            ->setOrder('position','ASC');
        $collection->setStoreId($storeId);
        return $collection;
    }

    public function getChildrenLevel(){
       return explode(', ',$this->getItem()->getCategories());
   }
   
    public function getParentCategories(){
        $storeId = $this->getStore()->getId();
        $parentIds = array(0);
        $categories = $this->getCategoryCollection();
        $categoryIds = $categories->getAllIds();
        foreach($categories as $category){
            $parents = $category->getParentIds();
            if(count(array_intersect($parents, $categoryIds))== 0)
                $parentIds[] = $category->getId();
        }
        $collection = $this->_categoryCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', ['in' => $parentIds])
            ->addFieldToFilter('is_active', 1)
            ->setOrder('position','ASC');
        $collection->setStoreId($storeId);
        return $collection;
    }
    public function getChildrenCollection($category) {
        if (is_object($category)) {
            $storeId = $this->getStore()->getId();
            $item = $this->getItem();
            $childrenIds = $category->getAllChildren();
            $childrenIds = explode(',', $childrenIds);
            $childrenIds = array_intersect($childrenIds, explode(', ', $item->getCategories()));
            $categoryCollection = $this->_categoryCollectionFactory->create()
                ->addFieldToFilter('entity_id', ['in' => $childrenIds])
                ->addFieldToFilter('entity_id', ['neq' => $category->getId()])
                ->addFieldToFilter('is_active', 1)
                ->setOrder('position','ASC')
                ->addAttributeToSelect('*');
            $categoryCollection->setStoreId($storeId);
            return $categoryCollection;
        }
        return null;
    }
    public function getAllCategory($columns_number,$parrent_categories){
        $collection = $parrent_categories;
        $item = $this->getItem();
        $type = $item->getCategoryType();
        $data = array();
        $sort = array();
        $categories = array();
        $columns_number = intval($columns_number);
        foreach ($collection as $category){
            $category->setLevel(1);
            $data[$category->getId()] = $category;
            if($category->hasChildren()){
                $children = $this->getChildrenCollection($category);
                foreach ($children as $child){
                    if(in_array($child, $data)) continue;
                    $child->setLevel(2);
                    $data[$child->getId()] = $child;
                }
                $sort[$category->getId()] =$children->getSize() + 1;
            }else{
                $sort[$category->getId()] =  1;
            }
        }
        if ($type) {
            $add_cat = 0;
            if (count($data) % $columns_number == 0) {
                $number = count($data) / $columns_number;
            } else {
                $number = floor(count($data) / $columns_number) + 1;
                $add_cat = count($data) % $columns_number;
            }
            $i = 1;
            $j = 1;
            foreach ($data as $cat) {
                $categories[$i][] = $cat;

                if ($j >= $number) {
                    $j = 1;
                    if ($add_cat && $i == $add_cat) {
                        $number = count($data) / $columns_number;
                    }
                    $i++;
                } else {
                    $j++;
                }
            }
            return $categories;
        }
        //asort($sort);
        if(array_sum($sort)% $columns_number==0 )
            $tb = array_sum($sort) / $columns_number;
        else
            $tb = floor(array_sum($sort) / $columns_number) +1;
        $value_group = array();
        $tb_temp = $tb= intval($tb);
        $tmp = array();
        $du = 0;
        foreach ($sort as $key => $value){
            if(in_array($key, $tmp)) continue;;
            $value_group[$key][] =  $key;
            $columns_number--;
            $total = $value;
            unset($sort[$key]);
            foreach ($sort as $key1 => $value1){
                $temp = $total+$value1;
                if($temp > $tb_temp){
                    continue;
                }else{
                    $total += $value1;

                    $value_group[$key][] =  $key1;

                    unset($sort[$key1]);
                    $tmp[] = $key1;
                }
            }
            $du += $tb -  $total;
            $tb_temp = $tb + $du;
        }
        foreach ($value_group as $groups) {
            $data_temp = array();
            foreach ($groups as $group) {
                $data_temp[] = $data[$group];
                $temps = $this->getChildrenCollection($data[$group]);
                foreach ($temps as $temp) {
                    $temp->setLevel(2);
                    $data_temp[] = $temp;
                }
            }
            if(count($data_temp))
                $categories[] = $data_temp;
        }
        return $categories;

    }
    public function getLevel($level){
        switch ($level) {
            case 1:
                $class = 'level1';
                break;
            case 2:
                $class = 'level2';
                break;
            case 3:
                $class = 'level3';
                break;
            default:
                $class = '';
        }
        return $class;
    }
    public function getChildrenCategoriesByLevel($category,$level){
        $storeId = $this->getStore()->getId();
        $categoryids =  explode(', ',$this->getItem()->getCategories());
        if($level == 2){
            $childs = $category->getChildrenCategories();
            if(is_object($childs)){
                $childrenIds = $childs->getAllIDs();
            }
            if(is_array($childs)){
                $childrenIds = array();
                foreach($childs as $child){
                    $childrenIds[] = $child->getId();
                }
            }
            $childrenIds = array_intersect($childrenIds, $categoryids);
        }elseif($level == 3){
            $childrenIds = $category->getAllChildren();
            $childrenIds = explode(',', $childrenIds);
            $childrenIds = array_intersect($childrenIds, $categoryids);

        }
        if(count($childrenIds)==0)
            $childrenIds = array();
        $childrens = $this->_categoryCollectionFactory->create()
                ->addFieldToFilter('entity_id', ['in' => $childrenIds])
                ->addFieldToFilter('entity_id',['neq' => $category->getId()])
                ->addFieldToFilter('is_active', 1)
                ->setOrder('position','ASC')
                ->addAttributeToSelect('*');
        $childrens->setStoreId($storeId);
        return $childrens;
    }
    public function getListingcategories(){
        $categoryIds = array(0);
        $storeId = $this->getStore()->getId();
        $categoryIds = explode(', ', $this->getItem()->getCategories());
        $collection = $this->_categoryCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', ['in' => $categoryIds])
            ->addFieldToFilter('is_active', 1)
            ->setOrder('position','ASC');
        $collection->setStoreId($storeId);
        return $collection;
    }
    public function getProductImage($product){
        return $this->_catalogProductListing->getImage($product, 'category_page_grid')->toHtml();
    }
    public function getPriceHtml($product){
        return $this->_helperCheckoutData->formatPrice($product->getFinalPrice());
    }

    /**
     * Featured
     */

    public function filterCms($text) {
		return $this->_filterProvider->getPageFilter()->filter($text);
	}
    public function limitString($string, $limit = 100) {
        // Return early if the string is already shorter than the limit
        if (strlen($string) < $limit) {
            return $string;
        }
        $regex = "/(.{1,$limit})\b/";
        preg_match($regex, $string, $matches);
        return $matches[1];
    }
    public function hasFeaturedItem() {
        $data = $this->getItem()->getData();
        if (isset($data['featured_type']) && $data['featured_type'] != \Magestore\Megamenu\Model\Megamenu::FEATURED_NONE) {
            return true;
        }
        return false;
    }
    public function getFeaturedProducts() {
        $storeId = $this->getStore();
        $data = $this->getItem()->getData();
        $proIds = array(0);
        if (isset($data['featured_products']) && $data['featured_products']) {
            $proIds = explode(', ', $data['featured_products']);
        }
        $collection = $this->_productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', $proIds)
            ->setOrder('position','ASC');
        $store= $this->getStore();
        if ($store)
            $collection->addStoreFilter($store);
        $collection->addAttributeToFilter('status', 1);
        $visibleStatus = [
            \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH,
            \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_CATALOG,
            \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_SEARCH,
        ];
        $collection->addAttributeToFilter('visibility', ['in'=>$visibleStatus]);
        return $collection;
    }
    public function getFeaturedCategories() {
        $data = $this->getItem()->getData();
        $catIds = array(0);
        if (isset($data['featured_categories']) && $data['featured_categories']) {
            $catIds = explode(', ', $data['featured_categories']);
        }
        $collection = $this->_categoryCollectionFactory->create()
                        ->addAttributeToSelect('*')
                        ->addFieldToFilter('entity_id', $catIds)
                        ->setOrder('position','ASC');
        return $collection;
    }
    /**
     * Main menu item content
     */
    public function getColumnNumber() {
        $data = $this->getItem()->getData();
        $columnNumber = '';
        if (isset($data['colum']) && $data['colum']) {
            $columnNumber = intval($data['colum']);
        }
        if ($columnNumber)
            return $columnNumber;
        else
            return 4;
    }
    public function getProducts() {
        $proIds = explode(', ',$this->getItem()->getProducts());
        $collection = $this->_productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', $proIds)
            ->setOrder('position','ASC');
        $store= $this->getStore();
        if ($store)
            $collection->addStoreFilter($store);
        $collection->addAttributeToFilter('status', 1);
        $visibleStatus = [
            \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH,
            \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_CATALOG,
            \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_SEARCH,
        ];
        $collection->addAttributeToFilter('visibility', ['in'=>$visibleStatus]);
        return $collection;
    }
    public function getProductbycategory($category){
        $storeId = $this->getStore()->getId();
        $number_products =  $this->getItem()->getNumberProducts();
        $collection = $this->_productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->setStoreId($storeId)
            ->addCategoryFilter($category)
            ->setOrder('position','ASC');
        $collection->addAttributeToFilter('status', 1);
        $visibleStatus = [
            \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH,
            \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_CATALOG,
            \Magento\Catalog\Model\Product\Visibility::VISIBILITY_IN_SEARCH,
        ];
        $collection->addAttributeToFilter('visibility', ['in'=>$visibleStatus]);
        if(isset($number_products) && $number_products){
            $collection->setPageSize($number_products);
        }
        return $collection;
    }
    public function positionSubAuto($align) {
        $sub_position = '';
        switch ($align) {
            case 0:
                $sub_position = 'sub_left';
                break;
            case 1:
                $sub_position = 'sub_right';
                break;
            case 2:
                $sub_position = 'sub_left position_auto';
                break;
            case 3:
                $sub_position = 'sub_right position_auto';
                break;
            default:
                break;
        }
        return $sub_position;
    }
    public function positionLeftSubAuto($align) {
        $sub_position = '';
        switch ($align) {
            case 0:
                $sub_position = 'position_menu';
                break;
            case 1:
                $sub_position = 'position_item';
                break;
            default:
                break;
        }
        return $sub_position;
    }
}
