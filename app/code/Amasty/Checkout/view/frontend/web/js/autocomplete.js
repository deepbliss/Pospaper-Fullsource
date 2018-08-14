define([
    'Magento_Ui/js/lib/view/utils/async',
    'uiRegistry',
    'ko'
], function ($, registry, ko) {
    return {
        isReady: ko.observable(false),

        geolocate: function(autocomplete) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var geolocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    var circle = new google.maps.Circle({
                        center: geolocation,
                        radius: position.coords.accuracy
                    });
                    autocomplete.setBounds(circle.getBounds());
                });
            }
        },

        registerField: function (component) {
            var self = this;
            if (this.isReady()) {
                return this.init(component);
            }
            else {
                this.isReady.subscribe(function (isReady) {
                    if (isReady) {
                        return self.init(component);
                    }
                });
            }
        },

        init: function (component) {
            var self = this;

            registry.get(component, function (rootComponent) {
                registry.get(component + '.street.0', function (inputComponent) {
                    $.async({
                        selector: '#' + inputComponent.uid
                    }, function (input) {
                        var autocomplete = new google.maps.places.Autocomplete(
                            input,
                            {types: ['geocode']}
                        );

                        autocomplete.addListener('place_changed', function () {
                            self.fillInAddress(autocomplete, rootComponent);
                        });

                        self.geolocate(autocomplete);
                    });
                });
            });
        },

        fillInAddress: function (autocomplete, rootComponent) {
            var place = autocomplete.getPlace();

            if (!place.address_components)
                return;

            var streetComponent = rootComponent.getChild('street').getChild(0);
            var street = place.name.replace(',', '');
            streetComponent.value(street);

            if (rootComponent.hasChild('postcode')) {
                rootComponent.getChild('postcode').value('');
            }
            if (rootComponent.hasChild('region_id_input')) {
                rootComponent.getChild('region_id_input').value('');
            }
            if (rootComponent.hasChild('city')) {
                rootComponent.getChild('city').value('');
            }

            var isRegionApplied = false;

            for (var i = place.address_components.length - 1; i >= 0; i--) {
                var addressComponent = place.address_components[i];
                var addressType = addressComponent.types[0];

                switch (addressType) {
                    case 'country':
                        if (rootComponent.hasChild('country_id')) {
                            rootComponent.getChild('country_id').value(addressComponent.short_name);
                        }
                        break;
                    case 'locality':
                        if (rootComponent.hasChild('city')) {
                            rootComponent.getChild('city').value(addressComponent.long_name);
                        }
                        break;
                    case 'postal_code':
                        if (rootComponent.hasChild('postcode')) {
                            rootComponent.getChild('postcode').value(addressComponent.long_name);
                        }
                        break;
                    case 'administrative_area_level_1':
                    case 'administrative_area_level_2':
                        if (isRegionApplied)
                            break;

                        var stateSelect = rootComponent.getChild('region_id');
                        if (stateSelect && stateSelect.visible()) {
                            var value = addressComponent.short_name;

                            var country = checkoutConfig.defaultCountryId;
                            if (rootComponent.hasChild('country_id')) {
                                country = rootComponent.getChild('country_id').value();
                            }
                            if (country in window.amasty_checkout_regions && value in window.amasty_checkout_regions[country]) {
                                stateSelect.value(window.amasty_checkout_regions[country][value]);
                            }
                        }
                        else if (rootComponent.hasChild('region_id_input')) {
                            rootComponent.getChild('region_id_input').value(addressComponent.long_name);
                        }

                        isRegionApplied = true;
                        break;
                }
            }
        }
    };
});
