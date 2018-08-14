<?php

namespace WeltPixel\Quickview\Plugin;

class ScopeConfig
{

    /**
     * @var  \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * ResultPage constructor.
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(\Magento\Framework\App\Request\Http $request)
    {
        $this->request = $request;
    }

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $subject
     * @param \Closure $proceed
     * @param $path
     * @param $scopeType
     * @param null $scopeCode
     * @return string
     */
    public function aroundGetValue(
        \Magento\Framework\App\Config\ScopeConfigInterface $subject,
        \Closure $proceed,
        $path,
        $scopeType = \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $scopeCode = null
    )
    {
        $result = $proceed($path, $scopeType, $scopeCode);

        if (($path == 'checkout/cart/redirect_to_cart')) {
            $refererUrl = $this->request->getServer('HTTP_REFERER');
            if (strpos($refererUrl, 'weltpixel_quickview/catalog_product/view') !== false) {
                $result = false;
            }
        }
        return $result;
    }
}
