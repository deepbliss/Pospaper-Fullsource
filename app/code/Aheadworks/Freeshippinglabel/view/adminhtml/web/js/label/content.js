/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'jquery',
    'mage/template'
], function ($, mageTemplate) {
    'use strict';

    $.widget('mage.awFslabelContent', {
        dataIndex: 0,
        options: {
            elementSelector: '',
            templateSelector: '[data-role=row-template]',
            rowsContainer: '[data-role=rows-container]',
            row: '[data-role=row]',
            storeViewField: '[data-role=store-view]',
            message: '[data-role=message]',
            addButton: '[data-role=add-button]',
            deleteButton: '[data-role=delete-button]',
            deleteFlag: '[data-role=delete-flag]',
            optionValues: []
        },

        /**
         * Initialize widget
         */
        _create: function() {
            var self = this;

            this.template = mageTemplate(this.options.templateSelector);
            $.each(this.options.optionValues, function() {
                self._addRow(this);
            });
            this._bind();
        },

        /**
         * Event binding
         */
        _bind: function () {
            this._on({
                'click [data-role=add-button]': function () {
                    this._addRow({});
                },
                'click [data-role=delete-button]': function (event) {
                    this._deleteRow($(event.currentTarget).data('index'));
                }
            });
        },

        /**
         * Add row
         *
         * @param {Object} data
         * @private
         */
        _addRow: function (data) {
            data.index = this.dataIndex++;
            this.element
                .find(this.options.rowsContainer)
                .append(this.template({data: data}));
            if ('store_id' in data) {
                this.element
                    .find(this.options.storeViewField)
                    .filter('[data-index=' + data.index + ']')
                    .val(data.store_id);
            }
        },

        /**
         * Delete row
         *
         * @param {integer} index
         * @private
         */
        _deleteRow: function (index) {
            this.element
                .find(this.options.deleteFlag)
                .filter('[data-index=' + index + ']')
                .val(1);
            this.element
                .find(this.options.row)
                .filter('[data-index=' + index + ']')
                .hide();
            this.element
                .find(this.options.message)
                .filter('[data-index=' + index + ']')
                .removeClass('required-entry');
        }
    });

    return $.mage.awFslabelContent;
});
