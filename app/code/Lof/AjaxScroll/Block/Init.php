<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_AjaxScroll
 *
 * @copyright  Copyright (c) 2016 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\AjaxScroll\Block;

class Init extends \Magento\Framework\View\Element\Template
{   

    protected $coreRegistry = null;
    /**
     * @var Lof\AjaxScroll\Helper\Data
     */
    public $helperData; 

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context    
     * @param \Lof\AjaxScroll\Helper\Data                      $helperData 
     * @param \Magento\Framework\Registry                      $registry   
     * @param array                                            $data       
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Lof\AjaxScroll\Helper\Data $helperData,
        \Magento\Framework\Registry $registry,
        array $data = []
        ) {
        parent::__construct($context, $data);  
        $this->helperData    = $helperData; 
        $this->coreRegistry = $registry;
    }

    public function getProductListMode()
    {  
        if ($currentMode = $this->getRequest()->getParam('product_list_mode')) {
            switch($currentMode){
                case 'grid':
                $productListMode = 'grid';
                break;
                case 'list':
                $productListMode = 'list';
                break;
                default:
                $productListMode = 'grid';
            }
        }
        else {
            $defaultMode = $this->helperData->getConfig('catalog/frontend/list_mode'); 
            switch($defaultMode){
                case 'grid-list':
                $productListMode = 'grid';
                break;
                case 'list-grid':
                $productListMode = 'list';
                break;
                case 'list':
                $productListMode = 'list';
                break;
                case 'grid':
                $productListMode = 'grid';
                break;
                default:
                $productListMode = 'grid';
            }
        }

        return $productListMode;
    }

    public function isEnable() {
        $fullAction       = $this->getRequest()->getFullActionName();
        if ($fullAction == 'catalog_category_view' && $category_obj = $this->coreRegistry->registry('current_category')) {
            $category = $category_obj->getId();
            $categories = explode(',', $this->helperData->getConfig('lofajaxscroll/instances/categories'));  
            if($categories){
                foreach ($categories as $catid) {
                    if($category == $catid){
                        return true;
                    }
                } 
            }
        }
        $enabled_search   = $this->helperData->getConfig('lofajaxscroll/instances/enabled_search');
        $enabled_advanced = $this->helperData->getConfig('lofajaxscroll/instances/enabled_advanced');
        if (($enabled_search && $fullAction == 'catalogsearch_result_index') || ($enabled_advanced && $fullAction == 'catalogsearch_advanced_result')) {
            return true;
        }
        return false;  
    } 

    /**
     * @return bool|false
     */
    public function getLoaderImage()
    {

        $url = $this->helperData->getConfig('lofajaxscroll/design/loading_image');
        if(!empty($url)) {
            $url = strpos($url, 'http') === 0 ? $url : $this->getViewFileUrl($url);
        } 
        return empty($url) ? false : $url;
    }
}
