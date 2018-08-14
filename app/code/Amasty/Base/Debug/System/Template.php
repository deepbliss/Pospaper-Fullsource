<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Debug\System;

class Template
{
    public static $varWrapper = '<div class="amasty-base-debug-wrapper"><code>%s</code></div>';

    public static $string = '"<span class="amasty-base-string">%s</span>"';

    public static $var = '<span class="amasty-base-var">%s</span>';

    public static $arrowsOpened =  '<span class="amasty-base-arrow" data-opened="true">&#x25BC;</span>
        <div class="amasty-base-array">';

    public static $arrowsClosed = '<span class="amasty-base-arrow" data-opened="false">&#x25C0;</span>
        <div class="amasty-base-array amasty-base-hidden">';

    public static $arrayHeader = '<span class="amasty-base-info">array:%s</span> [';

    public static $array = '<div class="amasty-base-array-line" style="padding-left:%s0px">
            %s  => %s
        </div>';

    public static $arrayFooter = '</div>]';

    public static $arrayKeyString = '"<span class="amasty-base-array-key">%s</span>"';

    public static $arrayKey = '<span class="amasty-base-array-key">%s</span>';

    public static $arraySimpleVar = '<span class="amasty-base-array-value">%s</span>';

    public static $arraySimpleString = '"<span class="amasty-base-array-string-value">%s</span>"';

    public static $objectHeader = '<span class="amasty-base-info" title="%s">Object: %s</span> {';

    public static $objectMethod = '<div class="amasty-base-object-method-line" style="padding-left:%s0px">
            #%s
        </div>';

    public static $objectMethodHeader = '<span style="margin-left:%s0px">Methods: </span>
        <span class="amasty-base-arrow" data-opened="false">◀</span>
        <div class="amasty-base-array  amasty-base-hidden">';

    public static $objectMethodFooter = '</div>';

    public static $objectFooter = '</div> }';

    public static $debugJsCss = '<script>
            var amastyToggle = function() {
                if (this.dataset.opened == "true") {
                    this.innerHTML = "&#x25C0";
                    this.dataset.opened = "false";
                    this.nextElementSibling.className = "amasty-base-array amasty-base-hidden";
                } else {
                    this.innerHTML = "&#x25BC;";
                    this.dataset.opened = "true";
                    this.nextElementSibling.className = "amasty-base-array";
                }
            };
            document.addEventListener("DOMContentLoaded", function() {
                arrows = document.getElementsByClassName("amasty-base-arrow");
                for (i = 0; i < arrows.length; i++) {
                    arrows[i].addEventListener("click", amastyToggle,false);
                }
            });
        </script>
        <style>
            .amasty-base-debug-wrapper {
                background-color: #263238;
                color: #ff9416;
                font-size: 13px;
                padding: 10px;
                border-radius: 3px;
                z-index: 1000000;
                margin: 20px 0;
            }
            .amasty-base-debug-wrapper code {
                background: transparent !important;
                color: inherit !important;
                padding: 0;
                font-size: inherit;
                white-space: inherit;
            }
            .amasty-base-info {
                color: #82AAFF;
            }
            .amasty-base-var, .amasty-base-array-key {
                color: #fff;
            }
            .amasty-base-array-value {
                color: #C792EA;
                font-weight: bold;
            }
            .amasty-base-arrow {
                cursor: pointer;
                color: #82aaff;
            }
            .amasty-base-hidden {
                display:none;
            }
            .amasty-base-string, .amasty-base-array-string-value {
                font-weight: bold;
                color: #c3e88d;
            }
            .amasty-base-object-method-line {
                color: #fff;
            }
        </style>';
}
