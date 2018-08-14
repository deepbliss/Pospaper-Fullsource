<?php
/**
 * Landofcoder
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the landofcoder.com license that is
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
 * @copyright  Copyright (c) 2016 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\AjaxScroll\Helper;
use Magento\Framework\App\Filesystem\DirectoryList;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $_storeManager;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;


    const XML_PATH_GENERAL_ENABLED = 'lofajaxscroll/general/enabled';


    /**
     * @param \Magento\Framework\App\Helper\Context      $context        
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager   
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider 
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider
        ) {
        parent::__construct($context);
        $this->_storeManager   = $storeManager;
        $this->_filterProvider = $filterProvider;
    }

	 /**
     * Return brand config value by key and store
     *
     * @param string $field
     * @param \Magento\Store\Model\Store|int|string $store
     * @return string|null
     */
     public function getConfig($field, $default="", $store = null)
     {
        $store = $this->_storeManager->getStore($store);
        $websiteId = $store->getWebsiteId();

        $result = $this->scopeConfig->getValue(
            $field,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store);
        if($result == "") {
            $result = $default;
        }
        return $result;
    }

    public function getConfigData($field, $store = null)
    {
        $store = $this->_storeManager->getStore($store);
        $websiteId = $store->getWebsiteId();

        $result = $this->scopeConfig->getValue(
            $field,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store); 
        return $result;
    }
  
    public function filter($str)
    {
    	$html = $this->_filterProvider->getPageFilter()->filter($str);
    	return $html;
    } 

    public function isEnabled($storeId = null)
    {
        return $this->getConfigData(self::XML_PATH_GENERAL_ENABLED, $storeId);
    }
 
    
    /**
     * Escape quotes in java script
     *
     * @param mixed $data
     * @param string $quote
     * @return mixed
     */
    public function jsQuoteEscape($data, $quote='\'')
    {
        if (is_array($data)) {
            $result = array();
            foreach ($data as $item) {
                $result[] = str_replace($quote, '\\'.$quote, $item);
            }
            return $result;
        }
        return str_replace($quote, '\\'.$quote, $data);
    }

    
}