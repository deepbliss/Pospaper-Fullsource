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
    public function collectRates(\Magento\Quote\Model\Quote\Address\RateRequest $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'collectRates');
        if (!$pluginInfo) {
            return parent::collectRates($request);
        } else {
            return $this->___callPlugins('collectRates', func_get_args(), $pluginInfo);
        }
    }
}
