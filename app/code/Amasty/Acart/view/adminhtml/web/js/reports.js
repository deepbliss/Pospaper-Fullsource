define([
    'jquery',
    'mage/translate',
    'Amasty_Acart/js/charts'
], function ($, $t, charts) {
    'use strict';

    $.widget('amasty_acart.Reports', {
        options: {
            ajaxUrl: '',
            reportError: '[data-amacart-js="report-error"]',
            potentialRevenuePlate: '[data-amacart-js="potential-revenue"]',
            recoveredRevenuePlate: '[data-amacart-js="recovered-revenue-plate"]',
            emailSentPlate: '[data-amacart-js="email-sent-plate"]',
            cartRestoredPlate: '[data-amacart-js="cart-restored-plate"]',
            ordersPlacedPlate: '[data-amacart-js="orders-placed-plate"]',
            efficiencyValue: '[data-amacart-js="efficiency"]',
            dataRange: '[data-amacart-js="data-select"]',
            dataHiddenRange: '[data-amacart-js="hidden-range"]'
        },

        _create: function () {
            var self = this;

            $('[data-amacart-js="data-range"]').parent().attr('data-amacart-js', 'hidden-range');

            $(this.options.dataRange).on('change', self.checkPeriodVisibility.bind(self));
            $('[data-amacart-js="report-submit"]').on('click', self.refresh.bind(self));

            $(document).ready(function() {
                self.refresh();
            });
        },

        checkPeriodVisibility: function () {
            if ($(this.options.dataRange).val() === "0") {
                $(this.options.dataHiddenRange).show();
            } else {
                $(this.options.dataHiddenRange).hide();
            }
        },

        refresh: function () {
            var self = this;

            $.ajax({
                showLoader: true,
                url: self.options.ajaxUrl,
                dataType: 'JSON',
                data: $('.entry-edit.form-inline :input').serializeArray(),
                type: "POST",
                success: function (response) {
                    if (response.type === 'success') {
                        $(self.options.reportError).hide();
                        self.setData(response.data);
                        charts({'data': response.data});
                    }

                    if (response.type === 'warning') {
                        $(self.options.reportError).text(response.message).show();
                        $(self.options.reportError).removeClass('message-error error').addClass('message-info info');
                    }

                    if (response.type === 'error') {
                        $(self.options.reportError).text(response.message).show();
                        $(self.options.reportError).removeClass('message-info info').addClass('message-error error');
                    }
                }
            });
        },

        setData: function (data) {
            var self = this;

            Object.keys(data).map(function(objectKey, index) {
                $('[data-amacart-js="' + objectKey + '"]').text(data[objectKey]);
            });

            $(self.options.potentialRevenuePlate).html(data['potential-revenue']);
            $(self.options.recoveredRevenuePlate).html(data['recovered-revenue']);
            $(self.options.emailSentPlate).html(data['sent-total']);
            $(self.options.cartRestoredPlate).html(data['restored-total']);
            $(self.options.ordersPlacedPlate).html(data['placed-total']);
            $(self.options.efficiencyValue).html(data['efficiency']);
        }
    });

    return $.amasty_acart.Reports;
});
