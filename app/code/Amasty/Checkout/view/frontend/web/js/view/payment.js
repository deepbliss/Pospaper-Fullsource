define([
    'ko',
    'Magento_Checkout/js/model/payment-service',
    'Magento_Checkout/js/view/payment',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/select-payment-method',
    'Magento_Checkout/js/checkout-data'
], function(ko, paymentService, Payment, quote, selectPaymentMethodAction, checkoutData) {
        'use strict';

        return Payment.extend({
            isVisible: ko.observable(true),

            updateRegion: function (items, name) {

                if (name == 'afterMethods') {
                    items.forEach(function(item){
                        item.removeChild('discount');
                        item.removeChild('rewards');
                    });
                }
                this.getRegion(name)(items);

                return this;
            },

            initialize: function() {
                this._super();
                this.navigate();
                this.setDefaultMethod();

                return this;
            },

            setDefaultMethod: function () {
                if (quote.paymentMethod())
                    return;

                var methods = paymentService.getAvailablePaymentMethods();

                if (methods.length == 0)
                    return;

                var defaultMethod = checkoutData.getSelectedPaymentMethod();
                if (!defaultMethod)
                    return;

                var method = methods.find(function (method) {
                    return method.method == defaultMethod;
                });

                if (method) {
                    selectPaymentMethodAction(method);
                    checkoutData.setSelectedPaymentMethod(method.method);
                }
            },

            getName: function () {
                return window.checkoutConfig.quoteData.block_payment_method;
            }
        });
    }
);
