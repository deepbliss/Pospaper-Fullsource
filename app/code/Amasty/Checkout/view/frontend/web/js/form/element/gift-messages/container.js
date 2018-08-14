define(
    [
        'Magento_Ui/js/lib/view/utils/async',
        'uiComponent',
        'uiRegistry',
        'Magento_Ui/js/modal/modal',
        'mage/translate',
        'ko',
        'mage/storage',
        'Amasty_Checkout/js/model/resource-url-manager',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Ui/js/model/messageList'
    ],
    function (
        $,
        Component,
        registry,
        modal,
        $t,
        ko,
        storage,
        resourceUrlManager,
        quote,
        errorProcessor,
        fullScreenLoader,
        messagesList
    ) {
        'use strict';

        var popUp = null;

        return Component.extend({
            defaults: {
                template: 'Amasty_Checkout/form/gift_messages/container'
            },

            checkbox: null,
            isFormPopUpVisible: ko.observable(false),

            initialize: function () {
                this._super();
                var self = this;

                this.isFormPopUpVisible.subscribe(function (value) {
                    if (value) {
                        self.getPopUp().openModal();
                    }
                });
            },

            initConfig: function (config) {
                var self = this;
                this._super();

                registry.get(this.name + '.checkbox', function (checkbox) {
                    self.checkbox = checkbox;

                    checkbox.checked.subscribe(self.toggleState.bind(self));
                    checkbox.on('edit_link_click', self.showPopup.bind(self));
                });

                return this;
            },

            toggleState: function (checked) {
                if (checked) {
                    this.showPopup();
                }
            },

            showPopup: function() {
                this.isFormPopUpVisible(true);
            },

            submit: function () {
                var self = this,
                    data = [];

                ['item_messages', 'quote_message'].forEach(function (containerName) {
                    var container = self.getChild(containerName);

                    if (typeof(container) === 'undefined')
                        return;

                    container.elems().forEach(function (messageComponent) {
                        data.push(messageComponent.collectData());
                    })
                });

                var serviceUrl = resourceUrlManager.getUrlForGiftMessage(quote);
                fullScreenLoader.startLoader();

                storage.post(
                    serviceUrl,
                    JSON.stringify({
                        gift_message: data
                    })
                ).done(
                    function (response) {
                        messagesList.addSuccessMessage({message: $t('Gift messages has been successfully updated')});
                    }
                ).fail(
                    function (response) {
                        errorProcessor.process(response);
                    }
                ).always(
                    function(response) {
                        self.getPopUp().closeModal();
                        fullScreenLoader.stopLoader(false);
                    }
                );
            },

            getPopUp: function () {
                var self = this,
                    buttons;

                if (!popUp) {
                    buttons = this.popUpForm.options.buttons;
                    this.popUpForm.options.buttons = [
                        {
                            class: buttons.save.class ? buttons.save.class : 'action primary action-save-address',
                            text: buttons.save.text ? buttons.save.text : $t('Update'),
                            click: self.submit.bind(self)
                        },
                        {
                            class: buttons.cancel.class ? buttons.cancel.class : 'action secondary action-hide-popup',
                            text: buttons.cancel.text ? buttons.cancel.text : $t('Close'),
                            click: function () {
                                this.closeModal();
                            }
                        }
                    ];
                    this.popUpForm.options.closed = function () {
                        self.isFormPopUpVisible(false);
                    };
                    popUp = modal(this.popUpForm.options, $(this.popUpForm.element));
                }

                return popUp;
            }
        });
    }
);
