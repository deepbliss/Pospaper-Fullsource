<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Edit\Tab;

/**
 * Main configuration tab
 */
class Configuration extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    )
    {

        parent::__construct($context, $registry, $formFactory, $data);
        $this->_systemStore = $systemStore;
    }

    /**
     * @return $this
     */
    protected function _prepareForm()
    {

        $model = $this->_coreRegistry->registry('data_feed');

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Configuration')]);

        if ($model->getId()) {
            $fieldset->addField('simplegoogleshopping_id', 'hidden', ['name' => 'simplegoogleshopping_id']);
        }

        // ===================== action flags ==================================
        // save and generate flag
        $fieldset->addField('generate_i', 'hidden', ['name' => 'generate_i', 'value' => '']);
        // save and continue flag
        $fieldset->addField('back_i', 'hidden', ['name' => 'back_i', 'value' => '']);

        // ===================== required hidden fields ========================

        $fieldset->addField(
            'simplegoogleshopping_category_filter',
            'hidden',
            [
                'class' => 'debug',
                'name' => 'simplegoogleshopping_category_filter',
                'label' => __('Category filter'),
                'title' => __('Category filter'),
                'required' => true
            ]
        );
        $fieldset->addField(
            'simplegoogleshopping_category_type',
            'hidden',
            [
                'class' => 'debug',
                'name' => 'simplegoogleshopping_category_type',
                'label' => __('Category type'),
                'title' => __('Category type'),
                'required' => true
            ]
        );
        $fieldset->addField(
            'simplegoogleshopping_categories',
            'hidden',
            [
                'class' => 'debug',
                'name' => 'simplegoogleshopping_categories',
                'label' => __('Categories'),
                'title' => __('Categories'),
                'required' => true
            ]
        );
        $fieldset->addField(
            'simplegoogleshopping_attributes',
            'hidden',
            [
                'class' => 'debug',
                'name' => 'simplegoogleshopping_attributes',
                'label' => __('Attributes filters'),
                'title' => __('Attributes filters'),
                'required' => true
            ]
        );


        // ===================== required visible fields =======================

        $fieldset->addField(
            'simplegoogleshopping_filename',
            'text',
            [
                'name' => 'simplegoogleshopping_filename',
                'label' => __('Filename'),
                'title' => __('Filename'),
                'required' => true,
                "note" => "Name of the file that is created when the data feed is generated"
            ]
        );
        $fieldset->addField(
            'simplegoogleshopping_path',
            'text',
            [
                'name' => 'simplegoogleshopping_path',
                'label' => __('Path'),
                'title' => __('Path'),
                'required' => true,
                "note" => __("Directory where the file is stored (related to the Magento root directory)")
            ]
        );
        $fieldset->addField(
            'store_id',
            'select',
            [
                'name' => 'store_id',
                'label' => __('Store View'),
                'title' => __('Store View'),
                'required' => true,
                'values' => $this->_systemStore->getStoreValuesForForm(false, false),
                "note" => __("Data from a particular store view that must be used in the data feed")
            ]
        );

        // point d'interrogation sur le champs store view
        // $renderer = $this->getLayout()->createBlock('Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element');
        // $field->setRenderer($renderer);

        $fieldset->addField(
            'simplegoogleshopping_url',
            'text',
            [
                'name' => 'simplegoogleshopping_url',
                'label' => __('Website url'),
                'title' => __('Website url'),
                'required' => true,
                "note" => __("Url of the data feed (internal use only)")
            ]
        );
        $fieldset->addField(
            'simplegoogleshopping_title',
            'text',
            [
                'name' => 'simplegoogleshopping_title',
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true,
                "note" => __("Title of the data feed (internal use only)")
            ]
        );
        $fieldset->addField(
            'simplegoogleshopping_description',
            'textarea',
            [
                'name' => 'simplegoogleshopping_description',
                'label' => __('Description'),
                'title' => __('Description'),
                'required' => true,
                'style' => 'height:100px'
            ]
        );
        $fieldset->addField(
            'simplegoogleshopping_xmlitempattern',
            'textarea',
            [
                'name' => 'simplegoogleshopping_xmlitempattern',
                'label' => __('Xml pattern'),
                'title' => __('Xml pattern'),
                'required' => true,
                'style' => 'height:500px ;letter-spacing:1px;',
                "note" => __("Product template that will be used to generate the final output for the data feed"),
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return __('Configuration');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return __('Configuration');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
