<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Adminhtml\Field\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var GroupFactory
     */
    protected $groupFactory;
    /**
     * @var \Amasty\Checkout\Model\Field
     */
    protected $fieldSingleton;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Amasty\Checkout\Block\Adminhtml\Field\Edit\GroupFactory $groupFactory,
        \Amasty\Checkout\Model\Field $fieldSingleton,
        array $data
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->groupFactory = $groupFactory;
        $this->fieldSingleton = $fieldSingleton;
    }

    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save', ['_current' => true]),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                ],
            ]
        );
        $form->setHtmlIdPrefix('field_');
        $form->setUseContainer(true);
        
        $visible = $this->addGroup(
            $form,
            'visible_fields',
            __('Enabled Checkout Fields'),
            1
        );
        
        $invisible = $this->addGroup(
            $form,
            'invisible_fields',
            __('Disabled Checkout Fields'),
            0
        );

        $storeId = $this->_request->getParam('store', null);

        /** @var \Amasty\Checkout\Model\Field $field */
        foreach ($this->fieldSingleton->getConfig($storeId) as $field) {
            $targetGroup = $field->getData('enabled') ? $visible : $invisible;

            $targetGroup->addRow('field_' . $field->getData('attribute_id'), ['field' => $field]);
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }

    public function addGroup(\Magento\Framework\Data\Form $form, $id, $title, $enabled)
    {
        /** @var \Amasty\Checkout\Block\Adminhtml\Field\Edit\Group $group */
        $group = $this->groupFactory->create();
        $group->setId($id);
        $group->setRenderer(\Amasty\Checkout\Block\Adminhtml\Field\Edit\Group::getGroupRenderer());
        $group->setData('title', $title);
        $group->setData('enabled', $enabled);

        $form->addElement($group);

        return $group;
    }

    protected function _prepareLayout()
    {
        \Amasty\Checkout\Block\Adminhtml\Field\Edit\Group::setRowRenderer(
            $this->getLayout()->createBlock(
                'Amasty\Checkout\Block\Adminhtml\Field\Edit\Group\Row\Renderer',
                $this->getNameInLayout() . '_row_element'
            )
        );

        \Amasty\Checkout\Block\Adminhtml\Field\Edit\Group::setGroupRenderer(
            $this->getLayout()->createBlock(
                'Amasty\Checkout\Block\Adminhtml\Field\Edit\Group\Renderer',
                $this->getNameInLayout() . '_group_element'
            )
        );

        return parent::_prepareLayout();
    }
}
