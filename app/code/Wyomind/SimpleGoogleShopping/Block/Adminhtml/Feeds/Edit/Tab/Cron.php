<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Edit\Tab;

/**
 * Cron task tab
 */
class Cron extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('data_feed');

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('');

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return string
     */
    public function getSGSCronExpr()
    {
        $model = $this->_coreRegistry->registry('data_feed');
        return $model->getCronExpr();
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return __('Cron schedule');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return __('Cron schedule');
    }

    /**
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
