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
namespace Magestore\Megamenu\Block\Adminhtml\Megamenu\Edit\Tab\Content;

class Maincontent extends \Magestore\Megamenu\Block\Adminhtml\Megamenu\Edit\Tab\AbstractBlock implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Main Content');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Main Content');
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
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $data = $this->getRegistryModel();
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();


        if(!isset($data))
            $data=null;
        $fieldset = $form->addFieldset('megamenu_submenu', ['legend' => __('General Configuration')]);

        if(!isset($data['submenu_width']) || !$data['submenu_width'])
            $data['submenu_width'] = 100;
        $fieldset->addField(
            'submenu_width1',
            'note',
            [
                'name' => 'submenu_width1',
                'label' => __('Width(%)'),
                'index'=> 'submenu_width1',
                'required' => true,
                'after_element_html' => '
            <input id="submenu_width" name="submenu_width" type="text" style="border:0; color:black; " readonly="">
                    <script type="text/javascript">
									require([
									"jquery",
									 "jquery/ui"
									], function  ($) {
                                        var submenu_width = "'.$data['submenu_width'].'";

                                        $( "#slider-range-max" ).slider({
                                          range: "max",
                                          min: 1,
                                          max: 100,
                                          value: submenu_width,
                                          slide: function( event, ui ) {
                                             $( "#submenu_width" ).val( ui.value );

                                          }
                                        });
                                        $( "#submenu_width" ).val( $( "#slider-range-max" ).slider( "value" ));

									});
					</script>

                    <div id="slider-range-max"></div>
                '
            ]
        );
        $fieldset->addField(
            'submenu_align',
            'select',
            [
                'name' => 'submenu_align',
                'label' => __('Alignment Type'),
                'values' => $this->_megaModel->getSubmenualignOptions()
            ]
        );
        $fieldset->addField(
            'leftsubmenu_align',
            'select',
            [
                'name' => 'leftsubmenu_align',
                'label' => __('Alignment Type'),
                'values' => $this->_megaModel->getLeftSubmenualignOptions()
            ]
        );


        $fieldset = $form->addFieldset('megamenu_maincontent', ['legend' => __('Main Content')]);

        $fieldset->addField(
            'menu_type',
            'select',
            [
                'name' => 'menu_type',
                'label' => __('Main Content Type'),
                'onchange' => 'toggleMenuType()',
                'values' => $this->_megaModel->getMenutypeOptions()
            ]
        );
        if(!isset($data['colum']) || !$data['colum'])
            $data['colum'] = 4;
        $fieldset->addField(
            'colum',
            'text',
            [
                'name' => 'colum',
                'label' => __('Number of Columns'),
                'index' =>  'colum',
            ]
        );
        $fieldset->addField(
            'categories_box_title',
            'text',
            [
                'name' => 'categories_box_title',
                'label' => __('Categories Box Title'),
                'index' =>  'categories_box_title',
            ]
        );
        $fieldset->addField(
            'products_box_title',
            'text',
            [
                'name' => 'products_box_title',
                'label' => __('Products Box Title'),
                'index' =>  'products_box_title',
                'value' =>  'Products',
            ]
        );
        $fieldset->addField(
            'category_type',
            'select',
            [
                'name' => 'category_type',
                'label' => __('Arrange Category Items by'),
                'index' =>  'category_type',
                'values' =>  $this->_megaModel->getCategorytypeOptions(),
            ]
        );
        $fieldset->addField(
            'category_image',
            'select',
            [
                'name' => 'category_image',
                'label' => __('Image shown'),
                'index' =>  'category_image',
                'values' =>  $this->_megaModel->getCategoryImageOptions(),
            ]
        );
        $fieldset->addField(
            'main_content',
            'editor',
            [
                'name' => 'main_content',
                'label' => __('Menu Content'),
                'index' =>  'main_content',
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );
        $categoryIds = implode(", ",
            $this->getCatalogCategoryColection()
            ->addFieldToFilter(
                'level',
                ['gt' => 0])->getAllIds()
                );
        if(!isset($data['categories'])){
            $data['categories'] = $categoryIds;
        }
        $fieldset->addField(
            'categories',
            'text',
            [
                'name' => 'categories',
                'label' => __('Categories'),
                'after_element_html' => '<a id="category_link" href="javascript:void(0)" onclick="toggleMainCategories()"><img src="' . $this->getRuleTrigerImage() . '" alt="" class="v-middle rule-chooser-trigger" title="Select Categories"></a>
                <div  id="categories_check" style="display:none">
                    <a href="javascript:toggleMainCategories(1)">Check All</a> / <a href="javascript:toggleMainCategories(2)">Uncheck All</a>
                </div>
                <div id="main_categories_select" style="display:none"></div>
                    <script type="text/javascript">
                    function toggleMainCategories(check){
                        var cate = $("main_categories_select");
                        if($("main_categories_select").style.display == "none" || (check ==1) || (check == 2)){
                        $("categories_check").style.display ="";
                            var url = "' . $this->getUrl('megamenuadmin/megamenu_category/chooser') . '";
                            if(check == 1){
                                $("categories").value = $("category_all_ids").value;
                            }else if(check == 2){
                                $("categories").value = "";
                            }
                            var params = $("categories").value.split(", ");
                            var parameters = {"form_key": FORM_KEY,"selected[]":params };
                            var request = new Ajax.Request(url,
                                {
                                    evalScripts: true,
                                    parameters: parameters,
                                    onComplete:function(transport){
                                        $("main_categories_select").update(transport.responseText);
                                        $("main_categories_select").style.display = "block";
                                    }
                                });
                        if(cate.style.display == "none"){
                            cate.style.display = "";
                        }else{
                            cate.style.display = "none";
                        }
                    }else{
                        cate.style.display = "none";
                        $("categories_check").style.display ="none";
                    }
                };
		</script>
            '
            ]
        );

        $collection= $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $productIds = implode(", ", $collection->getAllIds());
        $fieldset->addField(
            'products',
            'text',
            [
                'name' => 'products',
                'label' => __('Products'),
                'config' => $this->_wysiwygConfig->getConfig(),
                'class' => 'rule-param',
                'after_element_html' => '<a id="product_link" href="javascript:void(0)" onclick="toggleMainProducts()"><img src="' . $this->getRuleTrigerImage()  . '" alt="" class="v-middle rule-chooser-trigger" title="Select Products"></a><input type="hidden" value="'.$productIds.'" id="product_all_ids"/><div id="main_products_select" style="display:none;width:640px"></div>
                <script type="text/javascript">
                    function toggleMainProducts(){
                        if($("main_products_select").style.display == "none"){
                            var url = "' . $this->getUrl('megamenuadmin/megamenu_widget/chooser') . '";
                            var params = $("products").value.split(", ");
                            var parameters = {"form_key": FORM_KEY,"selected[]":params };
                            var request = new Ajax.Request(url,
                            {
                                evalScripts: true,
                                parameters: parameters,
                                onComplete:function(transport){
                                    $("main_products_select").update(transport.responseText);
                                    $("main_products_select").style.display = "block";
                                }
                            });
                        }else{
                            $("main_products_select").style.display = "none";
                        }
                    };


                </script>'
            ]
        );
        $fieldset->addField(
            'product_label',
            'text',
            [
                'name' => 'product_label',
                'label' => __('Product Label'),
                'index' =>  'product_label',
            ]
        );
        $fieldset->addField(
            'product_label_color',
            'text',
            [
                'name' => 'product_label_color',
                'label' => __('Product Label Color'),
                'index' =>  'product_label_color',
                'class' => 'jscolor',
                'values' => 'ff0000',
            ]
        );
        $fieldset->addField(
            'products_using_label',
            'text',
            [
                'name' => 'products_using_label',
                'label' => __('Products Using Label'),
                'class' => 'rule-param',
                'after_element_html' => '<a id="product_link2" href="javascript:void(0)" onclick="toggleMainProducts2()"><img src="' . $this->getRuleTrigerImage() . '" alt="" class="v-middle rule-chooser-trigger" title="Select Products"></a><input type="hidden" value="'.$productIds.'" id="product_all_ids2"/><div id="main_products_select2" style="display:none;width:640px"></div>
                <script type="text/javascript">
                    function toggleMainProducts2(){
                        if($("main_products_select2").style.display == "none"){
                            var url = "' . $this->getUrl('megamenuadmin/megamenu_widget/chooserlabel') . '";
                            var params = $("products_using_label").value.split(", ");
                            var parameters = {"form_key": FORM_KEY,"selected[]":params };
                            var request = new Ajax.Request(url,
                            {
                                evalScripts: true,
                                parameters: parameters,
                                onComplete:function(transport){
                                    $("main_products_select2").update(transport.responseText);
                                    $("main_products_select2").style.display = "block";
                                }
                            });
                        }else{
                            $("main_products_select2").style.display = "none";
                        }
                    };


                </script>'
            ]
        );
        $fieldset->addField(
            'number_products',
            'text',
            [
                'name' => 'number_products',
                'label' => __('Number of Products shown'),
                'note'       => 'If the value is 0 or empty, it will show all products',
            ]
        );
        $fieldset->addField(
            'view_all',
            'select',
            [
                'name' => 'view_all',
                'label' => __('Show View all link'),
                'values'    => $this->_megaModel->getCategoryImageOptions(),
            ]
        );


        $form->setValues($data->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
    public function getLoadUrl() {
        return $this->getUrl('*/*/chooser');
    }
}
