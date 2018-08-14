<?php
namespace Magento\Sales\Api\Data;

/**
 * Extension class for @see \Magento\Sales\Api\Data\OrderAddressInterface
 */
class OrderAddressExtension extends \Magento\Framework\Api\AbstractSimpleObject implements OrderAddressExtensionInterface
{
    /**
     * @return \Magento\Framework\Api\AttributeInterface[]|null
     */
    public function getCheckoutFields()
    {
        return $this->_get('checkout_fields');
    }

    /**
     * @param \Magento\Framework\Api\AttributeInterface[] $checkoutFields
     * @return $this
     */
    public function setCheckoutFields($checkoutFields)
    {
        $this->setData('checkout_fields', $checkoutFields);
        return $this;
    }
}
