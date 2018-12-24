/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* @api */
define([
    'Magento_Checkout/js/view/payment/default',
    'jquery',
    'mage/validation'
], function (Component, $) {
    'use strict';

    var d = new Date();
    var dateFormatted = ("0"+(d.getMonth()+1)).slice(-2) + ("0" + d.getDate()).slice(-2) + d.getFullYear().toString().substr(-2);
    var poNumber = (window.checkoutConfig.pospaper.po_number != '') ? 'Metro Diner #' + window.checkoutConfig.pospaper.po_number + ' - ' + dateFormatted : '';

    return Component.extend({
        defaults: {
            template: 'Magento_OfflinePayments/payment/purchaseorder-form',
            purchaseOrderNumber: poNumber
        },

        /** @inheritdoc */
        initObservable: function () {
            this._super()
                .observe('purchaseOrderNumber');

            return this;
        },

        /**
         * @return {Object}
         */
        getData: function () {
            return {
                method: this.item.method,
                'po_number': this.purchaseOrderNumber(),
                'additional_data': null
            };
        },

        isChecked: function () {
            return 'purchaseorder';
        },

        /**
         * @return {jQuery}
         */
        validate: function () {
            var form = 'form[data-role=purchaseorder-form]';

            return $(form).validation() && $(form).validation('isValid');
        }
    });
});
