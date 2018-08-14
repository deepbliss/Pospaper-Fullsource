/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'jquery',
    'mage/template'
], function ($, mageTemplate) {
    'use strict';

    $.widget('awrp.rates', {
        options: {
            templateId: '',
            addButtonId: '',
            rowContainer: '',
            deleteButtonSelect: '',
            templateValues: {},
            defaultValues: {}
        },
        
        /**
         * @private
         */
        _create: function () {
            var widget = this;
            
            this.template = mageTemplate(this.options.templateId);

            $.each(this.options.templateValues, function () {
                widget.add(this);
            });

            $(this.options.addButtonId).on('click', function (event) {
                widget.add();
            });
        },
        
        /**
         * Add row template to table
         * 
         * @param {Object} rowData
         */
        add: function (rowData) {
            var rowTmpl;

            if (!rowData) {
                rowData = this.options.defaultValues;
                var d = new Date();
                rowData._id = '_' + d.getTime() + '_' + d.getMilliseconds();
            }

            rowTmpl = this.template(rowData);

            $(rowTmpl).appendTo($(this.options.rowContainer));

            if (rowData) {
                this._fillControlsWithData(rowData);
            }

            this._initDeleteButton();
        },
        
        /**
         * Remove row from table
         * 
         * @param {String} rowId
         */
        remove: function (rowId) {
            $('#' + rowId).remove();
        },
        
        /**
         * Fill elements with data
         * 
         * @param {Object} rowData
         * @private
         */
        _fillControlsWithData: function (rowData) {
            if (rowData.column_values) {
                var rowInputElementNames = Object.keys(rowData.column_values);
                
                for (var i = 0; i < rowInputElementNames.length; i++) {
                    if ($('#' + rowInputElementNames[i]).length > 0) {
                        $('#' + rowInputElementNames[i]).val(rowData.column_values[rowInputElementNames[i]]);
                    }
                }
            }
            
        },
        
        /**
         * Initial the handler for delete button
         * 
         * @private
         */
        _initDeleteButton: function () {
            var widget = this;
            
            $(this.options.deleteButtonSelect).click(function () {
                var id = $(this).attr('id').replace('_delete', '');
                widget.remove(id);
            });
        }
    });

    return $.awrp.rates;
});