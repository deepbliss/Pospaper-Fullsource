/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define(
    [
        'ko',
        'Magento_Checkout/js/model/totals'
    ],
    function (ko, totals) {
        'use strict';

        var rewardPoints = totals.getSegment('aw_reward_points');

        return ko.observable(rewardPoints != null && rewardPoints.value != 0);
    }
);
