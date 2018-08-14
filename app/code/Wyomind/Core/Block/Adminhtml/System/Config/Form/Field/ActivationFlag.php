<?php

/**
 * Copyright © 2017 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\Core\Block\Adminhtml\System\Config\Form\Field;

/**
 * @deprecated but need to keep it for older versions of the extensions using it
 */
class ActivationFlag extends \Magento\Config\Block\System\Config\Form\Field
{

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {

        $html = '';
        if ($element->getBeforeElementHtml() && $element->getBeforeElementHtml() != '') {
            $html .= '<label class="addbefore" for="' .
                    $element->getHtmlId() .
                    '">' .
                    $element->getBeforeElementHtml() .
                    '</label>';
        }
        $html .= '<input id="' .
                $element->getHtmlId() .
                '" name="' .
                $element->getName() .
                '" ' .
                ' value="0" type="hidden"/>';
        if ($element->getAfterElementJs() && $element->getAfterElementJs() != '') {
            $html .= $element->getAfterElementJs();
        }
        if ($element->getAfterElementHtml() && $element->getAfterElementHtml() != '') {
            $html .= '<label class="addafter" for="' .
                    $element->getHtmlId() .
                    '">' .
                    $element->getAfterElementHtml() .
                    '</label>';
        }
        return $html;
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $isCheckboxRequired = $this->_isInheritCheckboxRequired($element);

        // Disable element if value is inherited from other scope. Flag has to be set before the value is rendered.
        if ($element->getInherit() == 1 && $isCheckboxRequired) {
            $element->setDisabled(true);
        }

        $html = '<td class="label" style="display:none;"><label for="' .
                $element->getHtmlId() .
                '">' .
                $element->getLabel() .
                '</label></td>';
        $html .= $this->_renderValue($element);

        if ($isCheckboxRequired) {
            $html .= $this->_renderInheritCheckbox($element);
        }

        $html .= $this->_renderScopeLabel($element);
        $html .= $this->_renderHint($element);

        return $this->_decorateRowHtml($element, $html);
    }

    protected function _renderValue(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        if ($element->getTooltip()) {
            $html = '<td class="value with-tooltip" style="display:none;">';
            $html .= $this->_getElementHtml($element);
            $html .= '<div class="tooltip"><span class="help"><span></span></span>';
            $html .= '<div class="tooltip-content">' . $element->getTooltip() . '</div></div>';
        } else {
            $html = '<td class="value" style="display:none;">';
            $html .= $this->_getElementHtml($element);
        }
        if ($element->getComment()) {
            $html .= '<p class="note"><span>' . $element->getComment() . '</span></p>';
        }
        $html .= '</td>';
        return $html;
    }

    protected function _renderScopeLabel(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = '<td class="scope-label" style="display:none;">';
        $html .= $element->getTooltip();
        $html .= '</td>';
        return $html;
    }
}
