/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define(
    [
         'Magento_Checkout/js/model/resource-url-manager'
    ],
    function (urlManager) {
        'use strict';
        return {
            /**
             * Retrieve apply reward points url
             * 
             * @return {string}
             */
            getApplyRewardPointsUrl: function (quoteId) {
                var params = (urlManager.getCheckoutMethod() == 'guest') ? {quoteId: quoteId} : {};
                var urls = {
                        'guest': '',
                        'customer': '/carts/mine/apply-aw-reward-points/'
                };
                return urlManager.getUrl(urls, params);
            },
            
            /**
             * Retrieve remove reward points url
             * 
             * @return {string}
             */
            getRemoveRewardPointsUrl: function  (quoteId) {
                var params = (urlManager.getCheckoutMethod() == 'guest') ? {quoteId: quoteId} : {};
                var urls = {
                        'guest': '', 
                        'customer': '/carts/mine/remove-aw-reward-points/'
                };
                return urlManager.getUrl(urls, params);
            },
            
            /**
             * Retrieve get customer reward points balance url
             * 
             * @return {string}
             */
            getCustomerRewardPointsBalanceUrl: function  (customerId) {
                var params = {customerId: customerId};
                var urls = {
                        'customer': '/carts/mine/aw-get-customer-reward-points'
                };
                return urlManager.getUrl(urls, params);
            }
        };
    }
);