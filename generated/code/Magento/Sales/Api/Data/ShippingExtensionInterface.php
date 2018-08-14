<?php
namespace Magento\Sales\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Sales\Api\Data\ShippingInterface
 */
interface ShippingExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return string|null
     */
    public function getExtOrderId();

    /**
     * @param string $extOrderId
     * @return $this
     */
    public function setExtOrderId($extOrderId);

    /**
     * @return \Temando\Shipping\Api\Data\Order\ShippingExperienceInterface|null
     */
    public function getShippingExperience();

    /**
     * @param \Temando\Shipping\Api\Data\Order\ShippingExperienceInterface $shippingExperience
     * @return $this
     */
    public function setShippingExperience(\Temando\Shipping\Api\Data\Order\ShippingExperienceInterface $shippingExperience);
}
