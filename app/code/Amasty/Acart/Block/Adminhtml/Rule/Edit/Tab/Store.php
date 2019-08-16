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
use Magento\Framework\Convert\DataObject as ObjectConverter;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Api\GroupRepositoryInterface;

class Store extends Generic implements TabInterface
{
    protected $_objectConverter;

    protected $_salesRule;

    protected $groupRepository;

    protected $_searchCriteriaBuilder;

    protected $_systemStore;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ObjectConverter $objectConverter,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_objectConverter = $objectConverter;
        $this->groupRepository = $groupRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    public function getTabLabel()
    {
        return __('Stores & Customer Groups');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return __('Stores & Customer Groups');
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

        if (!$this->_storeManager->isSingleStoreMode()) {
            $fldStore = $form->addFieldset('apply_in', ['legend' => __('Apply In')]);
            $field = $fldStore->addField(
                'store_ids',
                'multiselect',
                [
                    'name' => 'store_ids[]',
                    'label' => __('Stores'),
                    'title' => __('Stores'),
                    'values' => $this->_systemStore->getStoreValuesForForm(),
                    'note' => __('Leave empty or select all to apply the campaign to any store'),
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        }

        $fldCust = $form->addFieldset('apply_for', ['legend' => __('Apply For')]);

        if ($this->_storeManager->isSingleStoreMode()) {
            $storeId = $this->_storeManager->getStore(true)->getStoreId();
            $fldCust->addField('store_ids', 'hidden', ['name' => 'store_ids[]', 'value' => $storeId]);
            $model->setStoreIds($storeId);
        }

        $groups = $this->groupRepository
            ->getList($this->_searchCriteriaBuilder->create())
            ->getItems();
        $fldCust->addField(
            'customer_group_ids',
            'multiselect',
            [
                'name' => 'customer_group_ids[]',
                'label' => __('Customer Groups'),
                'title' => __('Customer Groups'),
                'values' => $this->_objectConverter->toOptionArray($groups, 'id', 'code'),
                'note' => __('Leave empty or select all to apply the campaign to any group'),

            ]
        );

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
