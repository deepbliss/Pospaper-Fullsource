<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Smtp extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        if ($this->getModuleManager() && $this->getModuleManager()->isEnabled('Amasty_Smtp')) {
            $element->setValue(__('Installed'));
            $element->setHtmlId('amasty_is_instaled');
            $url = $this->getUrl('adminhtml/system_config/edit/section/amsmtp');
            $element->setComment(__("Specify SMTP settings properly. See more details "
                . "<a target='_blank' href='%1'>here</a>.", $url));
        } else {
            $element->setValue(__('Not Installed'));
            $element->setHtmlId('amasty_not_instaled');
        }

        return parent::render($element);
    }
}
