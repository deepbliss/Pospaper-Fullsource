define([
    'uiRegistry',
    'Magento_Checkout/js/model/quote',
    'mage/utils/wrapper'
], function (registry, quote, wrapper) {
    'use strict';

    return function (selectShippingAddressAction) {
        return wrapper.wrap(selectShippingAddressAction, function (originalAction, shippingAddress) {
            originalAction(shippingAddress);

            registry.get('checkout.steps.shipping-step.shippingAddress', function (addressComponent) {
                var subscription = quote.shippingMethod.subscribe(function(method) {
                    subscription.dispose();

                    if (method && addressComponent.reloadPayments) {
                        addressComponent.selectShippingMethod(method);
                    }
                });
            });
        });
    };
});
