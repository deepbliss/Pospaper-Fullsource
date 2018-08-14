/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'Magento_Ui/js/form/components/insert-listing',
], function (InsertListing) {
    'use strict';

    return InsertListing.extend({
        defaults: {
            maxTotalSelected: 0,
            lastSelections: {},
            noMoreSelection: false,
            exports: {
                noMoreSelection: '${ $.ns }.${ $.ns }.listing_top.listing_paging:noMoreSelection'
            },
        },

        /**
         * @inheritdoc
         */
        initObservable: function () {
            return this._super()
                .observe(['noMoreSelection']);
        },

        /**
         * @inheritdoc
         */
        onSelectedChange: function () {
            if (this.maxTotalSelected > 0) {
                var provider = this.selections(),
                    selections,
                    totalSelected,
                    selected;
                selections = provider && provider.getSelections();
                totalSelected = provider.totalSelected();

                if (totalSelected > this.maxTotalSelected) {
                    if (selections.excludeMode) {
                        provider.excludeMode(false);
                        provider.selected(this.lastSelections.selected);
                    } else {
                        selected = selections.selected;

                        var position = this.maxTotalSelected - totalSelected,
                            count = totalSelected - this.maxTotalSelected;
                        selected.splice(position, count);

                        provider.selected(selected);
                    }
                }

                if (!selections.excludeMode) {
                    this.lastSelections = selections;
                }

                this.noMoreSelection(this.maxTotalSelected <= totalSelected);
            }

            return this._super();
        },
    });
});
