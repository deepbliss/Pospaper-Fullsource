<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Adminhtml\Blacklist\Edit\Tab;

use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Amasty\Acart\Controller\RegistryConstants;

class General extends Generic implements TabInterface
{
    public function getTabLabel()
    {
        return __('General');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('General');
    }

    /**
     * Returns status flag about this tab can be showed or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return Form
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */


    protected function _getBlacklist()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_AMASTY_ACART_BLACKLIST);
    }


    protected function _prepareForm()
    {
        $model = $this->_getBlacklist();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('amasty_blacklist_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General')]);

        if ($model->getId()) {
            $fieldset->addField('blacklist_id', 'hidden', ['name' => 'blacklist_id']);
        }

        $fieldset->addField(
            'customer_email',
            'text',
            [
                'name' => 'customer_email',
                'label' => __('Email'),
                'class' => 'required-entry validate-email',
                'title' => __('Email'),
                'required' => true
            ]
        );


        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
