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
class Megamenu extends \Magento\Framework\View\Element\Template
{
    /**
     * Block constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    /**
     * @var \Magestore\Megamenu\Model\MegamenuFactory
     */
    protected $_megamenuFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_configResoure;
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_typeListInterface;
	/**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;
    /**
     * Megamenu constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magestore\Megamenu\Model\MegamenuFactory $megamenuFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magestore\Megamenu\Model\MegamenuFactory $megamenuFactory,
        \Magento\Config\Model\ResourceModel\Config $configResoure,
        \Magento\Framework\App\Cache\TypeListInterface $typeListInterface,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
		\Magento\Cms\Model\Template\FilterProvider $filterProvider,
        array $data = array()
    ) {
        $this->_megamenuFactory = $megamenuFactory;
        $this->_storeManager = $context->getStoreManager();
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_pageConfig = $context->getPageConfig();
        $this->_typeListInterface = $typeListInterface;
        $this->_configResoure = $configResoure;
        $this->_directoryList =$directoryList;
		$this->_filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }


    /**
     * get base dir url
     * @return mixed
     */
    public function getBaseDirUrl()
    {
        return $this->_directoryList->getRoot();
    }
    /**
     * add css to head multi store
     */
    protected function _construct()
    {

        $store_id = $this->getStoreId();

        /*if ($this->getConfig('megamenu/general/enable', $store_id)) {

             Add config Css
            $website = $this->_storeManager->getWebsite()->getCode();
            $store = $this->_storeManager->getStore()->getCode();
            $path = '/app/code/Magestore/Megamenu/view/frontend/web/css/config/';
            if (file_exists($this->getBaseDirUrl(). $path . 'custom/custom_' . $website . '_' . $store . '.css')) {
                $css_file = 'config/custom/custom_' . $website . '_' . $store . '.css';
            } else {
                if (file_exists($this->getBaseDirUrl(). $path .  'custom/custom_' . $website . '_default.css')) {
                    $css_file = 'config/custom/custom_' . $website . '_default.css';
                } else {
                    $css_file = 'config/default.css';
                }
            }
            $megacssfile = 'Magestore_Megamenu::css/' . $css_file;
            $this->_pageConfig->addPageAsset($megacssfile);
        }*/
        if ($this->getConfig('megamenu/general/ids') && $this->getConfig('megamenu/general/cache')) {
            $item_ids = explode(',',$this->getConfig('megamenu/general/ids'));
            $item_ids = array_unique($item_ids);
            $this->_typeListInterface->cleanType('block_html');
            $this->_typeListInterface->cleanType('full_page');
            foreach($item_ids as $id){
                $this->_megamenuFactory->create()->load($id)->saveItem();
            }
            $this->_configResoure->saveConfig('megamenu/general/ids','0','default', 0);
            $this->_typeListInterface->cleanType('config');
            $this->_typeListInterface->cleanType('block_html');
            $this->_typeListInterface->cleanType('full_page');
        }

    }
    /**
     * get Store Id
     * @return mixed
     */
    public function getStoreId(){
        return $this->_storeManager->getStore()->getStoreId();
    }

    /**
     * get Config
     * @param $key
     * @param null $store
     * @return mixed
     */
    public function getConfig($key, $store = null) {
        return $this->_scopeConfig->getValue(
            $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * get top menu collection
     * @return mixed
     */
    function getTopMenuCollection(){
        $storeId = $this->getStoreId();
        $collection = $this->_megamenuFactory->create()->getCollection()
            ->addFieldToFilter('megamenu_type',\Magestore\Megamenu\Model\Megamenu::TOP_MENU)
            ->addFieldToFilter('stores',[['finset'=>$storeId],['finset'=>0]])
            ->addFieldToFilter('status',\Magestore\Megamenu\Model\Status::STATUS_ENABLED)
            ->setOrder('sort_order','ASC');
        return $collection;
    }
    public function getLeftMenuCollection(){
        $storeId = $this->getStoreId();
        $collection = $this->_megamenuFactory->create()->getCollection()
            ->addFieldToFilter('megamenu_type',\Magestore\Megamenu\Model\Megamenu::LEFT_MENU)
            ->addFieldToFilter('stores',[['finset'=>$storeId],['finset'=>0]])
            ->addFieldToFilter('status',\Magestore\Megamenu\Model\Status::STATUS_ENABLED)
            ->setOrder('sort_order','ASC');
        return $collection;
    }

    /**
     * get Sub memu width
     * @param $items
     * @return string
     */
    public function getSubMenuWidth($items){
        $array=array();
        foreach ($items as $item){
            if($item->getMenuType() != \Magestore\Megamenu\Model\Megamenu::ANCHOR_TEXT) $array[] = $item->getSubmenuWidth();
        }
        return json_encode($array);
    }
    public function getEffect(){
        $storeId = $this->getStoreId();
        return $this->getConfig(
            'megamenu/general/menu_effect',
            $storeId
        );
    }
    public function getMobileEffect(){
        $storeId = $this->getStoreId();
        return $this->getConfig(
            'megamenu/mobile_menu/mobile_effect',
            $storeId
        );
    }
    public function getAlign(){
        $storeId = $this->getStoreId();
        return $this->getConfig(
            'megamenu/top_menu/topmenu_align',
            $storeId
        );
    }
    public function getClassMenuType($name){
        $storeId = $this->getStoreId();
        if($name = 'topmenu'){
            $menu_type = $this->getConfig('megamenu/top_menu/responsive',$storeId);
        }else{
            $menu_type = $this->getConfig('megamenu/left_menu/responsive',$storeId);
        }

        switch ($menu_type) {
            case \Magestore\Megamenu\Model\Megamenu::NO_RESPONSIVE:
                return 'no-responsive';
            default:
                return  '';
        }
    }
    public function getContent($item){
        $storeId = $this->getStoreId();
        $cache =  $this->getConfig('megamenu/general/cache',$store=null);
        if($cache == \Magestore\Megamenu\Model\Status::STATUS_ENABLED){
            $content =  $this->getLayout()->createBlock(
                'Magento\Cms\Block\Block'
            )->setBlockId('mega_item_' .$item->getId().'_store_'.$storeId)
             ->toHtml();
        }else{
            $content =  $this->getLayout()->createBlock(
                'Magestore\Megamenu\Block\Item'
            )   ->setStore($storeId)
                ->setItem($item)
                ->setTemplate('item/item.phtml')
                ->toHtml();
        }
        return $this->_filterProvider->getPageFilter()->filter($content);
    }
    public function getImageIcon($item){
        $image_url =  $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$item->getItemIcon();
        return $image_url;
    }
}
