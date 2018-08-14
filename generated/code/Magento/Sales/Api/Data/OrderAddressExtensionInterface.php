<?php
namespace Magento\Sales\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Sales\Api\Data\OrderAddressInterface
 */
interface OrderAddressExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \Magento\Framework\Api\AttributeInterface[]|null
     */
    public function getCheckoutFields();

    /**
     * @param \Magento\Framework\Api\AttributeInterface[] $checkoutFields
     * @return $this
     */
    public function setCheckoutFields($checkoutFields);
}
