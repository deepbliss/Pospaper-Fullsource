/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';

        var config = window.checkoutConfig.payment,
            braintreeType = 'braintree',
            payPalType = 'braintree_paypal';

        if (config[braintreeType].isActive) {
            rendererList.push(
                {
                    type: braintreeType,
                    component: 'Magento_Braintree/js/view/payment/method-renderer/hosted-fields'
                }
            );
        }

        if (config[payPalType].isActive) {
            rendererList.push(
                {
                    type: payPalType,
                    component: 'Amasty_Checkout/js/view/checkout/payment/method-renderer/braintree/paypal'
                }
            );
        }

        /** Add view logic here if needed */
        return Component.extend({});
    }
);
/**
 * Created by demidov on 2.3.18.
 */
