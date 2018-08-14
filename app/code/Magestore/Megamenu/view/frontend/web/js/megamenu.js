/**
 * Magestore
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

define([
    'jquery',
    'jquery/ui',
], function($) {
    $.fn.megamenu = function(options) {
        var consts = {
            isMobile : navigator.userAgent.match(/iPhone|iPad|iPod/i) || navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Opera Mini/i) || navigator.userAgent.match(/IEMobile/i),
            eff_hover : 1,
            eff_animation : 2,
            eff_toggle : 3,
            Slide : 0,
            Blind : 1
        }
        if (typeof options == 'undefined') {
            var options = {
                effect:'1',
                mobile_effect: '1',
                arr: ["100", "100", "100", "100", "100", "100","100","100","100","100"],
                change: '768',
                responsive: '1'
            }
        }
        var settings = $.extend({}, options);
        $(this).megaInit(settings);
        $(this).megaUpdate(settings);
        $(this).megaApplyEvent(consts,settings);

    };
    $.fn.megaInit = function(settings) {
        $(this).megaSetScreen(settings.arr);
        $(this).megaCategoriesLevel();
        $(this).megaCategoriesDynamic();
        $(this).megaMobileCategories();
    };
    $.fn.megaUpdate = function(settings) {
        var self = $(this);
        $(window).load(function(){
            self.megaSetScreen(settings.arr);
        });
        $(window).resize(function(){
            self.megaSetScreen(settings.arr);
        });
    };
    $.fn.megaApplyEvent = function(consts,settings) {
        var effect = parseInt(settings.effect);
        var mobile_effect = parseInt(settings.mobile_effect);
        if(consts.isMobile != null){
            $(this).megaToggle(settings.change,settings.responsive);
        }else {
            switch (effect) {
                case consts.eff_animation:
                    $(this).megaSlide();
                    break;
                case consts.eff_toggle:
                    $(this).megaToggle(settings.change,settings.responsive);
                    break;
                default:
                    $(this).megaFade();
                    break;
            }
        }
        switch (mobile_effect) {
            case consts.Slide:
                $(this).megaMobileSlide();
                break;
            default:
                $(this).megaMobileBlind();
                break;
        }
    };
    $.fn.megaFade = function() {
        $(this).find('.ms-level0').bind('mouseenter',function(){
            var $_this = $(this);
            $_this.addClass('active');
            $_this.find('.ms-submenu').stop().fadeIn(150);
        });
        $(this).find('.ms-level0').bind('mouseleave',function(){
            var $_this = $(this);
            $_this.removeClass('active');
            $_this.find('.ms-submenu').hide();
        });
    };
    $.fn.megaSlide = function() {
        $(this).find('.ms-level0').bind('mouseenter',function(){
            var $_this = $(this);
            $_this.addClass('active');
            $_this.children('.ms-submenu').stop().slideDown(150);
        });
        $(this).find('.ms-level0').bind('mouseleave',function(){
            var $_this = $(this);
            $_this.removeClass('active');
            $_this.find('.ms-submenu').hide();
        });
    };
    $.fn.megaToggle = function(change,responsive) {
        var id = $(this).attr('id');
        var change = parseInt(change);
        var responsive = parseInt(responsive);
        $(this).find('.ms-label').bind('click',function(){
            var $_this = $(this);
            if(!$_this.hasClass('anchor_text')){
                if ($_this.hasClass('flag')) {
                    $_this.removeClass('flag');
                    $_this.parent().removeClass('active');
                    $_this.parent().children('.ms-submenu').hide();
                } else {
                    $('#'+id+' .ms-level0').removeClass('active');
                    $('#'+id+' .ms-label').removeClass('flag');
                    $('#'+id+' .ms-submenu').hide();
                    $_this.addClass('flag');
                    $_this.parent().addClass('active');
                    $_this.parent().children('.ms-submenu').slideDown(150);
                }
                if($(window).width() > change || !responsive){
                    return false;
                }else{
                    return true;
                }
            }
        });
    };
    $.fn.megaMobileSlide = function() {
        var id = $(this).attr('id');
        var mclick = $(this).find('.mb-label');
        $(this).find('.mb-submenu').removeClass('blind');
        $(this).find('.mb-submenu').addClass('slide');
        $(this).find('.mb-label').bind('click', function () {
            var $_this = $(this);
            $('#'+id+' .ms-level0').removeClass('mbactive');
            $_this.parent().addClass('mbactive');
            $_this.parent().children('.mb-submenu').animate({
                left: 0
            }, 300);
        });
        $(this).find('.mb-return').bind('click', function () {
            var $_this = $(this);
            mclick.parent().children('.mb-submenu').animate({
                left: 100 + '%'
            }, 300, function () {
                mclick.parent().removeClass('mbactive');
            });
        });
    };
    $.fn.megaMobileBlind = function() {
        var id = $(this).attr('id');
        $(this).find('.mb-label').bind('click', function () {
            var $_this = $(this);
            if ($_this.hasClass('glyphicon-minus')) {
                $_this.removeClass('glyphicon-minus');
                $_this.parent().removeClass('mbactive');
                $_this.parent().children('.mb-submenu').slideUp(200);
            } else {
                $('#'+id+' .ms-level0').removeClass('mbactive');
                $('#'+id+' .mb-label').removeClass('glyphicon-minus');
                $('#'+id+' .mb-submenu').slideUp(200);
                $_this.addClass('glyphicon-minus');
                $_this.parent().addClass('mbactive');
                $_this.parent().children('.mb-submenu').slideDown(200);
            }
        });
    };
    $.fn.megaSetScreen = function(arr) {
        var width_default = $(this).outerWidth();
        $(this).megaWidth(width_default,arr);
        $(this).megaPosition(width_default);
    };
    $.fn.megaWidth = function(width_default,arr) {
        var submenu = $(this).find('.ms-submenu');
        for (var i = 0; i < $(this).find('.ms-submenu').length; i++) {
            var width_value = parseInt(arr[i]) * width_default / 100 + 'px';
            var sub = submenu[i];
            $(sub).css({
                width: width_value,
                top: $(sub).parent().outerHeight() + $(sub).parent().position().top -3+ 'px'
            });
        }
    };
    $.fn.megaPosition = function(width_default) {
        $(this).find('.sub_left').each(function () {
            $_this = $(this);
            if ($_this.hasClass('position_auto')) {
                var left_value = $_this.parent().position().left;
                if (($_this.outerWidth() + left_value) > width_default) {
                    left_value = width_default - $_this.outerWidth();
                }
                if (left_value < 0)
                    left_value = 0;
                $_this.css({
                    left: left_value + 'px'
                });
            } else {
                $_this.css({
                    left: 0
                });
            }
        });
        $(this).find('.sub_right').each(function () {
            $_this = $(this);
            if ($_this.hasClass('position_auto')) {
                var right_value = width_default - $_this.parent().position().left - $_this.parent().outerWidth();
                if (($_this.outerWidth() + right_value) > width_default) {
                    right_value = width_default - $_this.outerWidth();
                }
                if (right_value < 0)
                    right_value = 0;
                $_this.css({
                    right: right_value + 'px'
                });
            } else {
                $_this.css({
                    right: 0
                });
            }
        });
    };
    $.fn.megaCategoriesLevel = function() {
        if ($(this).find('.ms-category-level .parent').length > 0) {
            $(this).find('.ms-submenu .ms-category-level .parent').bind('mouseenter', function () {
                var $_this = $(this);
                $_this.addClass('active');
            });
            $(this).find('.ms-category-level .parent').bind('mouseleave', function () {
                var $_this = $(this);
                $_this.removeClass('active');
            });

        }
    };
    $.fn.megaCategoriesDynamic = function(){
        if ($(this).find('.ms-category-dynamic .col-level .parent').length > 0){
            $(this).find('.ms-category-dynamic .col-level .parent').bind('mouseenter', function () {
                var $_this = $(this);
                var info = $_this.find('i.information');
                var parent = $_this.parentsUntil('.ms-submenu');
                var active_id = info.attr('title');
                parent.find('.col-level .parent').removeClass('active');
                $_this.addClass('active');
                parent.find('.ms-category-dynamic .col-dynamic').removeClass('active');
                parent.find('#'+active_id).addClass('active');

            });
        }
    };
    $.fn.megaMobileCategories = function(){
        if ($(this).find('.mb-level-click').length > 0){
            $(this).find('.mb-level-click').bind('click', function () {
                var $_this = $(this);
                if ($_this.hasClass('glyphicon-minus')) {
                    $_this.removeClass('glyphicon-minus');
                    $_this.parent().parent().removeClass('active');
                } else {
                    $_this.addClass('glyphicon-minus');
                    $_this.parent().parent().addClass('active');
                }

            });
        }
    };
    $.fn.leftmenu = function(options){
        var consts = {
            isMobile : navigator.userAgent.match(/iPhone|iPad|iPod/i) || navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Opera Mini/i) || navigator.userAgent.match(/IEMobile/i),
            eff_hover : 1,
            eff_animation : 2,
            eff_toggle : 3,
            Slide : 0,
            Blind : 1
        }
        if (typeof options == 'undefined') {
            var options = {
                effect:'1',
                mobile_effect: '1',
                main_div: '.columns',
                arr: ["100", "100", "100", "100", "100", "100","100","100","100","100"],
                change: '768',
                responsive: '1'
            }
        }
        var settings = $.extend({}, options);
        $(this).leftInit();
        $(this).leftHandle(consts,settings);
        $(this).leftWidth(settings);
        $(this).leftUpdate(settings);
    };
    $.fn.leftInit = function() {
        $(this).megaCategoriesLevel();
        $(this).megaCategoriesDynamic();
        $(this).megaMobileCategories();
    }
    $.fn.leftHandle = function(consts,settings) {
        var effect = parseInt(settings.effect);
        var mobile_effect = parseInt(settings.mobile_effect);
        if(consts.isMobile != null){
            $(this).leftToggle(settings.change,settings.responsive);
        }else {
            switch (effect) {
                case consts.eff_animation:
                    $(this).leftSlide();
                    break;
                case consts.eff_toggle:
                    $(this).leftToggle(settings.change,settings.responsive);
                    break;
                default:
                    $(this).leftFade();
                    break;
            }
        }
        switch (mobile_effect) {
            case consts.Slide:
                $(this).leftMobileSlide();
                break;
            default:
                $(this).leftMobileBlind();
                break;
        }
    };
    $.fn.leftUpdate = function(settings) {
        var self = $(this);
        $(window).load(function(){
            self.leftWidth(settings);
        });
        $(window).resize(function(){
            self.leftWidth(settings);
        });
    };
    $.fn.leftFade = function() {
        $(this).find('.msl-level0').bind('mouseenter',function(){
            var $_this = $(this);
            $_this.addClass('active');
            $_this.find('.msl-submenu').stop().fadeIn(150);
        });
        $(this).find('.msl-level0').bind('mouseleave',function(){
            var $_this = $(this);
            $_this.removeClass('active');
            $_this.find('.msl-submenu').hide();
        });
    };
    $.fn.leftSlide = function() {
        $(this).find('.msl-level0').bind('mouseenter',function(){
            var $_this = $(this);
            $_this.addClass('active');
            $_this.children('.msl-submenu').stop().slideDown(150);
        });
        $(this).find('.msl-level0').bind('mouseleave',function(){
            var $_this = $(this);
            $_this.removeClass('active');
            $_this.find('.msl-submenu').hide();
        });
    };
    $.fn.leftToggle = function(change,responsive) {
        var id = $(this).attr('id');
        var change = parseInt(change);
        var responsive = parseInt(responsive);
        $(this).find('.msl-label').bind('click',function(){
            var $_this = $(this);
            if(!$_this.hasClass('anchor_text')){
                if ($_this.hasClass('flag')) {
                    $_this.removeClass('flag');
                    $_this.parent().removeClass('active');
                    $_this.parent().children('.msl-submenu').hide();
                } else {
                    $('#'+id+' .msl-level0').removeClass('active');
                    $('#'+id+' .msl-label').removeClass('flag');
                    $('#'+id+' .msl-submenu').hide();
                    $_this.addClass('flag');
                    $_this.parent().addClass('active');
                    $_this.parent().children('.msl-submenu').slideDown(150);
                }
                if($(window).width() > change || !responsive){
                    return false;
                }else{
                    return true;
                }
            }
        });
    };
    $.fn.leftMobileSlide = function() {
        var id = $(this).attr('id');
        var mclick = $(this).find('.mb-label');
        $(this).find('.lmb-submenu').removeClass('blind');
        $(this).find('.lmb-submenu').addClass('slide');
        $(this).find('.mb-label').bind('click', function () {
            var $_this = $(this);
            $('#'+id+' .msl-level0').removeClass('mbactive');
            $_this.parent().addClass('mbactive');
            $_this.parent().children('.lmb-submenu').animate({
                left: 0
            }, 300);
        });
        $(this).find('.mb-return').bind('click', function () {
            var $_this = $(this);
            mclick.parent().children('.lmb-submenu').animate({
                left: 100 + '%'
            }, 300, function () {
                mclick.parent().removeClass('mbactive');
            });
        });
    };
    $.fn.leftMobileBlind = function() {
        var id = $(this).attr('id');
        $(this).find('.mb-label').bind('click', function () {
            var $_this = $(this);
            if ($_this.hasClass('glyphicon-minus')) {
                $_this.removeClass('glyphicon-minus');
                $_this.parent().removeClass('mbactive');
                $_this.parent().children('.lmb-submenu').slideUp(200);
            } else {
                $('#'+id+' .msl-level0').removeClass('mbactive');
                $('#'+id+' .mb-label').removeClass('glyphicon-minus');
                $('#'+id+' .lmb-submenu').slideUp(200);
                $_this.addClass('glyphicon-minus');
                $_this.parent().addClass('mbactive');
                $_this.parent().children('.lmb-submenu').slideDown(200);
            }
        });
    };
    $.fn.leftWidth = function(settings) {
        var submenu = $(this).find('.msl-submenu');
        var width_default = $(this).outerWidth();
        var arr = settings.arr;
        var left_width = $(settings.main_div).outerWidth() - width_default ;
        for (var i = 0; i < $(this).find('.msl-submenu').length; i++) {
            var width_value = parseInt(arr[i]) * left_width / 100 + 'px';
            var sub = submenu[i];
            $(sub).css({
                width: width_value,
                left: width_default-1 + 'px'
            });
        }
    };
});