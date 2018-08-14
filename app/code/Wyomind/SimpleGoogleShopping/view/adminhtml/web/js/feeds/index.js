/**
 * Copyright © 2017 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */


define(["jquery",
    "mage/mage",
    "jquery/ui",
    "Magento_Ui/js/modal/modal",
    "Magento_Ui/js/modal/confirm"], function ($, mage, ui, modal, confirm) {
    'use strict';
    return {
        generate: function (url) {
            confirm({
                title: $.mage.__("Generate data feed"),
                content: $.mage.__("Generate a data feed can take a while. Are you sure you want to generate it now ?"),
                actions: {
                    confirm: function () {
                        document.location.href = url;
                    },
                    cancel: function () {
                        $('.col-action select.admin__control-select').val("");
                    }
                }
            });
        },
        delete: function (url) {
            confirm({
                title: $.mage.__("Delete data feed"),
                content: $.mage.__("Are you sure you want to delete this feed ?"),
                actions: {
                    confirm: function () {
                        document.location.href = url;
                    },
                    cancel: function () {
                        $('.col-action select.admin__control-select').val("");
                    }
                }
            });
        },
        updater_url: "",
        updater_init: function () {
            var data = new Array();
            $('.updater').each(function () {
                var feed = [$(this).attr('id').replace("feed_", ""), $(this).attr('cron')];
                data.push(feed);
            });
            $.ajax({
                url: this.updater_url,
                data: {
                    data: JSON.stringify(data)
                },
                type: 'GET',
                showLoader: false,
                success: function (data) {
                    data.each(function (r) {
                        $("#feed_" + r.id).parent().html(r.content);
                    });
                    setTimeout(this.updater_init.bind(this), 1000);
                }.bind(this)
            });
        }
    };
});