<?php

namespace WeltPixel\Quickview\Plugin;

class BlockProductViewGallery
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var  \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * ResultPage constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
                                \Magento\Framework\App\Request\Http $request)
    {
        $this->request = $request;
        $this->scopeConfig = $scopeConfig;
    }


    /**
     * @param \Magento\Catalog\Block\Product\View\Gallery $subject
     * @param \Closure $proceed
     * @param string $name
     * @param string|null $module
     * @return string|false
     */
    public function aroundGetVar(
        \Magento\Catalog\Block\Product\View\Gallery $subject,
        \Closure $proceed,
        $name,
        $module = null
    )
    {
        $result = $proceed($name, $module);

        if ($this->request->getFullActionName() != 'weltpixel_quickview_catalog_product_view') {
            return $result;
        }

        switch ($name) {
            case "gallery/navdir" :
                $removeProductImageThumb = $this->scopeConfig->getValue(\WeltPixel\Quickview\Observer\AddUpdateHandlesObserver::XML_PATH_QUICKVIEW_REMOVE_PRODUCT_IMAGE_THUMB,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                if ($removeProductImageThumb) {
                    $result = 'horizontal';
                }
                break;
            /* Disable the image fullscreen on quickview*/
            case "gallery/allowfullscreen" :
                $result = 'false';
                break;
        }

        return $result;
    }
}
