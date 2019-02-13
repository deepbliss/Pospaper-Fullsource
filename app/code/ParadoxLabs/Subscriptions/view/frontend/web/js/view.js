/*jshint jquery:true*/
define([
    "jquery",
    "jquery/ui"
], function($) {
    "use strict";

    $.widget('mage.subscriptionsView', {
        options: {
            qtySelector:   '.field.qty input'
        },

        _create: function() {
            var wrapper = this;

            wrapper.element.find(wrapper.options.qtySelector).attr('disabled', true);
        }
    });

    return $.mage.subscriptionsView;
});
