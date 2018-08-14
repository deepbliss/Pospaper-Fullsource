define(
    [
        'jquery',
    ],
    function ($) {
        'use strict';

        return function (targetModule) {
            targetModule.registerStep = function (code, alias, title, isVisible, navigate, sortOrder) {
                var hash;

                if ($.inArray(code, this.validCodes) !== -1) {
                    throw new DOMException('Step code [' + code + '] already registered in step navigator');
                }

                if (alias != null) {
                    if ($.inArray(alias, this.validCodes) !== -1) {
                        throw new DOMException('Step code [' + alias + '] already registered in step navigator');
                    }
                    this.validCodes.push(alias);
                }

                this.validCodes.push(code);
                targetModule.steps.push({
                    code: code,
                    alias: alias != null ? alias : code,
                    title: title,
                    isVisible: isVisible,
                    navigate: navigate,
                    sortOrder: sortOrder
                });
                this.stepCodes.push(code);
                hash = window.location.hash.replace('#', '');

                if (hash != '' && hash != code) { //eslint-disable-line eqeqeq
                    //Force hiding of not active step
                    isVisible(false);
                }
            };

            return targetModule;
        };
    }
);

