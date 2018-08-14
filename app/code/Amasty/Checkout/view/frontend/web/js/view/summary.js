define([
    'Magento_Checkout/js/view/summary'
], function (Summary) {
    'use strict';

    return Summary.extend({
        getNameSummary:function () {
            return window.checkoutConfig.quoteData.block_order_summary;
        }
    });
});
