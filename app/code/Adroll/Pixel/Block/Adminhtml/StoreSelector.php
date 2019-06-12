<?php

namespace Adroll\Pixel\Block\Adminhtml;

use Adroll\Pixel\Helper\Config as ConfigHelper;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\UrlInterface;

class StoreSelector extends Template
{
    public function __construct(
        Context $context,
        UrlInterface $urlInterface,
        ConfigHelper $configHelper,
        array $data = []
    )
    {
        $this->_urlInterface = $urlInterface;
        $this->_configHelper = $configHelper;
        $this->_request = $context->getRequest();
        $this->_storeManager = $context->getStoreManager();

        $this->setTemplate('Adroll_Pixel::store_selector.phtml');
        parent::__construct($context, $data);
    }

    public function getFinalizeUrl()
    {
        return $this->_urlInterface->getUrl('adroll/finalize');
    }

    public function getExistentGroup()
    {
        return $this->_configHelper->getGroupForAdvertisableEid($this->getQueryParamValue('advertisable'));
    }

    public function getWebsites()
    {
        return $this->_storeManager->getWebsites();
    }

    public function getQueryParamValue($name)
    {
        return $this->_request->getQuery($name);
    }
}