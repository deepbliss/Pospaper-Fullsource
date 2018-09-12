<?php
namespace Magento\Shipping\Model\Shipping;

/**
 * Interceptor class for @see \Magento\Shipping\Model\Shipping
 */
class Interceptor extends \Magento\Shipping\Model\Shipping implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Shipping\Model\Config $shippingConfig, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Shipping\Model\CarrierFactory $carrierFactory, \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory, \Magento\Shipping\Model\Shipment\RequestFactory $shipmentRequestFactory, \Magento\Directory\Model\RegionFactory $regionFactory, \Magento\Framework\Math\Division $mathDivision, \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry, \Magento\Quote\Model\Quote\Address\RateRequestFactory $rateRequestFactory = null)
    {
        $this->___init();
        parent::__construct($scopeConfig, $shippingConfig, $storeManager, $carrierFactory, $rateResultFactory, $shipmentRequestFactory, $regionFactory, $mathDivision, $stockRegistry, $rateRequestFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResult');
        if (!$pluginInfo) {
            return parent::getResult();
        } else {
            return $this->___callPlugins('getResult', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOrigData($data)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setOrigData');
        if (!$pluginInfo) {
            return parent::setOrigData($data);
        } else {
            return $this->___callPlugins('setOrigData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resetResult()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resetResult');
        if (!$pluginInfo) {
            return parent::resetResult();
        } else {
            return $this->___callPlugins('resetResult', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfig');
        if (!$pluginInfo) {
            return parent::getConfig();
        } else {
            return $this->___callPlugins('getConfig', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collectRates(\Magento\Quote\Model\Quote\Address\RateRequest $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'collectRates');
        if (!$pluginInfo) {
            return parent::collectRates($request);
        } else {
            return $this->___callPlugins('collectRates', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collectCarrierRates($carrierCode, $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'collectCarrierRates');
        if (!$pluginInfo) {
            return parent::collectCarrierRates($carrierCode, $request);
        } else {
            return $this->___callPlugins('collectCarrierRates', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function composePackagesForCarrier($carrier, $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'composePackagesForCarrier');
        if (!$pluginInfo) {
            return parent::composePackagesForCarrier($carrier, $request);
        } else {
            return $this->___callPlugins('composePackagesForCarrier', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collectRatesByAddress(\Magento\Framework\DataObject $address, $limitCarrier = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'collectRatesByAddress');
        if (!$pluginInfo) {
            return parent::collectRatesByAddress($address, $limitCarrier);
        } else {
            return $this->___callPlugins('collectRatesByAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCarrierAvailabilityConfigField($code = 'active')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCarrierAvailabilityConfigField');
        if (!$pluginInfo) {
            return parent::setCarrierAvailabilityConfigField($code);
        } else {
            return $this->___callPlugins('setCarrierAvailabilityConfigField', func_get_args(), $pluginInfo);
        }
    }
}
