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
    'mage/template',
    'googleWebFontLoader',
    'Magento_Ui/js/lib/view/utils/async'
], function ($, mageTemplate, webFont) {
    "use strict";

    $.widget('mage.awFslabelPreview', {
        options: {
            loadedFonts: [],
            goalSelector: '[name="goal"]',
            currencies: [],
            labelTemplateSelector: '[data-role="label-template"]',
            stylesTemplateSelector: '[data-role="styles-template"]',
            labelRowSelector: '[data-index="content_fieldset"] tbody tr:visible',
            styleElementsSelectors: [
                '[name="font_name"]',
                '[name="font_size"]',
                '[name="font_weight"]',
                '[name="font_color"]',
                '[name="goal_font_color"]',
                '[name="background_color"]',
                '[name="text_align"]',
                '[name="custom_css"]',
            ]
        },

        /**
         * Initialize widget
         */
        _create: function () {
            this.labelTemplate = mageTemplate(this.options.labelTemplateSelector);
            this.stylesTemplate = mageTemplate(this.options.stylesTemplateSelector);
            this._bind();
            this.updatePreview();
        },

        /**
         * This method binds elements.
         * @private
         */
        _bind: function() {
            var self = this;

            // Bind elements in "Design" section of the form
            $.each(this.options.styleElementsSelectors, function (key, selector) {
                $(selector).on('change', $.proxy(self.updatePreview, self));
            });
            // Bind elements in "Content" section of the form
            $.async(
                '[data-index="content_fieldset"] [data-role="store-view"],' +
                '[data-index="content_fieldset"] [data-role="message"]',
                function(element) {
                    $(element).on('change', $.proxy(self.updatePreview, self));
                    self.updatePreview();
                }
            );
        },

        /**
         * Update preview
         *
         * @private
         */
        updatePreview: function () {
            var self = this,
                labels = self.getLabels();

            this.element.html('');

            if(!labels.length) { return; }

            $.each(labels, function(key, labelData) {
                self.addLabelBlock(labelData);
            });
            self.addStylesBlock(self.getStylesData());
        },

        /**
         * Get labels from corresponding inputs
         *
         * @private
         */
        getLabels: function () {
            var self = this, labels = [], storeId, message;

            $(this.options.labelRowSelector).each(function() {
                storeId = $(this).find('[data-role="store-view"]').val();
                message = $(this).find('[data-role="message"]').val();
                labels.push({store_id: storeId, message: self.processVars(storeId, message)});
            });
            return labels;
        },

        /**
         * Process variables in message
         *
         * @param {Int} storeId
         * @param {String} message
         * @private
         */
        processVars: function (storeId, message) {
            var goal = $(this.options.goalSelector).val(),
                replacement = '',
                currency;

            currency = this.options.currencies[storeId];
            replacement = '<span class="goal">' + currency + goal + '</span>';
            message = message.replace('{{ruleGoal}}', replacement);
            message = message.replace('{{ruleGoalLeft}}', replacement);

            return message;
        },

        /**
         * Add label preview
         *
         * @param {Object} data
         * @private
         */
        addLabelBlock: function (data) {
            this.element
                .append(this.labelTemplate({data: data}))
                .children('.aw_fslabel_label')
                .last()
                // Insert data.message manually as it may contain <span> element
                // which escapes when adding through x-magento-template
                .html(data.message);
        },

        /**
         * Get styles data
         *
         * @private
         */
        getStylesData: function () {
            var data = {}, propertyName, propertyValue, padding;
            $('[data-index="design_fieldset"] select')
                .add('[data-index="design_fieldset"] input')
                .add('[data-index="design_fieldset"] textarea')
                .each(function() {
                    propertyName = $(this).attr('name');
                    propertyValue = $(this).val();
                    data[propertyName] = propertyValue;
                });
            // Set padding depending on font size
            padding = Math.floor(data['font_size'] / 3);
            if (padding < 15) { padding = 15; }
            data['padding'] = padding + 'px';
            this.loadFont(data['font_name']);

            return data;
        },

        /**
         * Load font
         *
         * @param {Object} data
         * @private
         */
        loadFont: function (name) {
            if (this.options.loadedFonts.indexOf(name) == -1) {
                webFont.load({
                    google: {
                        families: [name]
                    }
                });
                this.options.loadedFonts.push(name);
            }
        },

        /**
         * Add styles block
         *
         * @param {Object} data
         * @private
         */
        addStylesBlock: function (data) {
            this.element
                .append(this.stylesTemplate({data: data}));
        }
    });

    return $.mage.awFslabelPreview;
});
