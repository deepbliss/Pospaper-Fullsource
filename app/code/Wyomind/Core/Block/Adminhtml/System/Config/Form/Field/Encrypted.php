<?php

/**
 * Copyright © 2017 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\Core\Block\Adminhtml\System\Config\Form\Field;

class Encrypted extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * @var \Wyomind\Core\Helper\Data
     */
    protected $_coreHelper = null;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $_encryptor = null;

    public function __construct(
    \Magento\Backend\Block\Template\Context $context,
            \Wyomind\Core\Helper\Data $coreHelper,
            \Magento\Framework\Encryption\EncryptorInterface $encryptor,
            array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_coreHelper = $coreHelper;
        $this->_encryptor = $encryptor;
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        if (version_compare("2.2.1", $this->_coreHelper->getMagentoVersion()) <= 0) {
            $element->setValue($this->_encryptor->decrypt($element->getValue()));
        }
        return parent::_getElementHtml($element);
    }

}
