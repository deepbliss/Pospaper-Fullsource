/**
 * @category Webkul
 * @package Webkul_AdavncedLayeredNavigation
 * @author Webkul
 * @copyright Copyright (c) 2010-2018 Webkul Software Private Limited (https://webkul.com)
 * @license https://store.webkul.com/license.html
 */
define([
    "jquery",
    "jquery/ui",
    "Webkul_AdvancedLayeredNavigation/js/jquerynstSlider"
    ], function ($) {
        'use strict';
        $.widget('mage.layerednavigation', {
            options: {},
            _create: function () {
                var self = this;
                var just_loaded = 0;
                var min_val;
                var max_val;
                var clear_url;
                var ajaxcall=false;
                $(document).ready(function () {
                    $('#maincontent').on('click',".filter-options-item", function () {
                        setTimeout(function () {
                            initialize_price_slider();
                        }, 1);
                    });
                    $('#maincontent').on('click',"#mode-list", function () {
                        if (ajaxcall) {
                            if ($(location).attr('href').indexOf('?') > 0) {
                                location.href = $(location).attr("href")+"&product_list_mode=list";
                            } else {
                                location.href = $(location).attr("href")+"?product_list_mode=list";
                            }
                        }
                    });
                    $('#maincontent').on('click',"#mode-grid", function () {
                        if (ajaxcall) {
                            location.href = $(location).attr("href").replace("product_list_mode=list", '');
                        }
                    });
                    $('#maincontent').on('click',".sorter-action", function () {
                        if (ajaxcall) {
                            var dataval = $(this).attr('data-value');
                            if (dataval == 'asc') {
                                location.href = $(location).attr("href").replace("product_list_dir=desc", '');
                            } else {
                                if ($(location).attr('href').indexOf('?') > 0) {
                                    location.href = $(location).attr("href")+"&product_list_dir=desc";
                                } else {
                                    location.href = $(location).attr("href")+"?product_list_dir=desc";
                                }
                            }
                        }
                    });
                    $('#maincontent').on('change',".sorter-options", function () {
                        if (ajaxcall) {
                            var val = $(this).val();
                            var url = $(location).attr("href");
                            if ($(location).attr('href').search('product_list_order') > 0) {
                                var urldata = $(location).attr('href').split('product_list_order=');
                                var url = $(location).attr("href").replace("product_list_order="+urldata[1].split('&')[0], '');
                            }
                            if (val != 'position') {
                                if ($(location).attr('href').indexOf('?') > 0) {
                                    location.href = url+"&product_list_order="+val;
                                } else {
                                    location.href = url+"?product_list_order="+val;
                                }
                            } else {
                                location.href = url;
                            }
                        }
                    });
                    $('#maincontent').on('change',".limiter-options", function () {
                        if (ajaxcall) {
                            var val = $(this).val();
                            var url = $(location).attr("href");
                            if ($(location).attr('href').search('product_list_limit') > 0) {
                                var urldata = $(location).attr('href').split('product_list_limit=');
                                var url = $(location).attr("href").replace("product_list_limit="+urldata[1].split('&')[0], '');
                            }
                            if ($(location).attr('href').indexOf('?') > 0) {
                                location.href = url+"&product_list_limit="+val;
                            } else {
                                location.href = url+"?product_list_limit="+val;
                            }
                        }
                    })
                    just_loaded++;
                    $('#maincontent').on('click','.filter-options-item', function (e) {
                        if (ajaxcall && $(e.target).attr('class') != 'attr_filter_input') {
                           // $('.filter-options-item.active').not(this).removeClass('active').find('.filter-options-content').hide();
                           // $(this).toggleClass('active');
                            if (false == $(this).find('.filter-options-content').is(':visible')) {
                                $('[class^=filter-options-content]').each(function () {
                                    $(this).css('display','none');
                                })
                            }
                            $(this).find('.filter-options-content').toggle();
                        }
                    });
                    $('#maincontent').on('click','.block-subtitle.filter-current-subtitle', function (e) {
                        if (window.matchMedia('(max-width: 767px)').matches) {
                            $('.filter .filter-current .items').toggle();
                        }
                    });
                    $('#maincontent').on('click','.filter-title strong', function (e) {
                        if (ajaxcall) {
                         //   $('#layered-filter-block').addClass('active');
                            $('body .page-header').css('display','none');
                            $('body .page-wrapper').css({
                                "height": "0",
                                "margin-top": "-999999em",
                                "visibility": "hidden"
                            })
                        }
                    });
                   /* $('#maincontent').on('click','.filter.active .filter-title strong', function (e) {
                        if (ajaxcall) {
                           // $('#layered-filter-block').removeClass('active');
                            $('body .page-header').attr('style', '');
                            $('body .page-wrapper').attr('style', '');
                        }
                    });*/
                    $('#maincontent').on('click','.swatch-attribute-options > a', function (e) {
                        e.preventDefault();
                        $(this).find('.swatch-option').addClass('selected');
                        var final_url = $(this).attr('href');
                        ajaxcall = true;
                        callAjaxAfterMakeUrl();
                    });
                    $('#maincontent').on('click', '.wk-filter-action', function () {
                        var actionurl = $(this).attr('data-url');
                        ajaxcall = true;
                        callAjax(actionurl);
                    })
                    
                    $('#maincontent').on('change','.com_model', function () {
                        
                        var actionurl = $('option:selected', this).attr('data-url');
                        ajaxcall = true;
                        callAjax(actionurl);
                    })

                    $('#maincontent').on('click','.layered-navigation-label', function (e) {
                        var final_url = $(this).attr('data-url');
                        ajaxcall = true;
                        callAjaxAfterMakeUrl();
                    })


                    $('#maincontent').on('keyup','.attr_filter_input', function () {
                        var this_input = $(this);
                        this_input.parents("ol").find("li").each(function () {
                            var this_li = $(this);
                            if (this_li.index() != 0) {
                                var this_input_value = this_input.val().toLowerCase();
                                var this_li_label_text = this_li.find("label").text().toLowerCase();
                                if (this_li_label_text.indexOf(this_input_value) < 0) {
                                    this_li.hide();
                                } else {
                                    this_li.show();
                                }
                            }
                        });
                        $.ajax({
                            url     :   self.options.url,
                            type    :   "POST",
                            data    :   {"attr":this_input.attr("data-attrname"),"value":this_input.val()}
                        });
                    });
                    $('#maincontent').on('click','.attr_filter_clear', function () {
                        $(this).prev().val("").trigger("keyup");
                    });
                    function initialize_price_slider()
                    {
                        $(".range_slider").nstSlider({
                            "left_grip_selector"    : ".min_grip",
                            "right_grip_selector"   : ".max_grip",
                            "value_bar_selector"    : ".range_slider_bar",
                            "value_changed_callback": function (cause, leftValue, rightValue) {
                                $(".min_range").text(leftValue);
                                $(".max_range").text(rightValue);
                            },
                            "user_mouseup_callback" : function () {
                                sliderMouseupCallback();
                                ajaxcall = true;
                            }
                        });
                    }
                    function sliderMouseupCallback()
                    {
                        if ($(".min_range").text() == $(".range_slider").attr("data-range_min")) {
                            min_val = "";
                        } else {
                            min_val = $(".min_range").text();
                        }
                        if ($(".max_range").text() == $(".range_slider").attr("data-range_max")) {
                            max_val = "";
                        } else {
                            max_val = $(".max_range").text();
                        }
                        $(".for_price_filter").attr("id",min_val+"-"+max_val);
                        callAjaxAfterMakeUrl();
                    }
                })
                $(document).ready(function () {
                    $(".attr_filter_input").trigger('keyup');
                })
                function callAjax(url)
                {
                    ajaxcall = true;
                    $(".wk_layer_loader_bg").show();
                    $.ajax({
                        url     :   url,
                        type    :   "GET",
                        success :   function (data) {
                            var body;
                            var dom = document.createElement("html");
                            dom.innerHTML = data;
                            if ($(window).width() >=750) {
                                if (($(data).find('.columns').length>0)) {
                                    body = $(".columns", dom);
                                    $(".columns").html(body.html());
                                    $(".wk_layer_loader_bg").hide();
                                } else {
                                    $(".wk_layer_loader_bg").hide();
                                    body = $("#maincontent", dom);
                                    $("#maincontent").html(body.html());
                                }
                            } else {
                                if (($(data).find('.columns').length>0)) {
                                    body = $(".columns", dom);
                                    $(".columns").html(body.html());
                                    $(".wk_layer_loader_bg").hide();
                                } else {
                                    body = $("#maincontent", dom);
                                    $("#maincontent").html(body.html());
                                    $(".wk_layer_loader_bg").hide();
                                }
                            }
                            $('[class=filter-options-content]').each(function () {
                                $(this).css('display','none');
                            });
                            $(".attr_filter_input").trigger('keyup');
                            window.history.pushState("object", "current title", url);
                           // jQuery(".filter-options-item").addClass("active");
                        }
                    })
                }
                function callAjaxAfterMakeUrl()
                {
                    var currentUrl = window.location.href;
                    var final_url = "";
                    var parameters_testing = {};
                    $(".layered_attrs").each(function () {
                        var this_this = $(this);
                        var this_attr_name = this_this.attr("data-attrname");
                        var this_attr_value = this_this.attr("id");
                        if (this_attr_value != "-" && this_attr_value != "") {
                            if (this_this.is(":checked") || this_this.hasClass("for_price_filter")) {
                                if (typeof parameters_testing[this_attr_name] == "undefined") {
                                    parameters_testing[this_attr_name] = new Array(this_attr_value);
                                } else {
                                    parameters_testing[this_attr_name][parameters_testing[this_attr_name].length] = this_attr_value;
                                }
                            }
                        }
                    });
                    $(".swatch-layered").find('.swatch-option').each(function () {
                        var this_this = $(this);
                        var this_attr_name = this_this.parent().parent().parent().attr("attribute-code");
                        var this_attr_value = this_this.attr("option-id");
                        if (this_attr_value != "-" && this_attr_value != "" && this_attr_name != 'undefined') {
                            if (this_this.hasClass("selected")) {
                                if (typeof parameters_testing[this_attr_name] == "undefined") {
                                    parameters_testing[this_attr_name] = new Array(this_attr_value);
                                } else {
                                    parameters_testing[this_attr_name][parameters_testing[this_attr_name].length] = this_attr_value;
                                }
                            }
                        }
                    });
                   // $('#layered-filter-block').removeClass('active');
                    $('body .page-header').attr('style', '');
                    $('body .page-wrapper').attr('style', '');
                    clear_url = self.options.clearUrl;
                    final_url = clear_url;
                    $.each(parameters_testing,function (index,value) {
                        if (final_url == clear_url) {
                            final_url += "?";
                        }
                        if (final_url != clear_url+"?") {
                            final_url += "&"+index+"=";
                        } else {
                            final_url += index+"=";
                        }
                        for (var i = 0; i < value.length; i++) {
                            final_url += value[i];
                            if (value[i+1]) {
                                final_url += "_";
                            }
                        }
                    });
                    final_url = getFinalUrl(final_url);
                    if (currentUrl != final_url) {
                        callAjax(final_url);
                    }
                }
                function getFinalUrl(final_url)
                {
                    if ($(location).attr('href').search('product_list_mode=list') > 0) {
                        final_url = getAdditionalUrl(final_url, 'product_list_mode=list');
                    }
                    if ($(location).attr('href').search('product_list_dir=desc') > 0) {
                        final_url = getAdditionalUrl(final_url, 'product_list_dir=desc');
                    }
                    if ($(location).attr('href').search('product_list_order=name') > 0) {
                        final_url = getAdditionalUrl(final_url, 'product_list_order=name');
                    }
                    if ($(location).attr('href').search('product_list_order=price') > 0) {
                        final_url = getAdditionalUrl(final_url, 'product_list_order=price');
                    }
                    if ($(location).attr('href').search('product_list_limit') > 0) {
                        var urldata = $(location).attr('href').split('product_list_limit=');
                        final_url = getAdditionalUrl(final_url, 'product_list_limit='+urldata[1].split('&')[0]);
                    }
                    if ($(location).attr('href').indexOf('q=') > 0) {
                        var urldata = $(location).attr('href').split('q=');
                        final_url = getAdditionalUrl(final_url, 'q='+urldata[1].split('&')[0]);
                    }

                    if ($(location).attr('href').search('cat=') > 0) {
                        var urldata = $(location).attr('href').split('cat=');
                        if (final_url.includes("cat=") === false) {
                            final_url = getAdditionalUrl(final_url, 'cat='+urldata[1].split('&')[0]);
                        }
                        else {
                    final_url = getSecondcatUrl(final_url);
                        }
                    }
    
                    return final_url;
                }

                function getSecondcatUrl(final_url)
                {
                    if (final_url.includes("cat=") === true) {
                            final_url = final_url;
                    }
                    else
                    {
                    var fields = final_url.split('_');
                    var new_url=fields[0];
                    var new_field = new_url.split('?');
                    var urllll = new_field[0];
                    var name = fields[1];
                    var final_url = urllll+'?cat='+name;

                    }
                    
                    
                    return final_url;
                }


                function getAdditionalUrl(final_url, value)
                {
                    if (final_url.indexOf('?') < 0) {
                        final_url = final_url+'?'+value;
                    } else {
                        final_url = final_url+'&'+value;
                    }
                    return final_url;
                }
            }
        });
        return $.mage.layerednavigation;
    });