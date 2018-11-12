<?php

namespace WeltPixel\GoogleTagManager\Plugin;

class CookieManagement
{
    /**
     * @var \WeltPixel\GoogleTagManager\Helper\Data
     */
    protected $helper;

    /**
     * @var \WeltPixel\GoogleTagManager\Model\CookieManager
     */
    protected $cookieManager;

    /**
     * @param \WeltPixel\GoogleTagManager\Helper\Data $helper
     * @param \WeltPixel\GoogleTagManager\Model\CookieManager $cookieManager
     */
    public function __construct(
        \WeltPixel\GoogleTagManager\Helper\Data $helper,
        \WeltPixel\GoogleTagManager\Model\CookieManager $cookieManager
    )
    {
        $this->helper = $helper;
        $this->cookieManager = $cookieManager;
    }

    /**
     * @param \Magento\Framework\App\FrontController $subject
     * @param $result
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDispatch(
        \Magento\Framework\App\FrontController $subject,
        $result
    ) {
        if (!$this->helper->isEnabled()) {
            return $result;
        }

        $this->cookieManager->setGtmCookies();
        return $result;

    }
}