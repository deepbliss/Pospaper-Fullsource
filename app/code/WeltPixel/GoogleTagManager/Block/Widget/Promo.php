<?php
namespace WeltPixel\GoogleTagManager\Block\Widget;

class Promo extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('widget/promolink_widget.phtml');
    }
}
