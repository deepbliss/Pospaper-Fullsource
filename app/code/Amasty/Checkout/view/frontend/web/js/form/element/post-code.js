define([
    'underscore',
    'Magento_Ui/js/form/element/post-code'
], function (_, Postcode) {
    'use strict';

    return Postcode.extend({
        update: function (value) {
            if (this.skipValidation) {
                this.error(false);
                this.validation = _.omit(this.validation, 'required-entry');
                this.required(false);
            }
            else {
                this._super();
            }
        }
    });
});
