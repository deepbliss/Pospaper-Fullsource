<?php

namespace WeltPixel\Quickview\Plugin;

class BlockProductList
{
    const XML_PATH_QUICKVIEW_ENABLED = 'weltpixel_quickview/general/enable_product_listing';
    const XML_PATH_QUICKVIEW_BUTTONSTYLE = 'weltpixel_quickview/general/button_style';
    
    
    /**
     * @var \Magento\Framework\UrlInterface 
     */
    protected $urlInterface;
    
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface 
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
        ) {
        $this->urlInterface = $urlInterface;
        $this->scopeConfig = $scopeConfig;
    }

    public function aroundGetProductDetailsHtml(
        \Magento\Catalog\Block\Product\ListProduct $subject,
        \Closure $proceed,
        \Magento\Catalog\Model\Product $product
        )
    {
        $result = $proceed($product);
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_ENABLED,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled) {
            $buttonStyle =  'weltpixel_quickview_button_' . $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_BUTTONSTYLE,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $productUrl = $this->urlInterface->getUrl('weltpixel_quickview/catalog_product/view', array('id' => $product->getId()));
            return $result . '<a class="weltpixel-quickview '.$buttonStyle.'" onclick ="newwin(&quot;'.$productUrl.'&quot;)" data-quickview-url=' . $productUrl . '></a>';
        }
        
        return $result;
    }
}
