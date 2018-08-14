<?php
namespace Magento\Tax\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Tax\Api\Data\OrderTaxDetailsAppliedTaxInterface
 */
interface OrderTaxDetailsAppliedTaxExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \Magento\Tax\Api\Data\AppliedTaxRateInterface[]|null
     */
    public function getRates();

    /**
     * @param \Magento\Tax\Api\Data\AppliedTaxRateInterface[] $rates
     * @return $this
     */
    public function setRates($rates);
}
