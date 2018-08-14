/*jshint browser:true jquery:true*/
/*global alert*/
var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/model/place-order': {
                'Amasty_Checkout/js/model/place-order-mixin': true
            },
            'Magento_Checkout/js/action/select-shipping-address' : {
                'Amasty_Checkout/js/action/select-shipping-address-mixin': true
            },
            'Magento_Checkout/js/action/select-payment-method' : {
                'Amasty_Checkout/js/action/select-payment-method-mixin': true
            },
            'Magento_Checkout/js/action/get-payment-information': {
                'Amasty_Checkout/js/action/get-payment-information-mixin': true
            },
            'Amazon_Payment/js/action/place-order': {
                'Amasty_Checkout/js/model/place-order-amazon-mixin': true
            },
            'Magento_Checkout/js/view/payment/list': {
                'Amasty_Checkout/js/view/payment/list': true
            },
            'Magento_Checkout/js/view/summary/abstract-total': {
                'Amasty_Checkout/js/view/summary/abstract-total': true
            },
            'Magento_Checkout/js/model/step-navigator': {
                'Amasty_Checkout/js/model/step-navigator': true
            },
            'Magento_Paypal/js/action/set-payment-method': {
                'Amasty_Checkout/js/action/set-payment-method-mixin': true
            },
            'Magento_CheckoutAgreements/js/model/agreements-assigner': {
                'Amasty_Checkout/js/model/agreements-assigner-mixin': true
            }
        }
    }
};
