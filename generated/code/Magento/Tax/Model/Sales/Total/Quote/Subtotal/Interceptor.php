<?php
namespace Magento\Tax\Model\Sales\Total\Quote\Subtotal;

/**
 * Interceptor class for @see \Magento\Tax\Model\Sales\Total\Quote\Subtotal
 */
class Interceptor extends \Magento\Tax\Model\Sales\Total\Quote\Subtotal implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Tax\Model\Config $taxConfig, \Magento\Tax\Api\TaxCalculationInterface $taxCalculationService, \Magento\Tax\Api\Data\QuoteDetailsInterfaceFactory $quoteDetailsDataObjectFactory, \Magento\Tax\Api\Data\QuoteDetailsItemInterfaceFactory $quoteDetailsItemDataObjectFactory, \Magento\Tax\Api\Data\TaxClassKeyInterfaceFactory $taxClassKeyDataObjectFactory, \Magento\Customer\Api\Data\AddressInterfaceFactory $customerAddressFactory, \Magento\Customer\Api\Data\RegionInterfaceFactory $customerAddressRegionFactory)
    {
        $this->___init();
        parent::__construct($taxConfig, $taxCalculationService, $quoteDetailsDataObjectFactory, $quoteDetailsItemDataObjectFactory, $taxClassKeyDataObjectFactory, $customerAddressFactory, $customerAddressRegionFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function collect(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'collect');
        if (!$pluginInfo) {
            return parent::collect($quote, $shippingAssignment, $total);
        } else {
            return $this->___callPlugins('collect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'fetch');
        if (!$pluginInfo) {
            return parent::fetch($quote, $total);
        } else {
            return $this->___callPlugins('fetch', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function mapAddress(\Magento\Quote\Model\Quote\Address $address)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'mapAddress');
        if (!$pluginInfo) {
            return parent::mapAddress($address);
        } else {
            return $this->___callPlugins('mapAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function mapItem(\Magento\Tax\Api\Data\QuoteDetailsItemInterfaceFactory $itemDataObjectFactory, \Magento\Quote\Model\Quote\Item\AbstractItem $item, $priceIncludesTax, $useBaseCurrency, $parentCode = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'mapItem');
        if (!$pluginInfo) {
            return parent::mapItem($itemDataObjectFactory, $item, $priceIncludesTax, $useBaseCurrency, $parentCode);
        } else {
            return $this->___callPlugins('mapItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function mapItemExtraTaxables(\Magento\Tax\Api\Data\QuoteDetailsItemInterfaceFactory $itemDataObjectFactory, \Magento\Quote\Model\Quote\Item\AbstractItem $item, $priceIncludesTax, $useBaseCurrency)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'mapItemExtraTaxables');
        if (!$pluginInfo) {
            return parent::mapItemExtraTaxables($itemDataObjectFactory, $item, $priceIncludesTax, $useBaseCurrency);
        } else {
            return $this->___callPlugins('mapItemExtraTaxables', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function mapItems(\Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment, $priceIncludesTax, $useBaseCurrency)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'mapItems');
        if (!$pluginInfo) {
            return parent::mapItems($shippingAssignment, $priceIncludesTax, $useBaseCurrency);
        } else {
            return $this->___callPlugins('mapItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function populateAddressData(\Magento\Tax\Api\Data\QuoteDetailsInterface $quoteDetails, \Magento\Quote\Model\Quote\Address $address)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'populateAddressData');
        if (!$pluginInfo) {
            return parent::populateAddressData($quoteDetails, $address);
        } else {
            return $this->___callPlugins('populateAddressData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingDataObject(\Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment, \Magento\Quote\Model\Quote\Address\Total $total, $useBaseCurrency)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingDataObject');
        if (!$pluginInfo) {
            return parent::getShippingDataObject($shippingAssignment, $total, $useBaseCurrency);
        } else {
            return $this->___callPlugins('getShippingDataObject', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateItemTaxInfo($quoteItem, $itemTaxDetails, $baseItemTaxDetails, $store)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'updateItemTaxInfo');
        if (!$pluginInfo) {
            return parent::updateItemTaxInfo($quoteItem, $itemTaxDetails, $baseItemTaxDetails, $store);
        } else {
            return $this->___callPlugins('updateItemTaxInfo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertAppliedTaxes($appliedTaxes, $baseAppliedTaxes, $extraInfo = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertAppliedTaxes');
        if (!$pluginInfo) {
            return parent::convertAppliedTaxes($appliedTaxes, $baseAppliedTaxes, $extraInfo);
        } else {
            return $this->___callPlugins('convertAppliedTaxes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCode($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCode');
        if (!$pluginInfo) {
            return parent::setCode($code);
        } else {
            return $this->___callPlugins('setCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCode');
        if (!$pluginInfo) {
            return parent::getCode();
        } else {
            return $this->___callPlugins('getCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLabel');
        if (!$pluginInfo) {
            return parent::getLabel();
        } else {
            return $this->___callPlugins('getLabel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function _setTotal(\Magento\Quote\Model\Quote\Address\Total $total)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, '_setTotal');
        if (!$pluginInfo) {
            return parent::_setTotal($total);
        } else {
            return $this->___callPlugins('_setTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemRowTotal(\Magento\Quote\Model\Quote\Item\AbstractItem $item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemRowTotal');
        if (!$pluginInfo) {
            return parent::getItemRowTotal($item);
        } else {
            return $this->___callPlugins('getItemRowTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemBaseRowTotal(\Magento\Quote\Model\Quote\Item\AbstractItem $item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemBaseRowTotal');
        if (!$pluginInfo) {
            return parent::getItemBaseRowTotal($item);
        } else {
            return $this->___callPlugins('getItemBaseRowTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIsItemRowTotalCompoundable(\Magento\Quote\Model\Quote\Item\AbstractItem $item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIsItemRowTotalCompoundable');
        if (!$pluginInfo) {
            return parent::getIsItemRowTotalCompoundable($item);
        } else {
            return $this->___callPlugins('getIsItemRowTotalCompoundable', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function processConfigArray($config, $store)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'processConfigArray');
        if (!$pluginInfo) {
            return parent::processConfigArray($config, $store);
        } else {
            return $this->___callPlugins('processConfigArray', func_get_args(), $pluginInfo);
        }
    }
}
