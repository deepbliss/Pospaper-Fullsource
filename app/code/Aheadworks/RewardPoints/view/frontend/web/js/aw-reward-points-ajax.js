/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

/**
 * Initialization widget to upload html content by Ajax
 *
 * @method ajax(placeholders)
 * @method replacePlaceholder(placeholder, html)
 */
define([
    'jquery'
], function($) {
    "use strict";

    $.widget('mage.awRewardPointsAjax', {
        options: {
            url: '/',
            dataPattern: 'aw-reward-points-block-name',
            originalRequest: []
        },

        /**
         * Initialize widget
         */
        _create: function () {
            var placeholders = $('[data-' + this.options.dataPattern + ']');

            if (placeholders && placeholders.length) {
                this.ajax(placeholders);
            }
        },

        /**
         * Send AJAX request
         * @param {Object} placeholders
         */
        ajax: function (placeholders) {
            var self = this,
                data = {
                    blocks: [],
                    originalRequest: this.options.originalRequest
                };

            placeholders.each(function() {
                data.blocks.push($(this).data(self.options.dataPattern));
            });
            data.blocks = JSON.stringify(data.blocks.sort());
            data.originalRequest = JSON.stringify(data.originalRequest);
            $.ajax({
                url: this.options.url,
                data: data,
                type: 'GET',
                cache: false,
                dataType: 'json',
                context: this,

                /**
                 * Response handler
                 * @param {Object} response
                 */
                success: function (response) {
                    placeholders.each(function() {
                        var placeholder = $(this),
                            placeholderName = placeholder.data(self.options.dataPattern);

                        if (response.hasOwnProperty(placeholderName)) {
                            self.replacePlaceholder(placeholder, response[placeholderName]);
                        }
                    });
                }
            });
        },

        /**
         * Replace placeholders
         * @param {Object} placeholder
         * @param {Object} html
         */
        replacePlaceholder: function (placeholder, html) {
            if (!placeholder) {
                return;
            }
            var parent = $(placeholder).parent();

            // Replace placeholder on html content
            placeholder.replaceWith(html);
            // Trigger event to use mage-data-init attribute
            $(parent).trigger('contentUpdated');
        }
    });

    return $.mage.awRewardPointsAjax;
});
