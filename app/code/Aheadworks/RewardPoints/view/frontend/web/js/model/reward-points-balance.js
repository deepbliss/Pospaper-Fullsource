/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define(
    [
         'ko'
    ],
    function (ko) {
        'use strict';
        
        var customerRewardPointsBalance = ko.observable(0);
        var customerRewardPointsBalanceCurrency = ko.observable(0);
        var customerRewardPointsOnceMinBalance = ko.observable(0);
        var customerRewardPointsSpendRateByGroup = ko.observable(0);
        var customerRewardPointsSpendRate = ko.observable(0);
        
        return {
            /**
             * Retrieve customer reward points balance
             * 
             * @return {Number}
             */
            customerRewardPointsBalance: customerRewardPointsBalance,
            
            /**
             * Retrieve customer reward points currency balance
             * 
             * @return {Number}
             */
            customerRewardPointsBalanceCurrency: customerRewardPointsBalanceCurrency,

            /**
             * Retrieve customer reward points once min balance
             *
             * @return {Number}
             */
            customerRewardPointsOnceMinBalance: customerRewardPointsOnceMinBalance,

            /**
             * Retrieve customer reward points spend rate by group
             *
             * @return {Number}
             */
            customerRewardPointsSpendRateByGroup: customerRewardPointsSpendRateByGroup,

            /**
             * Retrieve customer reward points spend rate
             *
             * @return {Number}
             */
            customerRewardPointsSpendRate: customerRewardPointsSpendRate
        }
    }
);
