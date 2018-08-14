<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Navigation;

class CssFileInclude extends \Magento\Framework\View\Element\Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->addPageAsset('Amasty_Checkout::css/source/mkcss/am-checkout.css');
    }
}
