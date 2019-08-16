<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Adminhtml\Rule\Edit\Tab;

use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Amasty\Acart\Controller\RegistryConstants;

class Schedule extends Generic implements TabInterface
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    public function getTabLabel()
    {
        return __('Schedule');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('Schedule');
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

    protected function _getRule()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_AMASTY_ACART_RULE);
    }

    protected function _prepareForm()
    {
        $model = $this->_getRule();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('amasty-rule-');

        $fieldset = $form->addFieldset('schedule_fieldset', []);

        $fieldset->addField(
            'schedule',
            'text',
            [
                'name' => 'schedule',
                'label' => __('Schedule'),
                'title' => __('Schedule')

            ]
        );

        $form->getElement(
            'schedule'
        )->setRenderer(
            $this->getLayout()->createBlock('Amasty\Acart\Block\Adminhtml\Rule\Edit\Tab\Schedule\Content')
        );

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
