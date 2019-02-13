/*jshint jquery:true*/
define([
    "jquery",
    "jquery/ui"
], function($) {
    "use strict";

    $.widget('mage.subscriptionsEdit', {
        options: {
            shippingAddressSelector: '#shipping_address_id',
            fieldSelector: '.shipping-address .field:not(.do-not-toggle)',
            inputSelector: 'select, input'
        },

        _create: function() {
            var wrapper = this;

            if ($(wrapper.options.shippingAddressSelector).length > 0) {
                // Watch
                $(wrapper.options.shippingAddressSelector).on('change', function () {
                    var fields = wrapper.element.find(wrapper.options.fieldSelector);

                    if (this.value == '') {
                        fields.show();
                        fields.find(wrapper.options.inputSelector).each(function (i, el) {
                            el.disabled = false;
                        });
                    }
                    else {
                        fields.hide();
                        fields.find(wrapper.options.inputSelector).each(function (i, el) {
                            el.disabled = true;
                        });
                    }
                });

                // Initialize
                wrapper.setShippingFieldVisibility();

                // Control -- country field likes to escape
                setInterval(function() {
                    wrapper.setShippingFieldVisibility();
                }, 1000);
            }
        },

        setShippingFieldVisibility: function() {
            var wrapper = this;

            if ($(wrapper.options.shippingAddressSelector).val() > 0) {
                var fields = wrapper.element.find(wrapper.options.fieldSelector);

                fields.hide();
                fields.find(wrapper.options.inputSelector).each(function (i, el) {
                    el.disabled = true;
                });
            }
        }
    });

    return $.mage.subscriptionsEdit;
});
