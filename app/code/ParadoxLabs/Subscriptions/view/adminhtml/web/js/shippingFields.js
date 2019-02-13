/*jshint jquery:true*/
define([
    "jquery",
    "jquery/ui"
], function($) {
    "use strict";

    $.widget('mage.subscriptionsShippingFields', {
        options: {
            shippingAddressSelector: '#shipping_address_id',
            fieldSelector: '.admin__field.toggle',
            inputSelector: 'select, input'
        },

        _create: function() {
            var wrapper = this;

            $(wrapper.options.shippingAddressSelector).on('change', function() {
                var fields = wrapper.element.find(wrapper.options.fieldSelector);

                if (this.value == '') {
                    fields.show();
                    fields.find(wrapper.options.inputSelector).each(function(i, el) {
                        el.disabled = false;
                    });
                }
                else {
                    fields.hide();
                    fields.find(wrapper.options.inputSelector).each(function(i, el) {
                        el.disabled = true;
                    });
                }
            });

            if ($(wrapper.options.shippingAddressSelector).val() > 0) {
                var fields = wrapper.element.find(wrapper.options.fieldSelector);

                fields.hide();
                fields.find(wrapper.options.inputSelector).each(function(i, el) {
                    el.disabled = true;
                });
            }
        }
    });

    return $.mage.subscriptionsShippingFields;
});
