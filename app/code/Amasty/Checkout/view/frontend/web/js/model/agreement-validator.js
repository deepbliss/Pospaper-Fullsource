
define([
    'jquery',
    'mage/validation'
], function ($) {
    'use strict';

    var checkoutConfig = window.checkoutConfig,
        agreementsConfig = checkoutConfig ? checkoutConfig.checkoutAgreements : {},
        agreementsInputPath = '.additional-options .checkout-agreements .checkout-agreement.required input';

    return {
        /**
         * Validate checkout agreements
         *
         * @returns {Boolean}
         */
        validate: function () {
            var isValid = true;

            if (!agreementsConfig || (!agreementsConfig.isEnabled || $(agreementsInputPath).length === 0)) {
                return true;
            }

            $(agreementsInputPath).each(function (index, element) {
                if (!$.validator.validateSingleElement(element, {
                        errorElement: 'div'
                    })) {
                    isValid = false;
                }
            });

            return isValid;
        }
    };
});
