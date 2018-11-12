<?php
namespace WeltPixel\GoogleTagManager\Block\Adminhtml\Widget;

Class InfoMessage extends \Magento\Backend\Block\Template
{


    /**
     * Prepare chooser element HTML
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element Form Element
     * @return \Magento\Framework\Data\Form\Element\AbstractElement
     */
    public function prepareElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $infoMessage = '<div class="wp-widget-info-message col-xs-12">
<div class="col-xs-12 with-border">If adding promo tracking via widget is not possible you can simply add the promotion parameters to the "a" tag</div>
<div class="col-xs-12">&lt;a hef=&quot;#&quot; data-track-promo-id=&quot;PROMOID&quot; <br/>
&nbsp;&nbsp;&nbsp;&nbsp;data-track-promo-name=&quot;PROMONAME&quot; <br/>
&nbsp;&nbsp;&nbsp;&nbsp;data-track-promo-creative=&quot;PROMOCREATIVE&quot; <br/>
&nbsp;&nbsp;&nbsp;&nbsp;data-track-promo-position=&quot;PROMOPOSITION&quot;<br/>
Content&lt;/a&gt;</div>';
        $customStyle = "<style>.wp-widget-info-message {padding: 10px; background-color: #fafafa; border: 3px solid #eb5202} .wp-widget-info-message div {padding: 10px;} .wp-widget-info-message div.with-border{border-bottom: 1px solid}</style>";
        $element->setData('after_element_html',  $customStyle . $infoMessage);
        return $element;
    }
}