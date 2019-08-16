define([
    'jquery'
], function ($) {
    'use strict';

    return function (widget) {
        $.widget('mage.globalNavigation', widget, {
            _open: function (e) {
                var selectors = this.options.selectors,
                    menuItemSelector = selectors.topLevelItem,
                    menuItem = $(e.target).closest(menuItemSelector),
                    subMenu = $(selectors.subMenu, menuItem),
                    closeBtn = subMenu.find(selectors.closeSubmenuBtn);

                closeBtn.unbind('click');
                this._super(e);

                /* fix for menu with multiple columns */
                menuItem.parents('.submenu')
                    .find(menuItemSelector).not(menuItem)
                    .removeClass('_show')
                    .removeClass('_active');

                /* fix for hiding our menu after selecting native */
                menuItem.parent()
                    .find(menuItemSelector + '._show').not(menuItem)
                    .removeClass('_show')
                    .removeClass('_active');
            },

            _close: function (e) {
                var selectors = this.options.selectors,
                    currentCrossElement = $(e.target),
                    topLevelItemSelector = selectors.topLevelItem,
                    menuItem = this.menu.find(topLevelItemSelector + '._show');

                if (currentCrossElement && currentCrossElement.parents('[data-ui-id*="menu-amasty"]').length > 0) {
                    this.options.selectors.topLevelItem = '.submenu ' + topLevelItemSelector;
                }

                this._super(e);
                this.options.selectors.topLevelItem = topLevelItemSelector;

                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });

        return $.mage.globalNavigation;
    }
});
