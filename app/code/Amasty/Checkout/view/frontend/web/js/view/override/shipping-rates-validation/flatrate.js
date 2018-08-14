define([
    'uiComponent',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-rates-validation-rules',
    'Magento_OfflineShipping/js/model/shipping-rates-validator/flatrate',
    'Amasty_Checkout/js/model/shipping-rates-validation-rules/flatrate'
], function(
    Component,
    defaultShippingRatesValidator,
    defaultShippingRatesValidationRules,
    flatrateShippingRatesValidator,
    amastyFlatrateShippingRatesValidationRules
) {
    'use strict';

    defaultShippingRatesValidator.registerValidator('flatrate', flatrateShippingRatesValidator);
    defaultShippingRatesValidationRules.registerRules('flatrate', amastyFlatrateShippingRatesValidationRules);

    return Component;
});
