<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Adminhtml\Rule;

use Amasty\Acart\Controller\RegistryConstants;

class Test extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->registry = $registry;

        return parent::__construct($context, $data);
    }

    /**
     * @return \Amasty\Acart\Model\Rule
     */
    public function getRule()
    {
        return $this->registry->registry(RegistryConstants::CURRENT_AMASTY_ACART_RULE);
    }
}
