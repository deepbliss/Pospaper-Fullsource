<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Plugin;

class ProductAttributes
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    public function aroundGetProductAttributes(\Magento\Quote\Model\Quote\Config $subject, \Closure $closure)
    {
        $attributesTransfer = $closure();

        $attributes = $this->objectManager->create('Amasty\Acart\Model\ResourceModel\Rule')->getAttributes();

        foreach ($attributes as $code) {
            $attributesTransfer[] = $code;
        }

        return $attributesTransfer;
    }
}
