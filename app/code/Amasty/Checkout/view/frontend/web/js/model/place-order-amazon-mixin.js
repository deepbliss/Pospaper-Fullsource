
define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/url-builder',
    'mage/storage',
    'mage/url',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/full-screen-loader',
    'Amazon_Payment/js/model/storage'
], function ($, wrapper, quote, urlBuilder, storage, url, errorProcessor, customer, fullScreenLoader, amazonStorage) {
    'use strict';

    function amazonOriginalAction(paymentData, redirectOnSuccess, messageContainer, amastyCheckoutData)
    {

        var serviceUrl,
            payload,
            agreements = [],
            re = /^agreement\[\d+?\]$/;

        redirectOnSuccess = redirectOnSuccess !== false;

        /** Checkout for guest and registered customer. */
        if (!customer.isLoggedIn()) {
            serviceUrl = urlBuilder.createUrl('/guest-carts/:quoteId/payment-information', {
                quoteId: quote.getQuoteId()
            });
            payload = {
                cartId: quote.getQuoteId(),
                email: quote.guestEmail,
                paymentMethod: paymentData,
                billingAddress: quote.billingAddress()
            };
        } else {
            serviceUrl = urlBuilder.createUrl('/carts/mine/payment-information', {});
            payload = {
                cartId: quote.getQuoteId(),
                paymentMethod: paymentData,
                billingAddress: quote.billingAddress()
            };
        }

        payload.amcheckout = amastyCheckoutData;

        amastyCheckoutData.forEach(function (item) {
            data[item.name] = item.value;
            if (re.test(item.name)) {
                agreements.push(item.value);
            }
        });

        if (agreements.length) {
            var extensionAttribute = payload.paymentMethod.extension_attributes;
            if (extensionAttribute.hasOwnProperty('agreement_ids')) {
                extensionAttribute.agreement_ids = agreements;
            }
        }

        fullScreenLoader.startLoader();

        return storage.post(
            serviceUrl,
            JSON.stringify(payload)
        ).done(
            function () {
                if (redirectOnSuccess) {
                    window.location.replace(url.build('checkout/onepage/success/'));
                }
            }
        ).fail(
            function (response) {
                errorProcessor.process(response);
                amazonStorage.amazonDeclineCode(response.responseJSON.code);
                fullScreenLoader.stopLoader();
            }
        );
    }

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, redirectOnSuccess, messageContainer) {
            var amcheckoutForm = $('.additional-options input, .additional-options textarea');
            var amcheckoutData = amcheckoutForm.serializeArray();
            var data = {};

            amcheckoutData.forEach(function (item) {
                data[item.name] = item.value;
            });

            return amazonOriginalAction(paymentData, redirectOnSuccess, messageContainer, data);
        });
    };
});
