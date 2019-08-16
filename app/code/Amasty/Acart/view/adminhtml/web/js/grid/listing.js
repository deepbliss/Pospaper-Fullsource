/**
 * @api
 */
define([
    'jquery',
    'Magento_Ui/js/grid/listing',
    'mage/translate'
], function ($, Listing) {
    'use strict';

    return Listing.extend({
        defaults: {
            template: 'Amasty_Acart/grid/listing',
            imports: {
                historyUrl: 'amasty_acart_queue_grid.amasty_acart_queue_grid_data_source:history_url'
            }
        },

        getEmptyGridLabel: function () {
            return $.mage.__('There are currently no emails in the queue. Sent emails can be found on the '
            + '<a class="new-page-url" href="%1" target="_blank">History</a> page. <br>Please keep in mind there is a time gap of approximately 10 minutes '
            + 'before carts abandoned by customers actually considered abandoned by Magento.').replace('%1', this.historyUrl);
        }
    });
});