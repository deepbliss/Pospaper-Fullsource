/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, serviceUrl, payload, messageContainer) {
            var amcheckoutForm = $('.additional-options input, .additional-options textarea'),
                amcheckoutData = amcheckoutForm.serializeArray(),
                data = {},
                agreements = [],
                re = /^agreement\[\d+?\]$/;

            amcheckoutData.forEach(function (item) {
                data[item.name] = item.value;
                if (re.test(item.name)) {
                    agreements.push(item.value);
                }
            });

            payload.amcheckout = data;

            if (agreements.length) {
                var extensionAttribute = payload.paymentMethod.extension_attributes;
                if (extensionAttribute.hasOwnProperty('agreement_ids')) {
                    extensionAttribute.agreement_ids = agreements;
                }
            }

            return originalAction(serviceUrl, payload, messageContainer);
        });
    };
});
