define([
	'jquery',
	'Magento_Ui/js/modal/alert',
    'mage/translate'
], function ($, alert) {
	"use strict";

	var GTMAPI = GTMAPI || {};

	var triggerButton = $('#save_gtm_api'),
		accountID = $('#weltpixel_googletagmanager_api_account_id'),
		containerID = $('#weltpixel_googletagmanager_api_container_id'),
		uaTrackingID = $('#weltpixel_googletagmanager_api_ua_tracking_id'),
		ipAnonymization = $('#weltpixel_googletagmanager_api_ip_anonymization'),
		displayAdvertising = $('#weltpixel_googletagmanager_api_display_advertising'),
		formKey = $('#api_form_key');

	var conversionTrackingButton = $('#save_gtm_api_conversion_tracking'),
		conversionId = $('#weltpixel_googletagmanager_adwords_conversion_tracking_google_conversion_id'),
		conversionLabel = $('#weltpixel_googletagmanager_adwords_conversion_tracking_google_conversion_label'),
		conversionCurrencyCode = $('#weltpixel_googletagmanager_adwords_conversion_tracking_google_conversion_currency_code');

	var remarketingButton = $('#save_gtm_api_remarketing'),
		remarketingConversionCode = $('#weltpixel_googletagmanager_adwords_remarketing_conversion_code'),
		remarketingConversionLabel = $('#weltpixel_googletagmanager_adwords_remarketing_conversion_label');


	GTMAPI.initialize = function (itemPostUrl) {
		var that = this;
		$(triggerButton).click(function() {
			var validation = that._validateInputs();
			if (validation.length) {
				alert({content: validation.join('')});
			} else {
				$.ajax({
					showLoader: true,
					url: itemPostUrl,
					data: {
						'form_key' : formKey.val(),
						'account_id' : accountID.val().trim(),
						'container_id' : containerID.val().trim(),
						'ua_tracking_id' : uaTrackingID.val().trim(),
						'ip_anonymization' : ipAnonymization.val(),
						'display_advertising' : displayAdvertising.val()
					},
					type: "POST",
					dataType: 'json'
				}).done(function (data) {
					alert({content: data.join('<br/>')});
				});
			}
		});

        var url = window.location.href;
        var newUrl = url.split('?')[0];
        if (url != newUrl) {
            window.history.pushState({}, window.document.title, newUrl);
        }
	};

	GTMAPI.initializeConversionTracking = function (itemPostUrl) {
		var that = this;
		$(conversionTrackingButton).click(function() {
			var validation = that._validateConversionTrackingInputs();
			if (validation.length) {
				alert({content: validation.join('')});
			} else {
				$.ajax({
					showLoader: true,
					url: itemPostUrl,
					data: {
						'form_key' : formKey.val(),
						'account_id' : accountID.val().trim(),
						'container_id' : containerID.val().trim(),
						'conversion_id' : conversionId.val().trim(),
						'conversion_label' : conversionLabel.val().trim(),
						'conversion_currency_code' : conversionCurrencyCode.val().trim()
					},
					type: "POST",
					dataType: 'json'
				}).done(function (data) {
					alert({content: data.join('<br/>')});
				});
			}
		});
	};

	GTMAPI.initializeRemarketing = function (itemPostUrl) {
        var that = this;
        $(remarketingButton).click(function() {
            var validation = that._validateRemarketingInputs();
            if (validation.length) {
                alert({content: validation.join('')});
            } else {
                $.ajax({
                    showLoader: true,
                    url: itemPostUrl,
                    data: {
                        'form_key' : formKey.val(),
                        'account_id' : accountID.val().trim(),
                        'container_id' : containerID.val().trim(),
                        'conversion_code' : remarketingConversionCode.val().trim(),
                        'conversion_label' : remarketingConversionLabel.val().trim()
                    },
                    type: "POST",
                    dataType: 'json'
                }).done(function (data) {
                    alert({content: data.join('<br/>')});
                });
            }
        });
    };

	GTMAPI._validateInputs = function () {
		var errors = [];
		if (accountID.val().trim() == '') {
			errors.push($.mage.__('Please specify the Account ID') + '<br/>');
		}
		if (containerID.val().trim() == '') {
			errors.push($.mage.__('Please specify the Container ID') + '<br/>');
		}
		if (uaTrackingID.val().trim() == '') {
			errors.push($.mage.__('Please specify the Universal Tracking ID') + '<br/>');
		}

		return errors;
	};


	GTMAPI._validateConversionTrackingInputs = function () {
		var errors = [];
		if (accountID.val().trim() == '') {
			errors.push($.mage.__('Please specify the Account ID in GTM API Configuration section') + '<br/>');
		}
		if (containerID.val().trim() == '') {
			errors.push($.mage.__('Please specify the Container ID in GTM API Configuration section') + '<br/>');
		}
		if (conversionId.val().trim() == '') {
			errors.push($.mage.__('Please specify the Google Conversion Id') + '<br/>');
		}
		if (conversionLabel.val().trim() == '') {
			errors.push($.mage.__('Please specify the Google Conversion Label') + '<br/>');
		}
		if (conversionCurrencyCode.val().trim() == '') {
			errors.push($.mage.__('Please specify the Google Convesion Currency Code') + '<br/>');
		}

		return errors;
	};


	GTMAPI._validateRemarketingInputs = function () {
        var errors = [];
        if (accountID.val().trim() == '') {
            errors.push($.mage.__('Please specify the Account ID in GTM API Configuration section') + '<br/>');
        }
        if (containerID.val().trim() == '') {
            errors.push($.mage.__('Please specify the Container ID in GTM API Configuration section') + '<br/>');
        }
        if (remarketingConversionCode.val().trim() == '') {
            errors.push($.mage.__('Please specify the Conversion Code') + '<br/>');
        }
        /**
        if (remarketingConversionLabel.val().trim() == '') {
            errors.push($.mage.__('Please specify the Conversion Label') + '<br/>');
        }
		*/
        return errors;
    };

	return GTMAPI;
});