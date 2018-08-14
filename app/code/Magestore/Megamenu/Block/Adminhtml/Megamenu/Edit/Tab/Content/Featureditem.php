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

class Featureditem extends \Magestore\Megamenu\Block\Adminhtml\Megamenu\Edit\Tab\AbstractBlock implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Featured Item');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Featured Item');
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


        $fieldset = $form->addFieldset('megamenu_featureditem', ['legend' => __('Featured Content')]);

        $categoryIds = implode(", ",
            $this->getCatalogCategoryColection()
                ->addFieldToFilter(
                    'level',
                    ['gt' => 0])->getAllIds()
        );
        $fieldset->addField(
            'featured_type',
            'select',
            [
                'name' => 'featured_type',
                'label' => __('Featured Type'),
                'onchange' => 'changeType()',
                'values' => $this->_megaModel->getFeaturedTypes(),
                'after_element_html' => '
                <input type="hidden" value="' . $categoryIds . '" id="category_all_ids" />'
            ]
        );


        if(!isset($data['featured_width']))
            $data['featured_width'] = 30;



        $fieldset->addField(
            'featured_width1',
            'note',
            [
                'name' => 'featured_width1',
                'label' => __('Width(%)'),
                'index'=> 'featured_width1',
                'required' => false,
                'after_element_html' => '
                    <input id="featured_width" name="featured_width" type="text" style="border:0; color:black; " readonly="">
                    <script type="text/javascript">
									require([
									"jquery",
									 "jquery/ui"
									], function  ($) {

                                         var featurewidth = "'.$data['featured_width'].'";
                                        $( "#slider-range-max1" ).slider({
                                          range: "max",
                                          min: 1,
                                          max: 100,
                                          value: featurewidth,
                                          slide: function( event, ui ) {
                                             $( "#featured_width" ).val( ui.value );

                                          }
                                        });
                                        $( "#featured_width" ).val( $( "#slider-range-max1" ).slider( "value" ));

									});
					</script>
                    <div id="slider-range-max1"></div>
                '
            ]
        );
        $fieldset->addField(
            'featured_title',
            'text',
            [
                'name' => 'featured_title',
                'label' => __('Featured Box Title'),
                'index' => 'featured_title',
            ]
        );
        $fieldset->addField(
            'featured_content',
            'editor',
            [
                'name' => 'featured_content',
                'label' => __('Featured Content'),
                'title' => __('Featured Content'),
                'style' => 'height:16em',
                'required' => false,
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );
        if(!isset($data['featured_column']) || !$data['featured_column'])
            $data['featured_column'] = 2;
        $fieldset->addField(
            'featured_column',
            'text',
            [
                'name' => 'featured_column',
                'label' => __('Featured Column Number'),
                'index' => 'featured_column',
            ]
        );

        $fieldset->addField(
            'featured_categories',
            'text',
            [
                'name' => 'featured_categories',
                'label' => __('Featured Categories'),
                'disabled' => 'disabled',
                'after_element_html' => '<a id="category_link" href="javascript:void(0)" onclick="toggleFeaturedCategories()"><img src="' . $this->getRuleTrigerImage() . '" alt="" class="v-middle rule-chooser-trigger" title="Select Categories"></a>
            <div  id="featured_categories_check" style="display:none">
                <a href="javascript:toggleFeaturedCategories(1)">Check All</a> / <a href="javascript:toggleFeaturedCategories(2)">Uncheck All</a>
            </div>
            <div id="featured_categories_select" style="display:none"> </div>
                <script type="text/javascript">
                    var count = 1;
                    function toggleFeaturedCategories(check){
                        count = count + 1;
                        if($("featured_categories_select").style.display == "none" || (check ==1) || (check == 2)){
                        $("featured_categories_check").style.display ="";
                            var url = "' . $this->getUrl("megamenuadmin/megamenu_category/chooserFeaturedCategories") . '";
                            if(check == 1){
                                $("featured_categories").value = $("category_all_ids").value;
                            }else if(check == 2){
                                $("featured_categories").value = "";
                            }
                            var params = $("featured_categories").value.split(", ");
                            var parameters = {"form_key": FORM_KEY,"selected[]":params };
                            var request = new Ajax.Request(url,
                                {
                                    evalScripts: true,
                                    parameters: parameters,
                                    onComplete:function(transport){
                                        $("featured_categories_select").update(transport.responseText);
                                        $("featured_categories_select").style.display = "block";
                                    }
                                }
                            );
                        }else{
                              $("featured_categories_select").style.display = "none";
                                 $("featured_categories_check").style.display ="none";
                        }
                    };
                    </script>
                    ',
                'note' =>  __('Upload images of categories selected for the best result.')
            ]
        );

        $collection= $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $productIds = implode(", ", $collection->getAllIds());
        $fieldset->addField(
            'featured_products',
            'text',
            [
                'name' => 'featured_products',
                'label' => __('Featured Products'),
                'disabled' => 'disabled',
                'class' => 'rule-param',
                'after_element_html' => '<a id="item_product_link" href="javascript:void(0)" onclick="toggleFeaturedProducts()"><img src="' . $this->getRuleTrigerImage() . '" alt="" class="v-middle rule-chooser-trigger" title="Select Products"></a><input type="hidden" value="' . $productIds . '" id="item_product_all_ids"/><div id="featured_products_select" style="display:none;width:640px"></div>
		<script type="text/javascript">
                    function toggleFeaturedProducts(){
                        if($("featured_products_select").style.display == "none"){
                        var url = "' . $this->getUrl('megamenuadmin/megamenu_widget/chooserFeatured') . '";
                        var params = $("featured_products").value.split(", ");
                        var parameters = {"form_key": FORM_KEY,"selected[]":params };
                        var request = new Ajax.Request(url,
                            {
                                evalScripts: true,
                                parameters: parameters,
                                onComplete:function(transport){
                                    $("featured_products_select").update(transport.responseText);
                                    $("featured_products_select").style.display = "block";
                                }
                            });
                            }else{
                                $("featured_products_select").style.display = "none";
                            }
                    };
                     var featured_grid;
                    function constructFeaturedData(div){
                        featured_grid = window[div.id+"JsObject"];
                        if(!featured_grid.reloadParams){
                            featured_grid.reloadParams = {};
                            featured_grid.reloadParams["selected[]"] = $("featured_products").value.split(", ");
                        }
                    }
                    function selectFeaturedProduct(e) {
                        if(e.checked == true){
                            if(e.id == "featured_on"){
                                $("featured_products").value = $("item_product_all_ids").value;
                            }else{
                                if($("featured_products").value == "")
                                    $("featured_products").value = e.value;
                                else
                                    $("featured_products").value = $("featured_products").value + ", "+e.value;
                            }
                            featured_grid.reloadParams["selected[]"] = $("featured_products").value.split(", ");
                        }else{
                             if(e.id == "featured_on"){
                                $("featured_products").value = "";
                            }else{
                                var vl = e.value;
                                if($("featured_products").value.search(vl) == 0){
                                    $("featured_products").value = $("featured_products").value.replace(vl+", ","");
                                }else{
                                    $("featured_products").value = $("featured_products").value.replace(", "+ vl,"");
                                }
                            }
                        }
                    }

                    function changeType(){
                        if($("featured_type").value == "3"){
                            if( $("slider-range-max1"))
                                  $("slider-range-max1").up(2).show();
                            $("featured_content").up(2).show();
                           $("featured_title").up(1).hide();
                           $("featured_column").up(1).hide();
                           $("featured_categories").up(2).hide();
                           $("featured_products").up(2).hide();

                        }else if($("featured_type").value == "2"){
                            if($("featured_width_slide"))
                                $("featured_width_slide").up(2).show();
                            $("featured_title").up(1).show();
                            $("featured_column").up(1).show();
                             $("featured_categories").up(2).show();
                              $("featured_products").up(2).hide();
                              $("featured_content").up(2).hide();
                               $("featured_categories").disabled=false;

                        }else if($("featured_type").value == "1"){
                            if($("featured_width_slide"))
                                $("featured_width_slide").up(2).show();
                            if($("slider-range-max1"))
                                $("slider-range-max1").up(2).show();
                            $("featured_title").up(1).show();
                            $("featured_column").up(1).show();
                            $("featured_products").up(2).show();
                            $("featured_content").up(2).hide();
                            $("featured_categories").up(2).hide();
                            $("featured_products").disabled=false;
                        }else{
                           if($("featured_width_slide"))
                                $("featured_width_slide").up(2).hide();
                           if($("slider-range-max1"))
                                $("slider-range-max1").up(2).hide();
                           $("featured_title").up(1).hide();
                           $("featured_content").up(2).hide();
                           $("featured_column").up(1).hide();
                           $("featured_categories").up(2).hide();
                           $("featured_products").up(2).hide();
                        }
                    }
                    require(["prototype",
                            ], function  () {
                              Event.observe(window, "load", function(){changeType();});

                    });
                </script>'
            ]
        );


        $form->setValues($data->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
