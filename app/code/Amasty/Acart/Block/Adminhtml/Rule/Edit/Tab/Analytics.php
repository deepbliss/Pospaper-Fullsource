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

class Analytics extends Generic implements TabInterface
{
    protected $_systemStore;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    public function getTabLabel()
    {
        return __('Analytics');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('Analytics');
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

        $fldInfo = $form->addFieldset('analytics_fieldset', ['legend' => __('Google Analytics')]);

        $fldInfo->addField(
            'utm_source',
            'text',
            [
                'label' => __('Campaign Source'),
                'name' => 'utm_source',
                'note' => __(
                    '<b>Required.</b> Use <b>utm_source</b> to identify a search engine, newsletter name, or other source.<br/><i>Example:</i> utm_source=google'
                )
            ]
        );

        $fldInfo->addField(
            'utm_medium',
            'text',
            [
                'label' => __('Campaign Medium'),
                'name' => 'utm_medium',
                'note' => __(
                    '<b>Required.</b> Use <b>utm_medium</b> to identify a medium such as email or cost-per- click<br/><i>Example:</i> utm_medium=cpc'
                )
            ]
        );

        $fldInfo->addField(
            'utm_campaign',
            'text',
            [
                'label' => __('Campaign Name'),
                'name' => 'utm_campaign',
                'note' => __(
                    '<b>Required.</b> Used for keyword analysis. Use <b>utm_campaign</b> to identify a specific product promotion or strategic campaign.<br/><i>Example:</i> utm_campaign=spring_sale'
                )
            ]
        );

        $fldInfo->addField(
            'utm_term',
            'text',
            [
                'label' => __('Campaign Term'),
                'name' => 'utm_term',
                'note' => __(
                    'Used for paid search. Use <b>utm_term</b> to note the keywords for this ad.<br/><i>Example:</i> utm_term=running+shoes'
                )
            ]
        );

        $fldInfo->addField(
            'utm_content',
            'text',
            [
                'label' => __('Campaign Content'),
                'name' => 'utm_content',
                'note' => __(
                    'Used for A/B testing and content-targeted ads. Use <b>utm_content</b> to differentiate ads or links that point to the same URL.<br/><i>Example:</i> utm_content=logolink <i>or</i> utm_content=textlink'
                )
            ]
        );

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
