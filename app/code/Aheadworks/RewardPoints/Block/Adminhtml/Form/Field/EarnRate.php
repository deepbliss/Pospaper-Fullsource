<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Adminhtml\Form\Field;

use Aheadworks\RewardPoints\Model\Source\Customer\Group as CustomerSourceGroup;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\Factory as ElementFactory;
use Magento\Store\Model\System\Store as SystemStore;
use Magento\Framework\Json\Helper\Data as JsonHelperData;

/**
 * Class Aheadworks\RewardPoints\Block\Adminhtml\Form\Field\EarnRate
 */
class EarnRate extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * @var string
     */
    protected $_template = 'Aheadworks_RewardPoints::form/field/rates.phtml';

    /**
     * @var ElementFactory
     */
    private $elementFactory;

    /**
     * @var SystemStore
     */
    private $systemStore;

    /**
     * @var CustomerSourceGroup
     */
    private $customerGroup;

    /**
     * @var JsonHelperData
     */
    private $jsonHelperData;

    /**
     * @param Context $context
     * @param ElementFactory $elementFactory
     * @param SystemStore $systemStore
     * @param CustomerSourceGroup $customerGroup
     * @param JsonHelperData $jsonHelperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        ElementFactory $elementFactory,
        SystemStore $systemStore,
        CustomerSourceGroup $customerGroup,
        JsonHelperData $jsonHelperData,
        array $data = []
    ) {
        $this->elementFactory = $elementFactory;
        $this->systemStore = $systemStore;
        $this->customerGroup = $customerGroup;
        $this->jsonHelperData = $jsonHelperData;
        parent::__construct($context, $data);
    }

    /**
     * Define columns
     *
     * @return void
     */
    protected function _construct()
    {
        $this->addColumn('website_id', [
            'label' => __('Web Site'),
            'class' => 'required-entry',
        ]);
        $this->addColumn('customer_group_id', [
            'label' => __('Customer Group'),
            'class' => 'required-entry',
        ]);
        $this->addColumn('lifetime_sales_amount', [
            'label' => __('Customer Lifetime Sales >='),
            'class' => 'required-entry validate-digits',
        ]);
        $this->addColumn('base_amount', [
            'label' => __('Base Currency'),
            'class' => 'required-entry validate-digits validate-greater-than-zero',
        ]);
        $this->addColumn('points', [
            'label' => __('Points'),
            'class' => 'required-entry validate-digits validate-greater-than-zero',
        ]);
        $this->_addAfter = false;
        $this->setAddButtonLabel(__('Add Earn Rate'));
        $this->setHtmlId('earn_rate');
        parent::_construct();
    }

    /**
     * {@inheritDoc}
     */
    public function renderCellTemplate($columnName)
    {
        switch ($columnName) {
            case 'website_id':
                return $this->createHtmlSelectElement(
                    $columnName,
                    $this->systemStore->getWebsiteValuesForForm()
                );
                break;
            case 'customer_group_id':
                return $this->createHtmlSelectElement(
                    $columnName,
                    $this->customerGroup->toOptionArray()
                );
                break;
            default:
                return parent::renderCellTemplate($columnName);
                break;
        }
    }

    /**
     * Retrive default value for columns
     *
     * @return string
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function getDefaultValueJson()
    {
        $defaultValues = [];
        foreach ($this->getColumns() as $columnName => $column) {
            $defaultValues[$columnName] = '';
        }

        $defaultValues['option_extra_attrs'] = [];

        return $this->jsonHelperData->jsonEncode($defaultValues);
    }

    /**
     * Retrive template values
     *
     * @return string
     */
    public function getTemplateValueJson()
    {
        $templateValues = [];
        foreach ($this->getArrayRows() as $_rowId => $_row) {
            $templateValues[$_rowId] = $_row->toArray();
        }

        return $this->jsonHelperData->jsonEncode($templateValues);
    }

    /**
     * {@inheritDoc}
     */
    protected function _getCellInputElementId($rowId, $columnName)
    {
        return $rowId . '_' . $columnName . '_' . $this->getHtmlId();
    }

    /**
     * Create the dropdown element and retrieve it html string
     *
     * @param  string $columnName
     * @param  array|Traversable $options
     * @return string
     */
    protected function createHtmlSelectElement($columnName, $options)
    {
        $element = $this->createSelectElement($columnName, $options);
        return str_replace("\n", '', $element->getElementHtml());
    }

    /**
     * Create the dropdown element
     *
     * @param  string $columnName
     * @param  array|Traversable $options
     * @return \Magento\Framework\Data\Form\Element\Select
     */
    protected function createSelectElement($columnName, $options)
    {
        $column = $this->_columns[$columnName];

        $element = $this->elementFactory->create('select');
        $element->setForm($this->getForm())
            ->setName($this->_getCellInputElementName($columnName))
            ->setHtmlId($this->_getCellInputElementId('<%- _id %>', $columnName))
            ->setValues($options)
            ->setStyle(isset($column['style']) ? $column['style'] : '');
        return $element;
    }
}
