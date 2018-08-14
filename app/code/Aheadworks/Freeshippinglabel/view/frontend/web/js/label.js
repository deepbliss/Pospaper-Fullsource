/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

/**
 * Widget manages label preview
 *
 * @method show(placeholders)
 */
define([
    'jquery',
    'googleWebFontLoader'
], function ($, webfont) {
    "use strict";

    $.widget('mage.awFslabelLabel', {
        options: {
            url: '/',
            stickyClass: null,
            delay: 0,
            font: '',
            minicart: '[data-block=\"minicart\"]',
            minicartCounter: '.minicart-wrapper .counter-number',
            currentCartCount: null
        },

        /**
         * Initialize widget
         */
        _create: function () {
            webfont.load({google: {families: [this.options.font]}});
            this.updateLabel();
            this.show();
            this._bind();
        },

        /**
         * Event binding
         */
        _bind: function () {
            var self = this;
            $(this.options.minicart).on('contentUpdated', $.proxy(self.updateLabel, self));
            if (this.options.stickyClass) {
                $(window).on('scroll', function () {
                    this.toggleFixedPosition($(window));
                }.bind(this));
                self.toggleFixedPosition($(window));
            }
        },

        /**
         * Show block
         *
         * @private
         */
        show: function () {
            var direction = 'up';

            if (this.options.delay > 0) {
                if (this.options.stickyClass == 'bottom_fixed') {
                    direction = 'down';
                }
                this.element
                    .delay(this.options.delay * 1000)
                    .toggle('slide', {direction: direction}, 1000);
            } else {
                this.element.show();
            }
        },

        /**
         * Update label
         *
         * @private
         */
        updateLabel: function () {
            var newCartCount = $('.minicart-wrapper .counter-number').html(),
                self = this;

            if (this.options.currentCartCount != newCartCount) {
                $.ajax({
                    url: this.options.url,
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    context: this,

                    /**
                     * Response handler
                     * @param {Object} response
                     */
                    success: function (response) {
                        if (response.labelContent && response.labelContent.length) {
                            $(self.element).html(response.labelContent);
                        }
                    }
                });
                this.options.currentCartCount = newCartCount;
            }
        },

        /**
         * Toggle fixed position class
         *
         * @private
         */
        toggleFixedPosition: function(window) {
            var condition,
                fixedClass = this.options.stickyClass,
                doc = $(document);

            if (fixedClass == 'top_fixed') {
                condition = (0 < window.scrollTop());
            } else {
                condition = (window.scrollTop() + window.height() < doc.height() - 60);
            }
            if (condition) {
                this.element.addClass(fixedClass);
            } else {
                this.element.removeClass(fixedClass);
            }
        }
    });

    return $.mage.awFslabelLabel;
});