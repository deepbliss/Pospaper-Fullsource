/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

define([
    'uiCollection',
    'Magento_Ui/js/form/client',
    'Magento_Ui/js/modal/alert',
    'mage/translate'
], function (Collection, Client, alert, $t) {
    'use strict';

    return Collection.extend({
        defaults: {
            saveUrl: '${ $.submit_url }',
            responseData: {},
            modules: {
                listing: '${ $.provider }'
            }
        },

        /**
         * Initializes component
         *
         * @returns {Form} Chainable
         */
        initialize: function () {
            this._super();
            this.client = new Client({'urls': {'save': this.saveUrl}});

            return this;
        },

        /**
         * Set and send import data
         *
         * @returns {Form} Chainable
         */
        apply: function () {
            this.validateForm();
            if (this.get('params.invalid')) {
                return this;
            }

            this.client.save(
                this.prepareData(),
                {
                    ajaxSave: true,
                    ajaxSaveType: 'default',
                    response: {
                        data: this.onResponseData.bind(this),
                        status: this.onResponseStatus.bind(this)
                    }
                }
            );

            return this;
        },

        /**
         * Response data after import
         *
         * @param {Object} data
         */
        onResponseData: function (data) {
            this.set('responseData', data);
        },

        /**
         * Response data after import
         *
         * @param {Boolean} status
         */
        onResponseStatus: function (status) {
            if (typeof(status) !== 'undefined') {
                alert({
                    title: $t('Notice'),
                    content: this.responseData.messages
                });
                if (status) {
                    // Update listing
                    this.listing().set('params.t', Date.now());
                }
            }
        },

        /**
         * Validates each element and returns true, if all elements are valid
         */
        validateForm: function () {
            this.set('params.invalid', false);
            this.trigger('data.validate');
        },

        /**
         * Prepare data
         */
        prepareData: function() {
            var data = {};

            this.elems().forEach(function (elem, index) {
                data[elem.index] = elem.value();
            });

            return data;
        }
    });
});
