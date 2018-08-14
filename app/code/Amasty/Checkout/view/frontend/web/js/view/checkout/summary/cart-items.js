/*browser:true*/
/*global define*/
define(
    [
        'ko',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/view/summary/cart-items'
    ],
    function (ko, totals, Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Amasty_Checkout/checkout/summary/cart-items'
            },

            itemsQty: ko.observable(),

            initialize: function () {
                this._super();
                var self = this;

                this.itemsQty(this.getItemsQty());

                totals.totals.subscribe(function () {
                    self.itemsQty(self.getItemsQty());
                });

                return this;
            },
            getItemsQty: function () {
                return parseFloat(totals.totals().items_qty);
            }
        });
    }
);
