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
namespace Magestore\Megamenu\Observer;
/**
 * Megamenu Observer Model
 */
class Cssgen implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    protected $_directoryList;
    /**
     * @var \Magento\Framework\View\Element\BlockFactory
     */
    protected $_blockFactory;
    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $_genFile;
    /**
     * @var \Magento\Store\Model\WebsiteFactory
     */
    protected $_websiteFactory;
    /**
     * @var \Magento\Store\Model\StoreFactory
     */
    protected $_storeFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    protected $_appRequest;
    /**
     * CssGen constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Store\Model\StoreFactory $storeFactory,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
        \Magento\Framework\Filesystem\Io\File  $genFile,
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        \Magento\Framework\Filesystem\DirectoryList $directoryList

    ) {
        $this->_appRequest = $requestInterface;
        $this->_storeManager = $storeManager;
        $this->_storeFactory = $storeFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->_genFile = $genFile;
        $this->_blockFactory= $blockFactory;
        $this->_directoryList =$directoryList;
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
     * @return \Magento\Framework\Filesystem\Io\File
     */
    public function getGenFile(){
        return $this->_genFile;
    }
    /**
     * Address before save event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {   /*
        $storeCode = 0;
        $websiteCode = 0;
        if($observer->getStore()){
            $store = $this->_storeFactory->create()->load($observer->getStore());
            $website = $this->_websiteFactory->create()->load($store->getWebsiteId());
            $storeCode = $store->getCode();
            $websiteCode = $website->getCode();
        }elseif($observer->getWebsite()){
            $website = $this->_websiteFactory->create()->load($observer->getWebsite());
            $websiteCode = $website->getCode();
        }
        $path = 'css/config/custom/';
        $storeId = null;
        if($storeCode && $websiteCode){
            $storeId = $this->_storeFactory->create()->load($storeCode, 'code')->getId();
            $filename = 'custom_'.$websiteCode.'_'.$storeCode.'.css';

        }elseif(!$storeCode && $websiteCode){
            $storeId = $this->_websiteFactory->create()->load($websiteCode,'code')->getDefaultGroup()->getDefaultStoreId();
            $filename = 'custom_'.$websiteCode.'_default.css';
        }else{
            $filename = 'default.css';
            $path = 'css/config/';
        }
        $file = $this->getBaseDirUrl().'/app/code/Magestore/Megamenu/view/frontend/web/'.$path.$filename;
        $css = $this->_blockFactory->createBlock('Magestore\Megamenu\Block\Megamenu')->setArea('frontend')->setBilly($storeId)->setTemplate('Magestore_Megamenu::cssgen.phtml')->toHtml();
        $this->getGenFile()->setAllowCreateFolders(true);
        $this->getGenFile()->open([ 'path' => $path ]);
        $this->getGenFile()->write($file, $css, 0777);
        $this->getGenFile()->streamLock(true);
        $this->getGenFile()->streamWrite($css);
        $this->getGenFile()->streamUnlock();
        $this->getGenFile()->streamClose();
   */
    }
}
