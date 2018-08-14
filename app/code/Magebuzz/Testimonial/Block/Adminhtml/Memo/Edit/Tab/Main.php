<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Magebuzz\Testimonial\Block\Adminhtml\Memo\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $store;

    protected $_wysiwygConfig;

    /**
     * @var \Magebuzz\Testimonial\Helper\Data
     */
    protected $_helper;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param array $data
     */

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magebuzz\Testimonial\Helper\Data $helper,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_helper = $helper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /** @var \Magebuzz\Testimonial\Model\Testimonial $model */
        $model = $this->_coreRegistry->registry('testimonial_memo');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('testimonial_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('mb_testimonial_id', 'hidden', ['name' => 'mb_testimonial_id']);
        }
        //2.name
        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Name'), 'title' => __('Name'), 'required' => true]
        );

        // 3.email
        $fieldset->addField(
            'email',
            'text',
            ['name' => 'email', 'label' => __('Email'), 'title' => __('Email'), 'required' => true, 'class' => 'validate-email']
        );
        // 4. avatar
        $fieldset->addField(
            'avatar',
            'file',
            [
                'name' => 'avatar',
                'label' => __('Avatar'),
                'title' => __('Avatar'),
                'note' => 'Allow image type: jpg, jpeg, gif, png',
                'after_element_html' => $this->getAfterImageElementHtml('image', $model->getAvatar())
            ]
        );
        // 5. website
        $fieldset->addField(
            'website',
            'text',
            ['name' => 'website', 'label' => __('Website'), 'title' => __('Website')]
        );

        // 6. company
        $fieldset->addField(
            'company',
            'text',
            ['name' => 'company', 'label' => __('Company'), 'title' => __('Company')]
        );

        // 7. address
        $fieldset->addField(
            'address',
            'text',
            ['name' => 'address', 'label' => __('Address'), 'title' => __('Address')]
        );

        // 8. testimonial
        $fieldset->addField(
            'testimonial',
            'editor',
            [
                'name' => 'testimonial',
                'label' => __('Testimonial'),
                'title' => __('Testimonial'),
                'style'     => 'width:600px; height:300px;',
                'wysiwyg'   => true,
                'config'    => $this->_wysiwygConfig->getConfig(),
                'class' => $this->getAmountWord()
            ]
        );

        // 9. created_time
        $fieldset->addField(
            'created_time',
            'date',
            [
                'name' => 'created_time',
                'label' => __('Created Time'),
                'date_format' => 'yyyy-MM-dd',
                'time_format' => 'hh:mm:ss',
                'style' => 'display:inline-block; width: 100%; padding: 5px 10px;',

            ]
        );

        // 10. mb_testimonial_updated_time
        // 11. is_active
        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => [ '2' => __('Approved'), '1' => __('Pending'), '0' => __('Rejected')]
            ]
        );
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('General Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('General Information');
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
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    public function getAmountWord(){
        $html = '';
        $html .= '\'input-text required-entry validate-length maximum-length-' . $this->_helper->getAmountWord() .' minimum-length-1\'';
        return $html;
    }

    public function getAfterImageElementHtml($field, $image)
    {
        $html = '';
        $html .= '<p style="margin-top: 5px">';
        $html .= '<image style="min-width: 300px;" src="' . $this->_helper->getImageUrl($image) . '" />';
        $html .= '<input type="hidden" value="' . $image . '" name="old_' . $field . '"/>';
        $html .= '</p>';
        return $html;
    }
}
