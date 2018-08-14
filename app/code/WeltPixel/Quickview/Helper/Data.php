<?php

namespace WeltPixel\Quickview\Helper;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var array
     */
    protected $_quickviewOptions;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->_quickviewOptions = $this->scopeConfig->getValue('weltpixel_quickview', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getSkuTemplate() {
        $removeSku = $this->_quickviewOptions['general']['remove_sku'];
        if (!$removeSku) {
            return 'Magento_Catalog::product/view/attribute.phtml';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getCustomCSS() {
        return trim($this->_quickviewOptions['general']['custom_css']);
    }

    /**
     * @return int
     */
    public function getCloseSeconds() {
        return trim($this->_quickviewOptions['general']['close_quickview']);
    }

    /**
     * @return boolean
     */
    public function getScrollAndOpenMiniCart() {
        return $this->_quickviewOptions['general']['scroll_to_top'];
    }

    /**
     * @return boolean
     */
    public function getShoppingCheckoutButtons() {
        return $this->_quickviewOptions['general']['enable_shopping_checkout_product_buttons'];
    }

}
