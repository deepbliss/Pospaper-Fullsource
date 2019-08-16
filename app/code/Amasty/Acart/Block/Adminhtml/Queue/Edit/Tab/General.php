<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Adminhtml\Queue\Edit\Tab;

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


    protected function _getQueue()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_AMASTY_ACART_QUEUE);
    }


    protected function _prepareForm()
    {
        $model = $this->_getQueue();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('amasty_queue_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General')]);

        if ($model->getId()) {
            $fieldset->addField('history_id', 'hidden', ['name' => 'history_id']);
        }

        $fieldset->addField(
            'email_subject',
            'text',
            ['name' => 'email_subject', 'label' => __('Subject'), 'title' => __('Subject'), 'required' => true]
        );

        $fieldset->addField(
            'email_body',
            'textarea',
            [
                'name' => 'email_body',
                'label' => __('Body'),
                'title' => __('Body'),
                'required' => true,
                'style' => 'height:24em',
            ]
        );

        $fieldset->addField(
            'sales_rule_coupon',
            'text',
            ['name' => 'sales_rule_coupon', 'label' => __('Coupon'), 'title' => __('Coupon'), 'required' => false]
        );

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
