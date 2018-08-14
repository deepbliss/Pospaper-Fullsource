/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define(
    [
        'ko',
        'Aheadworks_RewardPoints/js/view/summary/reward-points',
        'Aheadworks_RewardPoints/js/model/is-applied-flag',
        'Aheadworks_RewardPoints/js/action/remove-reward-points',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function (
        ko,
        Component,
        isAppliedFlag,
        removeRewardPointsAction,
        fullScreenLoader
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Aheadworks_RewardPoints/summary/one-step/reward-points'
            },

            /**
             * Remove reward points
             */
            remove: function () {
                var isLoading = ko.observable(true);

                fullScreenLoader.startLoader();
                isLoading.subscribe(function (flag) {
                    if (!flag) {
                        fullScreenLoader.stopLoader();
                    }
                });
                removeRewardPointsAction(isAppliedFlag, isLoading);
            }
        });
    }
);
