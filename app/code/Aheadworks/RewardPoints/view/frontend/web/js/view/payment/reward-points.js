/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'Magento_Checkout/js/model/totals',
        'Magento_Catalog/js/price-utils',
        'Magento_Customer/js/model/customer',
        'Aheadworks_RewardPoints/js/action/apply-reward-points',
        'Aheadworks_RewardPoints/js/action/remove-reward-points',
        'Aheadworks_RewardPoints/js/action/get-customer-reward-points-balance',
        'Aheadworks_RewardPoints/js/model/reward-points-balance',
        'Aheadworks_RewardPoints/js/model/is-applied-flag',
        'mage/translate'
     ],
    function (
            $, 
            ko, 
            Component, 
            totals, 
            priceUtils,
            customer, 
            applyRewardPoints, 
            removeRewardPoints, 
            getCustomerRewardPointsBalanceAction,
            rewardPointsBalance,
            isAppliedFlag,
            $t
        ){
        'use strict';
        
        var isLoading = ko.observable(false);
        
        return Component.extend({
            defaults: {
                template: 'Aheadworks_RewardPoints/payment/reward-points'
            },
            
            /**
             * Check if reward points is apply
             * 
             * @return {boolean}
             */
            isApplied: isAppliedFlag,
            
            /**
             * Is loading
             * 
             * @return {boolean}
             */
            isLoading: isLoading,
            
            /**
             * Check if customer is logged in
             * 
             * @return {boolean}
             */
            isCustomerLoggedIn: function(){
                return customer.isLoggedIn();
            },
            
            /**
             * Is display reward points block
             * 
             * @return {boolean}
             */
            isDisplayed: function() {
                var isDisplayed = false;
                if (this.isCustomerLoggedIn()) {
                    getCustomerRewardPointsBalanceAction();
                    isDisplayed = rewardPointsBalance.customerRewardPointsOnceMinBalance() == 0
                        && rewardPointsBalance.customerRewardPointsSpendRateByGroup()
                        && rewardPointsBalance.customerRewardPointsSpendRate();
                }
                return isDisplayed;
            },
            
            /**
             * Apply reward points
             * 
             * @return {void}
             */
            apply: function() {
                if (this.validate()) {
                    isLoading(true);
                    applyRewardPoints(isAppliedFlag, isLoading);
                }
            },
            
            /**
             * Remove reward points
             * 
             * @return {void}
             */
            remove: function() {
                if (this.validate()) {
                    isLoading(true);
                    removeRewardPoints(isAppliedFlag, isLoading);
                }
            },
            
            /**
             * Validate
             * 
             * @return {boolean}
             */
            validate: function() {
                return true;
            },
            
            /**
             * Retrieve available points text
             * 
             * @return {String}
             */
            getAvailablePointsText: function() {
                return rewardPointsBalance.customerRewardPointsBalance()
                    + $t(' store reward points available ') 
                    + '(' 
                    + this.getFormattedPrice(rewardPointsBalance.customerRewardPointsBalanceCurrency()) 
                    + ')';
            },
            
            /**
             * Retrieve used points text
             * 
             * @return {String}
             */
            getUsedPointsText: function() {
                var rewardPoints = totals.getSegment('aw_reward_points');

                if (rewardPoints) {
                    return $t('Used ') + rewardPoints.title + ' (' + this.getFormattedPrice(rewardPoints.value) + ')';
                } else {
                    return '';
                }
            },
            /**
             * Format price
             * 
             * @return {String}
             */
            getFormattedPrice: function(price) {
                return priceUtils.formatPrice(price, window.checkoutConfig.priceFormat);
            }
        });
});
