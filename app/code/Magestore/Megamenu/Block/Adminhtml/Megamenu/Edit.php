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
namespace Magestore\Megamenu\Block\Adminhtml\Megamenu;

/**
 * Form containerEdit
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
//    protected $_template = 'Magestore_Megamenu::edit.phtml';
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_objectId = 'megamenu_id';
        $this->_blockGroup = 'Magestore_Megamenu';
        $this->_controller = 'adminhtml_megamenu';
        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Megamenu'));
        $this->buttonList->update('delete', 'label', __('Delete'));

        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
                ],
            ],
            -100
        );

        $this->buttonList->add(
            'new-button',
            [
                'label' => __('Save and New'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndNew', 'target' => '#edit_form'],
                    ],
                ],
            ],
            10
        );

        $this->_formScripts[] = '
            function toggleEditor() {
                if (tinyMCE.getInstanceById("block_content") == null) {
                    tinyMCE.execCommand("mceAddControl", false, "block_content");
                } else {
                    tinyMCE.execCommand("mceRemoveControl", false, "block_content");
                }
            }

            function toggleMenuType(){

                        if($("menu_type").value == "4"){
                            if($("colum"))
                             $("colum").up(1).hide();
                            if($("categories_box_title"))
                                $("categories_box_title").up(1).hide();
                            if($("products_box_title"))
                                $("products_box_title").up(1).hide();
                            if($("category_type"))
                                $("category_type").up(1).hide();
                            if($("product_label"))
                                $("product_label").up(1).hide();
                            if($("product_label_color"))
                                $("product_label_color").up(1).hide();
                            if($("view_all"))
                                 $("view_all").up(1).hide();
                            if($("category_image"))
                                $("category_image").up(1).hide();
                            if($("main_content"))
                                $("main_content").up(2).hide();
                            if($("categories"))
                                $("categories").up(2).hide();
                            if($("products"))
                                $("products").up(2).hide();
                            if($("products_using_label"))
                                $("products_using_label").up(2).hide();
                            if($("number_products"))
                                $("number_products").up(1).hide();
                            if($("footer"))
                                $("footer").up(3).hide();
                            if($("submenu_align"))
                                $("submenu_align").up(2).hide();
                            if($("featured_type"))
                            $("featured_type").up(3).hide();
                        }else if($("menu_type").value == "1"){
                            $("category_type").up(1).hide();
			    $("view_all").up(1).hide();
                            $("product_label").up(1).hide();
                            $("product_label_color").up(1).hide();
                            $("products_using_label").up(2).hide();
                            $("colum").up(1).hide();
                            $("categories_box_title").up(1).hide();
                            $("products_box_title").up(1).hide();
                            $("main_content").up(2).show();
                            $("categories").up(2).hide();
                            $("products").up(2).hide();
                            $("megamenu_submenu").show();
                            $("category_image").up(1).hide();
                            $("number_products").up(1).hide();
                            $("footer").up(3).show();
                            $("main_content").up(1).show();
                            $("featured_type").up(3).show();
                        }else if($("menu_type").value == "2" || $("menu_type").value == "5"){
                            $("category_type").up(1).hide();
                            $("product_label").up(1).show();
                            $("product_label_color").up(1).show();
			    $("view_all").up(1).hide();
                            $("products_using_label").up(1).show();
                            $("colum").up(1).show();
                            $("categories_box_title").up(1).hide();
                            $("products_box_title").up(1).show();
                            $("main_content").up(1).hide();
                            $("categories").up(2).hide();
                            $("products").up(1).show();
                            $("megamenu_submenu").show();
                            $("number_products").up(1).hide();
                            $("category_image").up(1).hide();
                            $("products").up(2).show();
                            $("products_using_label").up(2).show();
                            $("footer").up(3).show();
                            $("featured_type").up(3).show();
                            $("buttonsmain_content").up(2).hide();
                        }else if($("menu_type").value == "3"){
                            $("category_type").up(1).show();
                            $("product_label").up(1).hide();
			    $("view_all").up(1).hide();
                            $("product_label_color").up(1).hide();
                            $("products_using_label").up(2).hide();
                            $("colum").up(1).show();
                            $("categories_box_title").up(1).show();
                            $("products_box_title").up(1).hide();
                            $("main_content").up(1).hide();
                            $("products").up(2).hide();
                            $("megamenu_submenu").show();
                            $("number_products").up(1).hide();
                            $("category_image").up(1).hide();
                            $("footer").up(3).show();
                            $("categories").up(2).show();
                            $("featured_type").up(3).show();
                            $("buttonsmain_content").up(2).hide();
                        }else if($("menu_type").value == "6"){
                            $("category_type").up(1).hide();
                            $("product_label").up(1).hide();
                            $("product_label_color").up(1).hide();
                            $("products_using_label").up(2).hide();
			                $("view_all").up(1).hide();
                            $("colum").up(1).hide();
                            $("categories_box_title").up(1).hide();
                            $("products_box_title").up(1).hide();
                            $("main_content").up(1).hide();
                            $("products").up(2).hide();
                            $("categories").up(1).show();
                            $("megamenu_submenu").show();
                            $("category_image").up(1).hide();
                            $("number_products").up(1).hide();
                            $("categories").up(2).show();
                            $("main_content").up(2).hide();
                            $("featured_type").up(3).hide();
                            if($("footer"))
                                $("footer").up(3).show();
                        }else if($("menu_type").value == "7"){

                            $("category_type").up(1).show();
                            $("product_label").up(1).hide();
                            $("product_label_color").up(1).hide();
                            $("products_using_label").up(2).hide();
                            $("colum").up(1).show();
                            $("categories_box_title").up(1).show();
                            $("products_box_title").up(1).hide();
                            $("main_content").up(1).hide();
                            $("categories").up(1).show();
                            $("products").up(2).hide();
                            $("megamenu_submenu").show();
                            $("categories").up(2).show();
                            $("categories_box_title").up(1).hide();
                            $("category_type").up(1).hide();
                            $("number_products").up(1).hide();
                            $("view_all").up(1).show();
                             $("category_image").up(1).show();
                            $("featured_type").up(3).show();
                            $("buttonsmain_content").up(2).hide();
                            $("footer").up(3).show();

                        }else if($("menu_type").value == "8"){
                            $("category_type").up(1).show();
                            $("product_label").up(1).hide();
                            $("product_label_color").up(1).hide();
							$("view_all").up(1).hide();
                            $("products_using_label").up(1).hide();
                            $("colum").up(1).show();
                            $("categories_box_title").up(1).show();
                            $("products_box_title").up(1).hide();
                            $("main_content").up(1).hide();
                            $("products").up(1).hide();
                            $("megamenu_submenu").show();
                            $("category_image").up(1).show();
                            $("category_type").up(1).hide();
                            $("number_products").up(1).show();
                            $("categories_box_title").up(1).hide()
                            $("categories").up(2).show();
                            $("products").up(2).hide();
                            $("products_using_label").up(2).hide();
                            $("footer").up(3).show();
                            $("featured_type").up(3).show();
                            $("buttonsmain_content").up(2).hide();
                        }
                    }
                    function changeMegamenuType(){
                        if($("megamenu_type").value=="1"){
                            $("submenu_align").up(1).hide();
                            $("leftsubmenu_align").up(1).show();
                        }else{
                            $("submenu_align").up(1).show();
                            $("leftsubmenu_align").up(1).hide();
                        }
                    }


                    	require(["prototype",
                            ], function  () {
                              Event.observe(window, "load", function(){toggleMenuType();changeMegamenuType();
                              	if($("name_menu").value == "")
                              toggleMainCategories(2);
                              });

                    });
                    require([
                            "jquery",
                            "underscore",
                            "mage/mage",
                            "mage/backend/tabs",
                            "domReady!"
                        ], function($) {
                            var $form = $(\'#edit_form\');

                            $form.mage(\'form\', {
                                handlersData: {
                                    save: {},
                                    saveAndNew: {
                                        action: {
                                            args: {back: \'new\'}
                                        }
                                    },
                                }
                            });

                        });

        ';
    }

}
