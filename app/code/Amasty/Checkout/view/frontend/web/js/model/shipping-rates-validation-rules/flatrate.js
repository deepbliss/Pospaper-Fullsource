define([], function () {
   'use strict'

    return {
        /**
         * @return {Object}
         */
        getRules: function () {
            return {
                'country_id': {
                    'required': true
                },
                'region_id': {
                    'required': true
                },
                'city': {
                    'required': true
                },
                'street': {
                    'required': true
                },
                'telephone': {
                    'required': true
                },
                'firstname': {
                    'required': true
                },
                'lastname': {
                    'required': true
                }
            };
        }
    }
});
