<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Edit\Tab;

/**
 * Filters tab
 */
class Filters extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    protected $_attributeFactory = null;
    protected $_attributeOption = null;
    protected $_coreHelper = null;
    protected $_attributeSetRepository = null;
    protected $_attributeRepository = null;
    protected $_objectManager = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Eav\Model\Entity\AttributeFactory $attributeFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attributeOption
     * @param \Wyomind\Core\Helper\Data $coreHelper
     * @param \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSetRepository
     * @param \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository
     * @param \Magento\Framework\ObjectManager\ObjectManager $objectManager
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Eav\Model\Entity\AttributeFactory $attributeFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attributeOption,
        \Wyomind\Core\Helper\Data $coreHelper,
        \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSetRepository,
        \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository,
        \Magento\Framework\ObjectManager\ObjectManager $objectManager,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_attributeFactory = $attributeFactory;
        $this->_attributeOption = $attributeOption;
        $this->_coreHelper = $coreHelper;
        $this->_attributeSetRepository = $attributeSetRepository;
        $this->_attributeRepository = $attributeRepository;
        $this->_objectManager = $objectManager;
    }

    /**
     * @return \Magento\Backend\Block\Widget\Form\Generic
     * @throws \Magento\Framework\Exception\LocalizedException
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
     * @return int
     */
    public function getNbFilters()
    {
        return $this->_coreHelper->getStoreConfig("simplegoogleshopping/system/filters");
    }

    /**
     * @return int
     */
    public function getFiltersSql()
    {
        return $this->_coreHelper->getStoreConfig("simplegoogleshopping/system/filters_sql");
    }

    /**
     * @return strign
     */
    public function getSGSTypeIds()
    {
        $model = $this->_coreRegistry->registry('data_feed');
        return $model->getSimplegoogleshoppingTypeIds();
    }

    /**
     * @return string
     */
    public function getSGSAttributeSets()
    {
        $model = $this->_coreRegistry->registry('data_feed');
        return $model->getSimplegoogleshoppingAttributeSets();
    }

    /**
     * @return string
     */
    public function getSGSVisibility()
    {
        $model = $this->_coreRegistry->registry('data_feed');
        return $model->getSimplegoogleshoppingVisibility();
    }

    /**
     * @return string
     */
    public function getSGSAttributes()
    {
        $model = $this->_coreRegistry->registry('data_feed');
        return $model->getSimplegoogleshoppingAttributes();
    }

    /**
     * @return string
     */
    public function getAttributeSets()
    {

        $typeCode = \Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE;

        $filterGroup = $this->_objectManager->create('\Magento\Framework\Api\Search\FilterGroup');
        $filter = $this->_objectManager->create('\Magento\Framework\Api\Filter');
        $filter->setField('entity_type_code');
        $filter->setConditionType('eq');
        $filter->setValue($typeCode);
        $filterGroup->setFilters([$filter]);

        $searchCriteria = $this->_objectManager->create('\Magento\Framework\Api\SearchCriteria');
        $searchCriteria->setFilterGroups([$filterGroup]);

        return $this->_attributeSetRepository->getList($searchCriteria)->getItems();
    }

    /**
     * @param int $attId
     * @return array
     */
    public function getAttributeOptions($attId)
    {
        $att = $this->_attributeFactory->create()->load($attId);
        if ($att->getSourceModel() != "") {
            try {
                return $att->getSource()->getAllOptions();
            } catch (\Exception $e) {
                return [];
            }
        } else {
            $coll = $this->_attributeOption->create();
            return $coll->setAttributeFilter($attId)->setStoreFilter($this->getStoreId())->getData();
        }
    }

    /**
     * @return array
     */
    public function getAttributesList()
    {
        $typeCode = \Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE;
        $searchCriteria = $this->_objectManager->create('\Magento\Framework\Api\SearchCriteria');
        $attributeList = $this->_attributeRepository->getList($typeCode, $searchCriteria)->getItems();

        $tmp = [];
        foreach ($attributeList as $attribute) {
            $tmp[] = [
                "attribute_id" => $attribute->getAttributeId(),
                "attribute_code" => $attribute->getAttributeCode(),
                "frontend_label" => $attribute->getDefaultFrontendLabel()
            ];
        }

        $attributeList[] = ["attribute_code" => "entity_id", "frontend_label" => "Product Id"];
        $attributeList[] = ["attribute_code" => "qty", "frontend_label" => "Quantity"];
        $attributeList[] = ["attribute_code" => "is_in_stock", "frontend_label" => "Is in stock"];

        usort($attributeList, ['\Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Edit\Tab\Filters', 'cmp']);

        return $attributeList;
    }

    /**
     * @param array $a
     * @param array $b
     * @return int
     */
    public function cmp(
        $a,
        $b
    )
    {
        return ($a['frontend_label'] < $b['frontend_label']) ? -1 : 1;
    }

    /**
     * @return string
     */
    public function getJsData()
    {
        $attributeCodes = [];
        $attributeList = $this->getAttributesList();
        foreach ($attributeList as $attribute) {
            if (preg_match("/^[a-zA-Z0-9_]+$/", $attribute['attribute_code'])) {
                if (isset($attribute['attribute_id'])) {
                    $attributeOptions = $this->getAttributeOptions($attribute['attribute_id']);

                    $options = [];
                    foreach ($attributeOptions as $attributeOption) {
                        if ((string)$attributeOption["value"] != '') {
                            $options[] = ["value" => (isset($attributeOption['option_id'])) ? $attributeOption['option_id'] : $attributeOption['value'], "label" => isset($attributeOption['label']) ? $attributeOption['label'] : $attributeOption['value']];
                        }
                    }
                    if ($attribute['attribute_code'] != 'location') {
                        $attributeCodes[$attribute['attribute_code']] = $options;
                    }
                }
            }
        }
        return json_encode($attributeCodes);
    }

    /**
     * @return string
     */
    public function getSelectHtml()
    {
        $selectOutput = "";
        $attributeList = $this->getAttributesList();
        foreach ($attributeList as $attribute) {
            if (!empty($attribute['frontend_label'])) {
                $selectOutput .= "<option value='" . $attribute['attribute_code'] . "'>" . $attribute['frontend_label'] . "</option>";
            }
        }
        return $selectOutput;
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return __('Filters');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return __('Filters');
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
}
