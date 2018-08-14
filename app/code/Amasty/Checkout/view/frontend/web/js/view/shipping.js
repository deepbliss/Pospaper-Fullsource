define(
    [
        'jquery',
        'Magento_Ui/js/lib/view/utils/async',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/action/set-shipping-information',
        'Magento_Checkout/js/view/shipping',
        'Magento_Checkout/js/model/quote',
        'uiRegistry'
    ],
    function (
        $,
        async,
        storage,
        checkoutData,
        shippingService,
        selectShippingMethod,
        setShippingInformationAction,
        Shipping,
        quote,
        registry
    ) {
        'use strict';

        var instance = null;

        // Fix js error in Magento 2.2
        function fixAddress(address) {
            if (!address) {
                return;
            }

            if (Array.isArray(address.street) && address.street.length == 0) {
                address.street = ['', ''];
            }
        }

        function removeAmazonPayButton() {
            var amazonPaymentButton = $('#PayWithAmazon_amazon-pay-button img');
            if (amazonPaymentButton.length > 1) {
                amazonPaymentButton.not(':first').remove();
            }
        }

        return Shipping.extend({
            setShippingInformation: function () {
                fixAddress(quote.shippingAddress());
                fixAddress(quote.billingAddress());

                setShippingInformationAction().done(
                    function () {
                        //stepNavigator.next();
                    }
                );
            },

            initialize: function () {
                this._super();
                this.setDefaultShippingMethod();
                instance = this;

                registry.get('checkout.steps.shipping-step.shippingAddress.before-form.amazon-widget-address.before-widget-address.amazon-checkout-revert',
                    function (component) {
                        component.isAmazonAccountLoggedIn.subscribe(function (loggedIn) {
                            if (!loggedIn) {
                                registry.get('checkout.steps.shipping-step.shippingAddress', function (component) {
                                    if (component.isSelected()) {
                                        component.selectShippingMethod(quote.shippingMethod());
                                    }
                                });
                            }
                        });
                    }
                );

                registry.get('checkout.steps.billing-step.payment.payments-list.amazon_payment', function (component) {
                    if (component.isAmazonAccountLoggedIn()) {
                        $('button.action-show-popup').hide();
                    }
                });

                registry.get('checkout.steps.shipping-step.shippingAddress.customer-email.amazon-button-region.amazon-button',
                    function (component) {
                        async.async({
                            selector: "#PayWithAmazon_amazon-pay-button img"
                        }, function () {
                            removeAmazonPayButton();
                        });

                        component.isAmazonAccountLoggedIn.subscribe(function (loggedIn) {
                            if (!loggedIn) {
                                removeAmazonPayButton();
                            }
                        });
                    }
                );
            },

            selectShippingMethod: function (shippingMethod) {
                this._super();

                instance.setShippingInformation();

                return true;
            },

            /**
             * The method sets default shipping method
             *
             * @return
             */
            setDefaultShippingMethod: function () {
                if (quote.shippingMethod()) {
                    return;
                }

                var rates = shippingService.getShippingRates(),
                    shippingMethod = checkoutData.getSelectedShippingRate(),
                    defaultShippingMethod = storage.get('amasty-checkout-default-shipping-mehtod');

                shippingMethod = (shippingMethod) ? shippingMethod : defaultShippingMethod();
                storage.set('amasty-checkout-default-shipping-mehtod', '');

                if (!shippingMethod) {
                   return;
                }

                var method = _.find(rates, function (rate) {
                    return rate.carrier_code + '_' + rate.method_code == shippingMethod;
                });

                if (method) {
                    selectShippingMethod(method).done(function () {
                        checkoutData.setSelectedShippingRate(method);
                    });
                }
            },

            getNameShippingAddress: function () {
                return window.checkoutConfig.quoteData.block_shipping_address;
            },

            getNameShippingMethod: function () {
                return window.checkoutConfig.quoteData.block_shipping_method;
            },

            isPostNlEnable: function () {
                return window.checkoutConfig.quoteData.posnt_nl_enable;
            }
        });
    }
);
