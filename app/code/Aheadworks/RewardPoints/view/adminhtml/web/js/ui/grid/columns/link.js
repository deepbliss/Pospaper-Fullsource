/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'Magento_Ui/js/grid/columns/column'
], function (Component) {
    'use strict';

    return Component.extend({
        defaults: {
            bodyTmpl: 'Aheadworks_RewardPoints/ui/grid/cells/link'
        },

        /**
         * Retrieve link rows
         *
         * @returns {Array}
         */
        getLinkRows: function (record) {
            if (record[this.index] && !Array.isArray(record[this.index])) {
                return [record[this.index]];
            }
            return record[this.index];
        },
    });
});
