define([
    'jquery',
    'jquery/ui',
    'mage/menu'
],
function($){
    $.widget('mega.menu', $.mage.menu, {
        _init: function () {
            //this._super();

            if (this.options.expanded === true) {
                this.isExpanded();
            }

            if (this.options.responsive === true) {
                mediaCheck({
                    media: '(max-width: 1023px)',
                    entry: $.proxy(function () {
                        this._toggleMobileMode();
                    }, this),
                    exit: $.proxy(function () {
                        this._lazyLoad();
                        this._toggleDesktopMode();
                    }, this)
                });
            }

            this._assignControls()._listen();
            this._setActiveMenu();
        },
        _closeOnDocumentClick: function( event ) {
            return false;
        },
        select: function (event) {
            var ui;

            this.active = this.active || $(event.target).closest('.ui-menu-item');

            if (this.active.is('.all-category')) {
                this.active = $(event.target).closest('.ui-menu-item');
            }
            ui = {
                item: this.active
            };

            if (!this.active.has('.ui-menu').length) {
                //this.collapseAll(event, true);
            }
            this._trigger('select', event, ui);
        },
        _lazyLoad: function() {
            $('body').trigger('loadMenu');
        },
        _toggleMobileMode: function () {
            var subMenus;

            $(this.element).off('mouseenter mouseleave');
            this._on({
                /**
                 * @param {jQuery.Event} event
                 */
                'click .ui-menu-item:has(a)': function (event) {
                    var target;

                    event.preventDefault();
                    target = $(event.target).closest('.ui-menu-item');

                    var catalogLink = $(event.target).closest('div');

                    if(typeof catalogLink.data('level') !== 'undefined') {
                        console.log('level-two');
                        event.stopPropagation();
                        return false;
                    }

                    if(catalogLink.hasClass('level2')) {
                        window.location.href = catalogLink.find('> a').attr('href');
                        return false;
                    }

                    if (!target.hasClass('level-top') || !target.has('.ui-menu').length || !target.has('.level-two-container'.length)) {
                        window.location.href = target.find('> a').attr('href');
                    }
                },

                /**
                 * @param {jQuery.Event} event
                 */
                'click .ui-menu-item:has(.ui-state-active)': function (event) {
                    this.collapseAll(event, true);
                }
            });

            subMenus = this.element.find('.level-top');
            $.each(subMenus, $.proxy(function (index, item) {
                var category = $(item).find('> a span').not('.ui-menu-icon').text(),
                    categoryUrl = $(item).find('> a').attr('href'),
                    menu = $(item).find('> .ui-menu');

                this.categoryLink = $('<a>')
                    .attr('href', categoryUrl)
                    .text($.mage.__('All ') + category);

                this.categoryParent = $('<li>')
                    .addClass('ui-menu-item all-category')
                    .html(this.categoryLink);

                if (menu.find('.all-category').length === 0) {
                    menu.prepend(this.categoryParent);
                }

            }, this));
        },
    });
    return $.mega.menu;
});