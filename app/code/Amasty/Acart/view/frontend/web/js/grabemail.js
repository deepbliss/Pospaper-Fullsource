define([
    'jquery'
], function($) {
    return {
        timers: null,
        url: null,
        isNeedLogEmail: false,
        run: function(url)
        {
            this.url = url;
            $(document).on('keyup', '#customer-email', this.sendEmail.bind(this));
        },
        validateEmail: function(email) {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        ajaxCall: function (value){
            $.ajax({
                url: this.url,
                method: 'get',
                global: false,
                data: {
                    form_key: window.checkoutConfig ? window.checkoutConfig.formKey : '',
                    email: value
                }
            });
        },
        sendEmail: function(e){
            var value = e.target.value;
            if (this.validateEmail(value) && this.isNeedLogEmail == true){

                if (this.timers != null){
                    clearTimeout(this.timers);
                }

                this.timers = setTimeout(function(){
                    this.ajaxCall(value)
                }.bind(this), 500);
            }
        }
    };
});