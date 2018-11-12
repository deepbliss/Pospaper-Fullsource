<?php

namespace WeltPixel\GoogleTagManager\Block\Adminhtml\System\Config;

/**
 * Class ProVersion
 * @package WeltPixel\GoogleTagManager\Block\Adminhtml\System\Config
 */
class ProVersion extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
       $proOptions = [
           'GTM API - Update tag, trigger, variables',
           'Track product review count',
           'Track product review score',
           'Track stock status',
           'Track Sale status',
           'Track transactions by product variants (configurable product)',
           'Track transactions by product custom options',
           'Track custom dimension Page Type',
           'Track custom dimension Page Name'
       ];

       $messageElements = array_map(
           function($el) {
                return "<p><b>$el</b></p>";
           }, $proOptions
       );

       $message = implode("", $messageElements);

        $html = '<div class="message" style="text-align: left; width: 100%;">' . $message  . '</div>';
        $html .= '<div style="padding: 1em;">Get PRO version of <a href="https://www.weltpixel.com/google-analytics-enhanced-ecommerce-tag-manager-magento-2.html" target="_blank" >Google Analytics Enhanced Ecommerce UA GTM Tracking</a>.</div>';
        $js = '<script type="text/javascript">
            require(["jquery"], function ($) {
                $(document).ready(function () {
                    var $el = $("#row_' . $element->getId() . '");
                    $el.find("td.label").remove();                 
                    $el.find("td.value").css("width", "90%");                 
                });
            });
            </script>';


        return $html . $js;
    }
}