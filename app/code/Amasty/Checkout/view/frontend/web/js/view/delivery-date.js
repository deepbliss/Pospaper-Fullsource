define(
    [
        'jquery',
        'Magento_Ui/js/form/form',
        'Amasty_Checkout/js/action/update-delivery',
        'Amasty_Checkout/js/model/delivery',
        'mage/translate',
        'Amasty_Checkout/js/view/checkout/datepicker'
    ],
    function (
        $,
        Component,
        updateAction,
        deliveryService,
        $t
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Amasty_Checkout/checkout/delivery_date',
                listens: {
                    'update': 'update'
                }
            },
            isLoading: deliveryService.isLoading,
            _requiredFieldSelector: '.amcheckout-delivery-date .field._required :input:not(:button)',

            update: function () {
                $(this._requiredFieldSelector).each(function(index, element){
                    this._removeErrorToInput(element);
                    this._removeErrorAfterInput(element);
                }.bind(this));

                this.source.set('params.invalid', false);
                this.source.trigger('amcheckoutDelivery.data.validate');

                if (!this.source.get('params.invalid')) {
                    var data = this.source.get('amcheckoutDelivery');

                    updateAction(data);
                }
            },

            validate: function () {
                var isAllValid = true;
                $(this._requiredFieldSelector).each(function(index, element) {
                    if( $(element).val().length === 0 ) {
                        this._addErrorToInput(element);
                        this._addErrorAfterInput(element);
                        isAllValid = false;
                    }
                }.bind(this));

                return isAllValid;
            },

            _addErrorToInput: function (input) {
                $(input).addClass('mage-error');
            },

            _addErrorAfterInput: function(input) {
                var after = $('#' + $(input).attr('id') + '-error');
                if (typeof $(after).get(0) === "undefined") {
                    $(input).parent().after('<div ' +
                        'for="' + $(input).attr('id') + '" ' +
                        'generated="true" ' +
                        'class="mage-error" ' +
                        'id="' + $(input).attr('id') + '-error">' +
                        $t('This is a required field.') +
                        '</div>');
                }
            },

            _removeErrorToInput: function (input) {
                $(input).removeClass('mage-error');
            },

            _removeErrorAfterInput: function (input) {
                var after = $('#' + $(input).attr('id') + '-error');
                if (typeof $(after).get(0) !== "undefined") {
                    $(after).remove();
                }
            },

            getName: function () {
                return window.checkoutConfig.quoteData.block_delivery;
            }
        });
    }
);
