define([
        'jquery',
        'underscore',
        'mage/template',
        'Magento_Ui/js/modal/confirm',
        'prototype'
    ], function ($, _, mageTemplate, confirm) {
        var schedule = {
            rowTemplate: null,
            htmlId: '',
            itemsCount: 0,
            config: {},

            /**
             * @param tpl
             * @param htmlId
             */
            init: function (tpl, htmlId) {
                this.rowTemplate = mageTemplate(tpl);
                this.htmlId = htmlId;
            },

            /**
             * @param key
             * @param index
             * @return {string}
             */
            getId: function (key, index) {
                return this.htmlId + '_' + (index !== undefined ? index + '_' : '') + key;
            },

            /**
             * @param key
             * @param index
             * @return {*|jQuery|HTMLElement}
             */
            findObject: function (key, index) {
                return $('#' + this.getId(key, index)).get(0);
            },

            /**
             * @param key
             * @param index
             * @param value
             */
            setValue: function (key, index, value) {
                var object = this.findObject(key, index);
                if (object) {
                    switch (object.type) {
                        case 'checkbox' :
                            object.checked = !!+value;
                            break;
                        default :
                            object.setValue(value);
                            break;
                    }
                }
            },

            /**
             * @param config
             */
            addItem: function (config) {
                var data,
                    couponsContainer,
                    row;

                data = {
                    index: this.itemsCount++
                };
                couponsContainer = this.findObject('container');
                row = Element.insert(couponsContainer, {
                    bottom: this.rowTemplate({
                        data: data
                    })
                });

                if (config) {
                    this.config = config;
                } else {
                    this.config = {
                        expired_in_days: 4
                    }
                }

                this.configure(data.index);
                this.initRowEvents();
            },

            /**
             * @param index
             */
            configure: function (index) {
                _.each(this.config, function (value, key) {
                    this.setValue(key, index, value)
                }.bind(this));

                this.useShoppingCartRule(null, index);
                this.sendSameCoupon(null, index);
                this.showLess(null, index);
            },

            /**
             * @param event
             * @param index
             */
            useShoppingCartRule: function (event, index) {
                var self = this,
                    couponOwn = this.findObject('coupon_own', index),
                    couponCartRule = this.findObject('coupon_cart_rule', index),
                    couponSame = this.findObject('send_same_coupon', index),
                    couponSelect = this.findObject('simple_action', index),
                    currentCoupon = $('#' + couponOwn.id).parents('[data-am-js="coupon"]'),
                    nextCoupon = currentCoupon.next('[data-am-js="coupon"]'),
                    prevCoupon = currentCoupon.prev('[data-am-js="coupon"]'),
                    couponTextInputs = currentCoupon.find('[data-am-js="value"]'),
                    nextCouponTextInputs = nextCoupon.find('[data-am-js="value"]');

                if (this.findObject('use_shopping_cart_rule', index).checked) {
                    couponCartRule.show();
                    couponSame.disabled = true;
                    currentCoupon.find('[data-am-js="coupon-type"] :nth-child(1)').prop('selected', 'selected');
                    couponSelect.disabled = true;

                    if (!currentCoupon.is(':last-of-type') && !nextCoupon.find('[data-am-js="usecartrule-checkbox"]').prop('checked')) {
                        nextCoupon.find('[data-am-js="send-same"]').prop('disabled', false);
                        if (!nextCoupon.find('[data-am-js="send-same"]').prop('checked')) {
                            self.disableTextInputs(nextCouponTextInputs, false);
                            nextCoupon.find('[data-am-js="coupon-type"]').prop('disabled', false);
                        }
                    }

                    self.disableTextInputs(couponTextInputs, true);
                } else {
                    couponCartRule.hide();

                    if ((!currentCoupon.is(':nth-of-type(1)'))
                        && (prevCoupon.find('[data-am-js="coupon-type"]').val()
                            || prevCoupon.find('[data-am-js="usecartrule-checkbox"]').prop('checked')
                            || prevCoupon.find('[data-am-js="send-same"]').prop('checked'))) {
                        couponSame.disabled = false;
                    }

                    if (!currentCoupon.is(':last-of-type') && !nextCoupon.find('[data-am-js="usecartrule-checkbox"]').prop('checked')) {
                        nextCoupon.find('[data-am-js="send-same"]').prop({'disabled': true, 'checked': false});
                        nextCoupon.find('[data-am-js="send-same-box"]').removeClass('-nounderline');
                        nextCoupon.find('[data-am-js="usecartrule-box"]').show();
                        nextCoupon.find('[data-am-js="coupon-type"]').prop('disabled', false);
                        self.disableTextInputs(nextCouponTextInputs, false);

                    }
                    couponSelect.disabled = false;
                    self.disableTextInputs(couponTextInputs, false);
                }
            },

            /**
             * @param event
             * @param index
             */
            sendSameCoupon: function (event, index) {
                var self = this,
                    couponOwn = this.findObject('coupon_own', index),
                    currentCoupon = $('#' + couponOwn.id).parents('[data-am-js="coupon"]'),
                    nextCoupon = currentCoupon.next('[data-am-js="coupon"]');


                if (this.findObject('send_same_coupon', index).checked) {
                    self.changeCouponFieldsVisibility(currentCoupon, 'hide');

                    if (!currentCoupon.is(':last-of-type') && !nextCoupon.find('[data-am-js="usecartrule-checkbox"]').prop('checked')) {
                        nextCoupon.find('[data-am-js="send-same"]').prop('disabled', false);
                    }
                } else {
                    self.changeCouponFieldsVisibility(currentCoupon, 'show');

                    if (!currentCoupon.is(':last-of-type')) {
                        self.changeCouponFieldsVisibility(nextCoupon, 'show');
                        nextCoupon.find('[data-am-js="send-same"]').prop({'disabled': true, 'checked': false})
                    }
                }
            },

            /**
             * @param event
             * @param index
             */
            showMore: function (event, index) {
                var couponExrta = this.findObject('coupon_extra', index),
                    showMore = this.findObject('show_more', index),
                    showLess = this.findObject('show_less', index);

                couponExrta.show();
                showMore.hide();
                showLess.show();
            },

            /**
             * @param event
             * @param index
             */
            showLess: function (event, index) {
                var couponExrta = this.findObject('coupon_extra', index),
                    showMore = this.findObject('show_more', index),
                    showLess = this.findObject('show_less', index);

                couponExrta.hide();
                showMore.show();
                showLess.hide();
            },

            /**
             * @param event
             * @param index
             */
            deleteItem: function (event, index) {
                confirm({
                    content: 'Are you sure?',
                    actions: {
                        confirm: function () {
                            var schedule = this.findObject('schedule', index);
                            schedule.remove();
                        }.bind(this)
                    }
                })
            },

            /**
             * @return {schedule}
             */
            checkUseSameCouponCheckboxVisibility: function () {
                var self = this;

                $('[data-am-js="coupon-type"]').each(function (index, element) {
                    var selectorIndex = self.getIndexFromId(element.id),
                        currentCoupon = $('#' + element.id).parents('[data-am-js="coupon"]'),
                        nextCoupon = currentCoupon.next('[data-am-js="coupon"]'),
                        useSameCoupon = self.findObject('send_same_coupon', selectorIndex),
                        useNextSameCoupon = nextCoupon.find('[data-am-js="send-same"]');

                    if (!currentCoupon.is(':last-of-type')) {
                        if (!nextCoupon.find('[data-am-js="usecartrule-checkbox"]').prop('checked')) {
                            if (!$(element).val()
                                && !useSameCoupon.checked
                                && !self.findObject('use_shopping_cart_rule', selectorIndex).checked) {
                                useNextSameCoupon.prop('disabled', true);
                                useNextSameCoupon.prop('checked', false);
                                self.changeCouponFieldsVisibility(nextCoupon, 'show');
                            } else {
                                useNextSameCoupon.prop('disabled', false);
                            }
                        }

                    } else {
                        return this;
                    }
                });
            },

            /**
             * @return {schedule}
             */
            initRowEvents: function () {
                var self = this;

                $('.amasty-add-row').on('click', function () {
                    self.checkUseSameCouponCheckboxVisibility();
                });

                $('[data-am-js="coupon-type"]').on('change', function () {
                    self.checkUseSameCouponCheckboxVisibility();
                });

                return this;
            },

            /**
             * @param id
             */
            getIndexFromId: function (id) {
                return id.replace(this.htmlId + '_', '').split('_')[0];
            },

            /**
             * @param coupon
             * @param visibility
             * @returns {schedule}
             */
            changeCouponFieldsVisibility: function (coupon, visibility) {
                var self = this,
                    couponSame = coupon.find('[data-am-js="send-same-box"]'),
                    couponSelect = coupon.find('[data-am-js="coupon-type"]'),
                    useShoppingCartRule = coupon.find('[data-am-js="usecartrule-box"]'),
                    useShoppingCartRuleCheckbox = coupon.find('[data-am-js="usecartrule-checkbox"]'),
                    couponTextInputs = coupon.find('[data-am-js="value"]');

                switch (visibility) {
                    case 'hide':
                        couponSame.addClass('-nounderline');
                        couponSelect.prop('disabled', true);
                        coupon.find('[data-am-js="coupon-type"] :nth-child(1)').prop('selected', 'selected');
                        if (!useShoppingCartRuleCheckbox.prop('checked')) {
                            useShoppingCartRule.hide();
                        }

                        self.disableTextInputs(couponTextInputs, true);
                        break;
                    case 'show':
                        useShoppingCartRule.show();
                        couponSame.removeClass('-nounderline');
                        if (!useShoppingCartRuleCheckbox.prop('checked')) {
                            couponSelect.prop('disabled', false);
                            self.disableTextInputs(couponTextInputs, false);
                        }
                        break;
                    default:
                        useShoppingCartRule.show();
                        break;
                }

                return this;
            },

            /**
             * @param inputs
             * @param state
             */
            disableTextInputs: function (inputs, state) {
                for (var i = 0; i < inputs.length; i++) {
                    inputs[i].disabled = state;
                }
            }
        }

        return schedule
    }
)
