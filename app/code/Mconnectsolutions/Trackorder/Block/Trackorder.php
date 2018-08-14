<?php

namespace Mconnectsolutions\Trackorder\Block;

use Magento\Framework\View\Element\Template;

class Trackorder extends Template
{
    /**
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    
    /**
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfigObject;
    
    /**
     *
     * @var \Mconnectsolutions\Trackorder\Helper\Data
     */
    private $helper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mconnectsolutions\Trackorder\Helper\Data $helper,
        array $data = []
    ) {
        $this->scopeConfigObject = $context->getScopeConfig();
        $this->storeManager = $context->getStoreManager();
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('Mconnectsolutions_Trackorder::trackorder.phtml');
    }

    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    public function getAjaxUrl()
    {
        if ($this->helper->getConfig('mconnectsolutions_trackorder/track_order/enable')) {
            return $this->getUrl("trackorder/index/info");
        }
    }
    
    public function getStoreConfigValues($inputData)
    {
        return  $this->scopeConfigObject->getValue($inputData);
    }
    
    public function getEnableTrackOrder()
    {
        return $this->helper->getConfig('mconnectsolutions_trackorder/track_order/enable');
    }
    
    public function getTopLink()
    {
        return $this->helper->getConfig('mconnectsolutions_trackorder/track_order/top_link');
    }
    
    public function getTopMenu()
    {
        return $this->helper->getConfig('mconnectsolutions_trackorder/track_order/top_menu');
    }
    
    public function getEmailLink()
    {
        return $this->helper->getConfig('mconnectsolutions_trackorder/track_order/link_email');
    }
}
