<?php

namespace Pos\Custommodule\Block\Product;

class View extends \Magento\Catalog\Block\Product\View
{
    protected function _prepareLayout()
    {
        $this->getLayout()->createBlock(\Magento\Catalog\Block\Breadcrumbs::class);
        return parent::_prepareLayout();
    }
}