/**
 * Pluginsplanet
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the pluginsplanet.com license that is
 * available through the world-wide-web at this URL:
 * https://www.pluginsplanet.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Pluginsplanet
 * @package     Pluginsplanet_Core
 * @copyright   Copyright (c) 2016 Pluginsplanet (http://www.pluginsplanet.com/)
 * @license     https://www.pluginsplanet.com/LICENSE.txt
 */

var config = {
    paths: {
        'Pluginsplanet/core/jquery/popup': 'Pluginsplanet_Core/js/jquery.magnific-popup.min',
        'Pluginsplanet/core/owl.carousel': 'Pluginsplanet_Core/js/owl.carousel.min',
        'Pluginsplanet/core/bootstrap': 'Pluginsplanet_Core/js/bootstrap.min',
        mpIonRangeSlider: 'Pluginsplanet_Core/js/ion.rangeSlider.min',
        touchPunch: 'Pluginsplanet_Core/js/jquery.ui.touch-punch.min'
    },
    shim: {
        "Pluginsplanet/core/jquery/popup": ["jquery"],
        "Pluginsplanet/core/owl.carousel": ["jquery"],
        "Pluginsplanet/core/bootstrap": ["jquery"],
        mpIonRangeSlider: ["jquery"],
        touchPunch: ['jquery', 'jquery/ui']
    }
};
