<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds;

/**
 * Data Feed form container
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{

    /**
     * Container intialization
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Wyomind_SimpleGoogleShopping';
        $this->_controller = 'adminhtml_feeds';

        parent::_construct();

        $this->removeButton('save');
        $this->removeButton('reset');


        $this->updateButton('delete', 'label', __('Delete'));

        $this->addButton(
            'duplicate',
            [
                'label' => __('Duplicate'),
                'class' => 'add',
                'onclick' => "jQuery('#simplegoogleshopping_id').remove(); jQuery('#back_i').val('1'); jQuery('#edit_form').submit();",
            ]
        );
        $this->addButton(
            'generate',
            [
                'label' => __('Generate'),
                'class' => 'save',
                'onclick' => "require([\"sgs_configuration\"], function (configuration) {configuration.generate(); });"
            ]
        );

        $this->addButton(
            'save',
            [
                'label' => __('Save'),
                'class' => 'save',
                'onclick' => "jQuery('#back_i').val('1'); jQuery('#edit_form').submit();",
            ]
        );
    }
}
