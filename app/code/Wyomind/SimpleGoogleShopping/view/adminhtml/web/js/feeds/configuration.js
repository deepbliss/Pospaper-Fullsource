/**
 * Copyright © 2017 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

define(['jquery', "Magento_Ui/js/modal/confirm"], function ($, confirm) {
    'use strict';
    return {
        codeMirrorProductPattern: null,
        generate: function () {
            confirm({
                title: "Generate data feed",
                content: "Generate a data feed can take a while. Are you sure you want to generate it now ?",
                actions: {
                    confirm: function () {
                        $('#generate_i').val('1');
                        $('#edit_form').submit();
                    }
                }
            });
        },
        delete: function () {
            confirm({
                title: "Delete data feed",
                content: "Are you sure you want to delete this feed ?",
                actions: {
                    confirm: function () {
                        $('#back_i').val('1');
                        $('#edit_form').submit();
                    }
                }
            });
        }
    }
});

