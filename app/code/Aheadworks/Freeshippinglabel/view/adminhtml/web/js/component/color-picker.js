/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'Magento_Ui/js/form/element/abstract',
    'Magento_Ui/js/lib/view/utils/async',
    'jquery/colorpicker/js/colorpicker'
], function (Abstract, $) {
    'use strict';

    return Abstract.extend({
        defaults: {
            elementTmpl: 'Aheadworks_Freeshippinglabel/label/form/color-picker',
            inputSelector: ''
        },

        /**
         * Initialization
         */
        initialize: function () {
            this._super();

            this.inputSelector = 'input[name=' + this.inputName + ']';
            $.async({
                component: this,
                selector: this.inputSelector
            }, this.initColorPicker.bind(this));

            return this;
        },

        /**
         * Initialize color picker
         */
        initColorPicker: function (inputSelector) {
            var inputElement = $(inputSelector),
                colorPicker = inputElement.next('div.color_picker_window');

            colorPicker.ColorPicker({
                /**
                 * ColorPicker onSubmit action
                 *
                 * @param {String} hsb
                 * @param {String} hex
                 * @param {String} rgb
                 * @param {String} el
                 */
                onSubmit: function (hsb, hex, rgb, el) {
                    var container = $(el);

                    container.ColorPickerHide();
                    inputElement.val('#' + hex);
                    inputElement.trigger('change');
                    container.css('background', '#' + hex);
                }
            });
            colorPicker.ColorPickerSetColor(inputElement.val());
            colorPicker.css('background', inputElement.val());

            return this;
        },

        /**
         * Callback that fires when 'value' property is updated.
         */
        onUpdate: function (value) {
            var colorPicker = $(this.inputSelector).next('div.color_picker_window');

            this._super();
            colorPicker.ColorPickerSetColor(value);
            colorPicker.css('background', value);
        }
    });
});


