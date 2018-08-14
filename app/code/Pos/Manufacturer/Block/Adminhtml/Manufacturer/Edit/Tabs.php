<?php
namespace Pos\Manufacturer\Block\Adminhtml\Manufacturer\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_manufacturer_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Manufacturer Information'));
    }
}