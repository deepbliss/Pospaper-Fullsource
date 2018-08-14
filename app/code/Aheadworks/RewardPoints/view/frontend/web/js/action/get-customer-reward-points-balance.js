/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define(
    [
        'jquery',
        'Aheadworks_RewardPoints/js/model/resource-url-manager',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Customer/js/model/customer',
        'Aheadworks_RewardPoints/js/model/reward-points-balance'
    ],
    function ($, urlBuilder, storage, errorProcessor, customer, rewardPointsBalance) {
        'use strict';

        return function (deferred, messageContainer) {
            var serviceUrl;

            deferred = deferred || $.Deferred();
            
            serviceUrl = urlBuilder.getCustomerRewardPointsBalanceUrl(customer.customerData.id);

            return storage.get(
                serviceUrl, false
            ).done(
                function (response) {
                    if (response.customer_reward_points_balance) {
                        rewardPointsBalance.customerRewardPointsBalance(
                            response.customer_reward_points_balance
                        );
                    } 
                    
                    if (response.customer_reward_points_balance_currency) {
                        rewardPointsBalance.customerRewardPointsBalanceCurrency(
                            response.customer_reward_points_balance_currency
                        );
                    }

                    if (response.customer_reward_points_once_min_balance) {
                        rewardPointsBalance.customerRewardPointsOnceMinBalance(
                            response.customer_reward_points_once_min_balance
                        );
                    }

                    if (response.customer_reward_points_spend_rate_by_group) {
                        rewardPointsBalance.customerRewardPointsSpendRateByGroup(
                            response.customer_reward_points_spend_rate_by_group
                        );
                    }

                    if (response.customer_reward_points_spend_rate) {
                        rewardPointsBalance.customerRewardPointsSpendRate(
                            response.customer_reward_points_spend_rate
                        );
                    }
                    
                    deferred.resolve();
                }
            ).fail(
                function (response) {
                    errorProcessor.process(response, messageContainer);
                    deferred.reject();
                }
            );
        };
    }
);
