<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License 
 * Agreeement (EULA) that is available through the world-wide-web at the 
 * following URI: http://www.magemodule.com/magento2-ext-license.html.  
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through 
 * the web, please send a note to admin@magemodule.com so that we can mail 
 * you a copy immediately.
 *
 * @author       MageModule, LLC admin@magemodule.com 
 * @copyright   2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Block\Adminhtml\Actions;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var string
     */
    private $formId = 'actions_form_placeholder';

    protected function _prepareLayout()
    {
        $this->setTemplate('MageModule_Core::widget/tab/form/placeholder.phtml');

        return parent::_prepareLayout();
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setFormId($id)
    {
        $this->formId = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormId()
    {
        return $this->formId;
    }
}
