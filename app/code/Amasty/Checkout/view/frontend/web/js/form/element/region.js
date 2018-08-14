define(
    [
        'Magento_Ui/js/form/element/region',
        'uiRegistry'
    ],
    function (
        Component,
        registry
    ) {
        'use strict';

        return Component.extend({
            initialize: function (config) {
                this._super();

                registry.get(this.parentName + '.' + 'region_id_input', function (region) {
                    if (config.value) {
                        region.value(config.value);
                    }
                });

                return this;
            },

            filter: function (value, field) {
                var country = registry.get(this.parentName + '.' + 'country_id');
                if (country) {
                    this._super();
                }
            }
        });
    }
);
