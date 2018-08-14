define([
    'underscore',
    'Magento_Braintree/js/view/payment/method-renderer/paypal',
    'Magento_Checkout/js/model/quote'
], function (
    _,
    Component,
    quote
) {
    'use strict';

    return Component.extend({

        /**
         * Prepare data to place order
         * @param {Object} data
         */
        beforePlaceOrder: function (data) {
            this.setPaymentMethodNonce(data.nonce);

            if (quote.billingAddress() === null && typeof data.details.billingAddress !== 'undefined') {
                this.setBillingAddress(data.details, data.details.billingAddress);
            }

            this.placeOrder();
        },

        /**
         * Get shipping address
         * @returns {Object}
         */
        getShippingAddress: function () {
            var address = quote.shippingAddress();

            if (!this.canUseAddress(address)) {
                return {};
            }

            return {
                recipientName: address.firstname + ' ' + address.lastname,
                streetAddress: address.street[0],
                locality: address.city,
                countryCodeAlpha2: address.countryId,
                postalCode: address.postcode,
                region: address.regionCode,
                phone: address.telephone,
                editable: this.isAllowOverrideShippingAddress()
            };
        },

        /**
         * Can address uses for ini braintree paypal
         *
         * @param address
         *
         * @returns boolean
         */
        canUseAddress: function (address) {
            var hasPostcode = !(_.isNull(address.postcode) || _.isUndefined(address.postcode));
            var hasStreet = !(_.isNull(address.street) || _.isUndefined(address.street));

            if (hasPostcode && hasStreet) {
                return true;
            }

            return false;
        }
    });
});
