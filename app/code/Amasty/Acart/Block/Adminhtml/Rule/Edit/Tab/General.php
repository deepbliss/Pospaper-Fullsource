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

class General extends Generic implements TabInterface
{
    /**
     * @var \Amasty\Acart\Ui\Component\Listing\Column\Active\Options
     */
    private $activeOptions;

    /**
     * @var \Amasty\Acart\Ui\Component\Listing\Column\CancelCondition\Options
     */
    private $cancelConditionOptions;

    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    private $yesno;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Amasty\Acart\Ui\Component\Listing\Column\Active\Options $activeOptions,
        \Amasty\Acart\Ui\Component\Listing\Column\CancelCondition\Options $cancelConditionOption,
        \Magento\Config\Model\Config\Source\Yesno $yesno,
        array $data = []
    ) {
        $this->activeOptions = $activeOptions;
        $this->cancelConditionOptions = $cancelConditionOption;
        $this->yesno = $yesno;
        parent::__construct($context, $registry, $formFactory, $data);
    }

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

    protected function _getRule()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_AMASTY_ACART_RULE);
    }

    /**
     * @inheritdoc
     */
    protected function _prepareForm()
    {
        $model = $this->_getRule();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('amasty-rule-');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General')]);

        if ($model->getId()) {
            $fieldset->addField('rule_id', 'hidden', ['name' => 'rule_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Name'), 'title' => __('Name'), 'required' => true]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => $this->activeOptions->toArray()
            ]
        );

        $fieldset->addField(
            'priority',
            'text',
            [
                'class' => 'validate-number',
                'name' => 'priority',
                'label' => __('Priority'),
                'title' => __('Priority'),
            ]
        );

        $fieldset->addField(
            'cancel_condition',
            'multiselect',
            [
                'label' => __('Cancel Condition'),
                'title' => __('Cancel Condition'),
                'name' => 'cancel_condition',
                'values' => $this->cancelConditionOptions->toOptionArray(),
                'note' => __(
                    'Note! Additional to the listed actions Order Placed action will always cancel the abandoned cart email'
                )
            ]
        );

        $form->setValues($model->getData());

        $this->setForm($form);

        parent::_prepareForm();

        return $this;
    }
}
