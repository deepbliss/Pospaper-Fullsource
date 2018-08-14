/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/item/details',
        'Magento_Checkout/js/model/quote',
        'Magento_Ui/js/modal/confirm',
        'Amasty_Checkout/js/action/remove-item',
        'Amasty_Checkout/js/action/update-item',
        'mage/translate',
        'ko',
        'Amasty_Checkout/js/options/configurable',
        'priceOptions',
        'mage/validation'
    ],
    function ($, Component, quote, confirm, removeItemAction, updateItemAction, $t, ko, configurable, priceOptions) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Amasty_Checkout/checkout/summary/item/details'
            },

            /**
             *
             * @param item
             * @return {*}
             */
            getItemConfig: function (item) {
                return this.getPropertyDataFromItem(item, 'amcheckout');
            },

            /**
             *
             * @param item
             * @param propertyName
             * @return {*}
             */
            getPropertyDataFromItem: function (item, propertyName) {
                if (item.hasOwnProperty(propertyName)) {
                    return item[propertyName];
                }

                var quoteItem = this.getItemFromQuote(item);

                if (quoteItem.hasOwnProperty(propertyName)) {
                    return quoteItem[propertyName];
                }
                else
                    return false;
            },

            /**
             *
             * @param item
             * @return {*}
             */
            getStockStatusHtml: function (item) {
                return this.getPropertyDataFromItem(item, 'amstockstatus');
            },
            /**
             *
             * @param item
             * @return {*}
             */
            getItemFromQuote: function (item) {
                var items = quote.getItems();
                var quoteItems = items.filter(function (quoteItem) {
                    return quoteItem.item_id == item.item_id;
                });

                if (quoteItems.length == 0)
                    return false;

                return quoteItems[0];
            },

            getConfigurableOptions: function (item) {
                var itemConfig = this.getItemConfig(item);

                if (itemConfig.hasOwnProperty('configurableAttributes')) {
                    return itemConfig.configurableAttributes.template;
                }
                else
                    return '';
            },

            getCustomOptions: function (item) {
                var itemConfig = this.getItemConfig(item);

                if (itemConfig.hasOwnProperty('customOptions')) {
                    return itemConfig.customOptions.template;
                }
                else
                    return '';
            },

            initOptions: function (item) {
                var itemConfig = this.getItemConfig(item);

                var containerSelector = '[data-role="product-attributes"][data-item-id=' + item.item_id + ']';
                var container = $(containerSelector);

                if (itemConfig.hasOwnProperty('configurableAttributes')) {
                    container.amcheckoutConfigurable({
                        spConfig: JSON.parse(itemConfig.configurableAttributes.spConfig),
                        superSelector: containerSelector + ' .super-attribute-select'
                    });
                }

                if (itemConfig.hasOwnProperty('customOptions')) {
                    container.priceOptions({
                        optionConfig: JSON.parse(itemConfig.customOptions.optionConfig)
                    });
                }

                item.form = container;
                item.isUpdated = ko.observable(false);
                item.validation = container.validation();

                container.find('input, select, textarea').change(function () {
                    item.isUpdated(true);
                });
            },

            updateItem: function (item) {
                if (item.validation.valid()) {
                    updateItemAction(item.item_id, item.form.serialize());
                }
            },

            deleteItem: function (item) {
                confirm({
                    content: $t("Are you sure you would like to remove this item from the shopping cart?"),
                    actions: {
                        confirm: function () {
                            removeItemAction(item.item_id);
                        },
                        always: function (event) {
                            event.stopImmediatePropagation();
                        }
                    }
                });
            },

            canShowDeleteButton: function () {
                return quote.getItems().length >= 1;
            }
        });
    }
);
