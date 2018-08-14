/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'ko',
    'Magento_Ui/js/lib/view/utils/async',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/grid/dnd'
], function (ko, $, _, registry, Dnd) {
    'use strict';

    return Dnd.extend({
        /**
         * Creates clone of a target table with only specified column visible
         *
         * @param {HTMLTableHeaderCellElement} elem - Dragging column
         * @returns {Dnd} Chainbale
         */
        _cloneTable: function (elem) {
            var clone       = this.table.cloneNode(true),
                columnIndex = this._getColumnIndex(elem),
                headRow     = clone.tHead.firstElementChild,
                headCells   = _.toArray(headRow.cells),
                tableBody   = clone.tBodies[0],
                tableFoot   = clone.tFoot,
                bodyRows    = _.toArray(tableBody.children),
                footRows    = _.toArray(tableFoot.children),
                origTrs     = this.table.tBodies[0].children,
                origTrsFoot = this.table.tFoot.children
            self = this;

            clone.style.width = elem.offsetWidth + 'px';

            headCells.forEach(function (th, index) {
                if (index !== columnIndex) {
                    headRow.removeChild(th);
                }
            });

            headRow.cells[0].style.height = elem.offsetHeight + 'px';

            bodyRows.forEach(function (row, rowIndex) {
                self._cloneRows (row, rowIndex, headCells, columnIndex, origTrs, tableBody);
            });
            footRows.forEach(function (row, rowIndex) {
                self._cloneRows (row, rowIndex, headCells, columnIndex, origTrsFoot, tableFoot);
            });

            this.dragTable = clone;

            $(clone)
                .addClass('_dragging-copy')
                .appendTo('body');

            return this;
        },

        _cloneRows: function(row, rowIndex, headCells, columnIndex, origTrs, table) {
            var cells = row.cells,
                cell;

            if (cells.length !== headCells.length) {
                table.removeChild(row);
                return;
            }

            cell = row.cells[columnIndex].cloneNode(true);

            while (row.firstElementChild) {
                row.removeChild(row.firstElementChild);
            }

            cell.style.height = origTrs[rowIndex].cells[columnIndex].offsetHeight + 'px';

            row.appendChild(cell);
        }
    });
});
