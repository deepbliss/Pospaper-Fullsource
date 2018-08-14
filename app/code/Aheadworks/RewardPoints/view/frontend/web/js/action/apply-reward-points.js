/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

/*global define,alert*/
define(
    [
        'ko',
        'jquery',
        'Aheadworks_RewardPoints/js/model/resource-url-manager',
        'Aheadworks_RewardPoints/js/model/payment/reward-points-messages',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/action/get-payment-information',
        'mage/storage',
        'mage/translate'
    ],
    function (
        ko,
        $,
        urlManager,
        messageContainer,
        quote,
        totals,
        errorProcessor,
        getPaymentInformationAction,
        storage,
        $t
    ) {
        'use strict';
        return function (isApplied, isLoading, deferred) {
            var quoteId = quote.getQuoteId(),
                url = urlManager.getApplyRewardPointsUrl(quoteId);

            if (typeof deferred == 'undefined') {
                deferred = $.Deferred();
            }
            
            return storage.put(
                url,
                {},
                true
            ).done(
                function (response) {
                    if (response[0] != 'undefined' && response[0].success) {
                        var totalsDeferred = $.Deferred();
                        isLoading(false);
                        isApplied(true);
                        totals.isLoading(true);
                        getPaymentInformationAction(totalsDeferred);
                        $.when(totalsDeferred).done(function () {
                            totals.isLoading(false);
                            deferred.resolve();
                        });
                        messageContainer.addSuccessMessage({'message': response[0].message});
                    }
                }
            ).fail(
                function (response) {
                    isLoading(false);
                    totals.isLoading(false);
                    errorProcessor.process(response, messageContainer);
                    deferred.reject();
                }
            );
        };
    }
);
