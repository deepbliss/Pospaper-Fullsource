define(['jquery'], function ($) {

  return function (widget) {
    var globalOptions = {
      productId: null,
      priceConfig: null,
      prices: {},
      priceTemplate: '<span class="price"><%- data.formatted %><span class="case-font">/Case</span></span>'
    };

    $.widget('mage.priceBox', widget, {
      options: globalOptions
    });
    return $.mage.priceBox;
  }
});