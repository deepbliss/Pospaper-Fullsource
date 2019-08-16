define([
    'jquery',
    'Magento_Ui/js/modal/alert'
], function($, alert) {
    var test = {
        url: null,
        ruleId: null,
        successMessage: '',
        init: function(url, ruleId, successMessage) {
            this.url = url;
            this.ruleId = ruleId;
            this.successMessage = successMessage;
        },
        send: function(id){
            var self = this;

            $.ajax({
                url: self.url,
                method: 'get',
                showLoader: true,
                data: {
                    quote_id: id,
                    rule_id: self.ruleId
                },
                success: function(response) {
                    var message = response.error ?
                        response.errorMsg :
                        self.successMessage;
                    alert({
                        content: message,
                        actions: {
                            confirm: function () {

                            }
                        }
                    })
                }
            });
        }
    };

    return test;
});