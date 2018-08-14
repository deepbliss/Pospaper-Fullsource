<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Adminhtml\System\Config;

class CssInclude extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var \Amasty\Base\Helper\CssChecker
     */
    private $cssChecker;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Amasty\Base\Helper\CssChecker $cssChecker,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->cssChecker = $cssChecker;
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $corruptedWebsites = $this->cssChecker->getCorruptedWebsites();
        if ($corruptedWebsites) {
            return parent::render($element);
        }

        return '';
    }
}
