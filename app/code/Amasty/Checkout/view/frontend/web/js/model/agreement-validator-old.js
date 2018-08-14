define([
    'jquery',
    'mage/validation'
], function ($) {
    'use strict';

    var checkoutConfig = window.checkoutConfig,
        agreementsConfig = checkoutConfig ? checkoutConfig.checkoutAgreements : {},
        agreementsError = '.additional-options .checkout-agreements .checkout-agreement div.mage-error',
        agreementsInputPath = '.additional-options .checkout-agreements .checkout-agreement input';

    return {
        /**
         * Validate checkout agreements
         *
         * @returns {Boolean}
         */
        validate: function () {
            if (!agreementsConfig || (!agreementsConfig.isEnabled)) {
                return true;
            }

            if ($(agreementsInputPath).length == 0) {
                return true;
            }

            var isValid = true,
                element = $(agreementsInputPath),
                validator = $('#checkout').validate({
                    errorClass: 'mage-error',
                    errorElement: 'div',
                    meta: 'validate',
                    errorPlacement: function (error, element) {
                        var errorPlacement = element;
                        if (element.is(':checkbox') || element.is(':radio')) {
                            errorPlacement = element.siblings('label').last();
                        }
                        errorPlacement.after(error);
                    }
                });


            if (element.is(':checked') == false) {
                isValid = false;
                if (!$(agreementsError).length) {
                    validator.showLabel(element, $.mage.__('This is a required field.'));
                }
            }

            return isValid;
        }
    };
});