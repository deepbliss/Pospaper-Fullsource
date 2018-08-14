<?php
namespace Magento\Tax\Api\Data;

/**
 * Extension class for @see \Magento\Tax\Api\Data\OrderTaxDetailsAppliedTaxInterface
 */
class OrderTaxDetailsAppliedTaxExtension extends \Magento\Framework\Api\AbstractSimpleObject implements OrderTaxDetailsAppliedTaxExtensionInterface
{
    /**
     * @return \Magento\Tax\Api\Data\AppliedTaxRateInterface[]|null
     */
    public function getRates()
    {
        return $this->_get('rates');
    }

    /**
     * @param \Magento\Tax\Api\Data\AppliedTaxRateInterface[] $rates
     * @return $this
     */
    public function setRates($rates)
    {
        $this->setData('rates', $rates);
        return $this;
    }
}
