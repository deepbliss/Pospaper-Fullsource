/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'Magento_Ui/js/form/provider',
], function (Provider) {
    'use strict';

    return Provider.extend({
        /**
         * @inheritdoc
         */
        save: function (options) {
            var data = this.get('data');

            data.customer_selections = JSON.stringify(data.customer_selections);

            this.client.save(data, options);

            return this;
        },
    });
});
