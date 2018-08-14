/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/totals'
    ],
    function (Component, totals) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Aheadworks_RewardPoints/summary/reward-points'
            },
            
            /**
             * Order totals
             * 
             * @return {Object}
             */
            totals: totals.totals(),
            
            /**
             * Is display reward points totals
             * 
             * @return {boolean}
             */
            isDisplayed: function() {
                return this.isFullMode() && this.getPureValue() != 0;
            },
            
            /**
             * Get title 
             * 
             * @return {string} 
             */
            getTitle: function() {
                if (this.totals) {
                    var rewardPoints = totals.getSegment('aw_reward_points');
                    
                    if (rewardPoints) {
                        return rewardPoints.title;
                    }
                    return null;
                }
            },
            
            /**
             * Get total value
             * 
             * @return {number}
             */
            getPureValue: function() {
                var price = 0;
                if (this.totals) {
                    var rewardPoints = totals.getSegment('aw_reward_points');
                    
                    if (rewardPoints) {
                        price = rewardPoints.value;
                    }
                }
                return price; 
            },
            
            /**
             * Get total value
             * 
             * @return {string}
             */
            getValue: function() {
                return this.getFormattedPrice(this.getPureValue());
            }
        });
    }
);
