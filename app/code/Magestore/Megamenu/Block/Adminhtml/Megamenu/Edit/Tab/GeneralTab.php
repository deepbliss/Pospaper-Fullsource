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
namespace Magestore\Megamenu\Block\Adminhtml\Megamenu\Edit\Tab;

use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Class Tab GeneralTab
 */
class GeneralTab extends \Magento\Backend\Block\Widget\Form\Generic implements TabInterface
{
    /**
     * @var \Magestore\Megamenu\Model\Megamenu
     */
    protected $_megamenuModel;
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected  $_storeManager;

    /**
     * GeneralTab constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magestore\Megamenu\Model\Megamenu $megamenuModel
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magestore\Megamenu\Model\Megamenu $megamenuModel,
        array $data = array()
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_storeManager= $context->getStoreManager();
        $this->_systemStore =$systemStore;
        $this->_megamenuModel =$megamenuModel;
    }

    /**
     * get registry model.
     *
     * @return \Magento\Framework\Model\AbstractModel|null
     */
    public function getRegistryModel()
    {
        return $this->_coreRegistry->registry('megamenu_model');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('General information');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('General information');
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
     * {@inheritdoc}
     */
    protected function _prepareForm()
    {
        $model = $this->getRegistryModel();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

//        $form->setHtmlIdPrefix('helloword_');

        $fieldset = $form->addFieldset('general_fieldset', ['legend' => __('General Information')]);

        if ($model->getId()) {
            $fieldset->addField('megamenu_id', 'hidden', ['name' => 'megamenu_id']);
        }

        $fieldset->addField(
            'name_menu',
            'text',
            [
                'name' => 'name_menu',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
            ]
        );
        if ($this->_storeManager->isSingleStoreMode()) {
            $fieldset->addField(
                'stores',
                'hidden',
                [
                    'title' => __('Store View'),
                    'label' => __('Store View'),
                    'name' => 'stores[]',
                    'required' => true,
                ]
            );
        } else {
            $fieldset->addField(
                'stores',
                'multiselect',
                [
                    'title' => __('Store View'),
                    'label' => __('Store View'),
                    'name' => 'stores[]',
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true),
                ]
            );
        }
        $fieldset->addField(
            'link',
            'text',
            [
                'name' => 'link',
                'label' => __('Link'),
                'title' => __('Link'),
                'required' => false,
            ]
        );
        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name' => 'sort_order',
                'label' => __('Sort Order'),
                'title' => __('Sort Order'),
                'required' => false,
            ]
        );

        $fieldset->addField(
            'item_icon',
            'image',
            [
                'name' => 'item_icon',
                'label' => __('Menu Item Icon'),
                'title' => __('Menu Item Icon'),
                'note' => __('Supported file types: .jpeg, .jpg, .gif, .png
15 x 15 px is recommended.'),
            ]
        );


       $fieldset->addField(
            'megamenu_type',
            'select',
            [
                'name' => 'megamenu_type',
                'label' => __('Menu Type'),
                'onchange' => 'changeMegamenuType()',
                'values' => $this->_megamenuModel->getMegamenutypeOptions(),
            ]
        );

        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'options' => \Magestore\Megamenu\Model\Status::getAvailableStatuses(),
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }


}
