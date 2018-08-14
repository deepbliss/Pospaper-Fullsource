/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

/**
 * Initialization widget for share
 *
 * @method click()
 */
define([
    'jquery'
], function($) {
    "use strict";

    $.widget('awrp.awRewardPointsShare', {
        options: {
            url: '/',
            productId: 0,
            network: ''
        },

        /**
         * Initialize widget
         */
        _create: function() {
            this._bind();
        },
        /**
         * Bind event
         */
        _bind: function() {
            this._on({
                click: this.click
            });
        },
        /**
         * Redirect to url with rule id and uenc param
         */
        click: function()
        {
            $.ajax({
                url: this.options.url,
                data: {
                    productId: this.options.productId,
                    network: this.options.network
                },
                type: 'GET',
                cache: false,
                dataType: 'json',
                context: this,

                /**
                 * Response handler
                 * @param {Object} response
                 */
                success: function (response) {
                    console.log(response);
                }
            });
        }
    });

    return $.awrp.awRewardPointsShare;
});
