<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml;

/**
 * Data feed grid container
 */
class Feeds extends \Magento\Backend\Block\Widget\Grid\Container
{

    /**
     * Block constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_feeds';
        $this->_blockGroup = 'Wyomind_SimpleGoogleShopping';
        $this->_headerText = __('Manage Data feeds');

        $this->_addButtonLabel = __('Create New Data Feed');

        parent::_construct();
    }
}
