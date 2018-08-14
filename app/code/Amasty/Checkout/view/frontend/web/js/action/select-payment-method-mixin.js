define([
    'uiRegistry',
    'Magento_Checkout/js/model/quote',
    'mage/utils/wrapper'
], function (registry, quote, wrapper) {
    'use strict';

    return function (selectPaymentMethod) {
        return wrapper.wrap(selectPaymentMethod, function (originalAction, paymentMethod) {
            registry.get('checkout', function (component) {
                if (typeof component.enableOrderActionAllowed == "function") {
                    component.enableOrderActionAllowed();
                }
            });
            originalAction(paymentMethod);
        });
    };
});
