<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Adminhtml\Blacklist\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blacklist_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Blacklist View'));
    }
}
