<?php

namespace Adroll\Pixel\Block\Adminhtml;

use Adroll\Pixel\Helper\Config as ConfigHelper;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\RequestInterface;

class Advertisables extends Template
{
    public function __construct(
        Context $context,
        UrlInterface $urlInterface,
        ConfigHelper $configHelper,
        array $data = [])
    {
        $this->_urlInterface = $urlInterface;
        $this->_configHelper = $configHelper;
        $this->_storeManager = $context->getStoreManager();

        $this->setTemplate('Adroll_Pixel::advertisables.phtml');
        parent::__construct($context, $data);
    }

    public function getAdrollBaseUrl()
    {
        return ConfigHelper::ADROLL_BASE_URL;
    }

    public function getWebsites()
    {
        return $this->_storeManager->getWebsites();
    }

    public function getAdvertisableEid($groupId)
    {
        return $this->_configHelper->getAdvertisableEid($groupId);
    }

    public function getAdvertisableName($groupId)
    {
        return $this->_configHelper->getAdvertisableName($groupId);
    }

    public function getUninstallPixelActionUrl()
    {
        return $this->_urlInterface->getUrl('adroll/uninstallPixel');
    }
}