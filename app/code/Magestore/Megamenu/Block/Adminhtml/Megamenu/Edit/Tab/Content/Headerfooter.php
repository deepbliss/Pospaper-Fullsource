<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Megamenu
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Megamenu\Block\Adminhtml\Megamenu\Edit\Tab\Content;

class Headerfooter extends \Magestore\Megamenu\Block\Adminhtml\Megamenu\Edit\Tab\AbstractBlock implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Header & Footer Content');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Header & Footer Content');
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
    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->getRegistryModel();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();


        $fieldset = $form->addFieldset('megamenu_headerfooter', ['legend' => __('Header & Footer Content')]);

        $fieldset->addField(
            'header',
            'editor',
            [
                'name' => 'header',
                'label' => __('Header'),
                'title' => __('Header'),
                'style' => 'height:16em',
                'required' => false,
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );

        $fieldset->addField(
            'footer',
            'editor',
            [
                'name' => 'footer',
                'label' => __('Footer'),
                'title' => __('Footer'),
                'style' => 'height:16em',
                'required' => false,
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );


        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}