/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'mageUtils',
    'moment',
    'Magento_Ui/js/grid/columns/column'
], function (utils, moment, Column) {
    'use strict';

    return Column.extend({
        defaults: {
            timeOffset: 0,
            dateFormat: 'MMM d, YYYY h:mm:ss A'
        },

        /**
         * Overrides base method to normalize date format.
         *
         * @returns {DateColumn} Chainable.
         */
        initConfig: function () {
            this._super();

            this.dateFormat = utils.normalizeDate(this.dateFormat);

            return this;
        },

        /**
         * Formats incoming date based on the 'dateFormat' property.
         *
         * @returns {String} Formatted date.
         */
        getLabel: function (value, format) {
            var date = moment.utc(this._super()).add(this.timeOffset, 'seconds');

            date = date.isValid() ?
                date.format(format || this.dateFormat) :
                '';

            return date;
        }
    });
});
