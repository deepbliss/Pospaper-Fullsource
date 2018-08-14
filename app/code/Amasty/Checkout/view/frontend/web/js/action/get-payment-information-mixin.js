define([
    'uiRegistry',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/shipping-rate-registry',
    'mage/utils/wrapper'
], function (registry, quote, rateRegistry, wrapper) {
    'use strict';

    return function (getPaymentInformationAction) {
        return wrapper.wrap(getPaymentInformationAction, function (originalAction, deferred, messageContainer) {
            var reload = false;
            var address = quote.shippingAddress();
            
            if (registry && registry.__proto__.hasOwnProperty('get')) {
                registry.get('checkoutProvider', function (component) {
                    if (component.amdiscount) {
                        reload = component.amdiscount.isNeedToReloadShipping;
                    }
                });
            }

            if (reload && address) {
                var cacheKey = address.getCacheKey().concat(JSON.stringify(address.extensionAttributes)),
                    cacheData = rateRegistry.get(cacheKey);

                rateRegistry.set(address.getKey(), null);
                rateRegistry.set(address.getCacheKey(), null);
                rateRegistry.set(cacheKey, null);

                quote.shippingAddress(address);

                rateRegistry.set(cacheKey, cacheData);
            }

            return originalAction(deferred, messageContainer);
        });
    };
});