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
namespace Magestore\Megamenu\Block\Adminhtml\Megamenu\Widget\Chooser;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;

class Sku extends \Magento\Backend\Block\Widget\Grid\Extended {
    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $_catalogType;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_cpCollection;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $_cpCollectionInstance;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory
     */
    protected $_eavAttSetCollection;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $eavAttSetCollection
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $cpCollection
     * @param \Magento\Catalog\Model\Product\Type $catalogType
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $eavAttSetCollection,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $cpCollection,
        \Magento\Catalog\Model\Product\Type $catalogType,
        array $data = []
    ) {
        $this->_catalogType = $catalogType;
        $this->_cpCollection = $cpCollection;
        $this->_eavAttSetCollection = $eavAttSetCollection;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct() {
        parent::_construct();

        if ($this->getRequest()->getParam('current_grid_id')) {
            $this->setId($this->getRequest()->getParam('current_grid_id'));
        } else {
            $this->setId('skuChooserGrid_' . $this->getId());
        }

        $this->setDefaultSort('name');
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('collapse')) {
            $this->setIsCollapsed(true);
        }

    }

    /**
     * Grid Row JS Callback
     *
     * @return string
     */
    public function getRowClickCallback()
    {
        $js = "
            function (grid, event) {
                var trElement = Event.findElement(event, 'tr');
                var isInput = Event.element(event).tagName == 'INPUT';
                var input = $('products');
                if (trElement) {
                    var checkbox = Element.select(trElement, 'input');
                    if (checkbox[0]) {
                        var checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                        if(checked){
                            if(input.value == '')
                                input.value = checkbox[0].value;
                            else
                                input.value = input.value + ', '+checkbox[0].value;

                        }else{
                            var vl = checkbox[0].value;
                            if(input.value.search(vl) == 0){
                                if(input.value == vl) input.value = '';
                                input.value = input.value.replace(vl+', ','');
                            }else{
                                input.value = input.value.replace(', '+ vl,'');
                            }
                        }
                        checkbox[0].checked =  checked;
                        grid.reloadParams['selected[]'] = input.value.split( ', ');
                    }
                }
            }
        ";
        return $js;
    }
    public function getCheckboxCheckCallback(){
        $js = ' function (grid, element, checked) {

        var input = $("products");
        if (checked) {
            $$("#'.$this->getId().' input[type=checkbox][class=checkbox admin__control-checkbox]").each(function(e){
                if(e.name != "check_all"){
                    if(!e.checked){
                        if(input.value == "")
                            input.value = e.value;
                        else
                            input.value = input.value + ", "+e.value;
                        e.checked = true;
                        grid.reloadParams["selected[]"] = input.value.split(", ");
                    }
                }
            });
        }else{
            $$("#'.$this->getId().' input[type=checkbox][class=checkbox admin__control-checkbox]").each(function(e){
                if(e.name != "check_all"){
                    if(e.checked){
                        var vl = e.value;
                        if(input.value.search(vl) == 0){
                            if(input.value == vl) input.value = "";
                            input.value = input.value.replace(vl+", ","");
                        }else{
                            input.value = input.value.replace(", "+ vl,"");
                        }
                        e.checked = false;
                        grid.reloadParams["selected[]"] = input.value.split(", ");
                    }
                }
            });

        }
    } ';
        return $js;
    }
    public function getRowInitCallback(){
        $js =' function (grid, row) {
                    if($$(".input-text.admin__control-text.no-changes")[2].value!=""||
                    $$(".input-text.admin__control-text.no-changes")[1].value!="" ||
                    $$(".input-text.admin__control-text.no-changes")[0].value!=""||
                    $$(".no-changes.admin__control-select")[2].value!="" ||
                    $$(".no-changes.admin__control-select")[1].value!=""){
                             $$(".action-default")[7].show();
                             $$(".no-changes.admin__control-select")[0].show();
                    }
                    else{
                        $$(".action-default")[7].hide();
                        $$(".no-changes.admin__control-select")[0].hide();
                    }
                    grid.reloadParams["selected[]"] = $("products").value.split(", ");
        }

        ';
        return $js;
    }

    /**
     * @param Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column) {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $selected = $this->_getSelectedProducts();
            if (empty($selected)) {
                $selected = '';
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $selected]);
            } else {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $selected]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Prepare Catalog Product Collection for attribute SKU in Promo Conditions SKU chooser
     *
     * @return $this
     */
    protected function _prepareCollection() {
        $collection = $this->_getCpCollectionInstance()->setStoreId(
            0
        )->addAttributeToSelect(
            'name',
            'type_id',
            'attribute_set_id'
        );

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Get catalog product resource collection instance
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getCpCollectionInstance() {
        if (!$this->_cpCollectionInstance) {
            $this->_cpCollectionInstance = $this->_cpCollection->create();
        }
        return $this->_cpCollectionInstance;
    }

    /**
     * Define Cooser Grid Columns and filters
     *
     * @return $this
     */
    protected function _prepareColumns() {
        $this->addColumn(
            'in_products',
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_products',
                'values' => $this->_getSelectedProducts(),
                'align' => 'center',
                'index' => 'entity_id',
                'use_index' => true,
            ]
        );

        $this->addColumn(
            'entity_id',
            ['header' => __('ID'), 'sortable' => true, 'width' => '60px', 'index' => 'entity_id']
        );

        $this->addColumn(
            'type',
            [
                'header' => __('Type'),
                'width' => '60px',
                'index' => 'type_id',
                'type' => 'options',
                'options' => $this->_catalogType->getOptionArray(),
            ]
        );

        $sets = $this->_eavAttSetCollection->create()->setEntityTypeFilter(
            $this->_getCpCollectionInstance()->getEntity()->getTypeId()
        )->load()->toOptionHash();

        $this->addColumn(
            'set_name',
            [
                'header' => __('Attribute Set'),
                'width' => '100px',
                'index' => 'attribute_set_id',
                'type' => 'options',
                'options' => $sets,
            ]
        );

        $this->addColumn(
            'chooser_sku',
            ['header' => __('SKU'), 'name' => 'chooser_sku', 'width' => '80px', 'index' => 'sku']
        );
        $this->addColumn(
            'chooser_name',
            ['header' => __('Product'), 'name' => 'chooser_name', 'index' => 'name']
        );

        return parent::_prepareColumns();
    }
    /**
     * @return string
     */
    public function getGridUrl() {
        return $this->getUrl(
            'megamenuadmin/megamenu_widget/chooser',
            ['_current' => true, 'current_grid_id' => $this->getId(), 'collapse' => null]
        );
    }

    
    /**
     * @return mixed
     */
    protected function _getSelectedProducts() {
        $products = $this->getRequest()->getPost('selected', []);

        return $products;
    }
}
