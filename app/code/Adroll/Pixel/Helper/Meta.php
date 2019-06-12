<?php

namespace Adroll\Pixel\Helper;

use Adroll\Pixel\Helper\Config as ConfigHelper;
use Adroll\Pixel\Helper\Feed as FeedHelper;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ProductMetadata;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Store\Model\StoreManagerInterface;

class Meta extends AbstractHelper
{
    public function __construct(
        Context $context,
        ConfigHelper $configHelper,
        FeedHelper $feedHelper,
        ProductMetadata $productMetadata,
        ModuleListInterface $moduleListInterface,
        StoreManagerInterface $storeManager,
        CurrencyFactory $currencyFactory)
    {
        $this->_configHelper = $configHelper;
        $this->_feedHelper = $feedHelper;
        $this->_productMetadata = $productMetadata;
        $this->_moduleListInterface = $moduleListInterface;
        $this->_storeManager = $storeManager;
        $this->_currencyFactory = $currencyFactory;
        $this->_scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
    }

    private function getAvailableCurrencyCodes($store)
    {
        $codes = [];
        foreach ($store->getAvailableCurrencyCodes() as $code){
            try {
                $destCurrency = $this->_currencyFactory->create()->load($code);
                $store->getBaseCurrency()->convert(1, $destCurrency);
                $codes[] = $code;
            } catch (\Exception $e) {
                # Conversion rate for this currency has not been set. Cannot use it for product feed.
            }
        }
        return $codes;
    }

    public function serializeStore($store)
    {
        return array(
            'id' => $store->getId(),
            'name' => $store->getName(),
            'code' => $store->getCode(),
            'currencies' => $this->getAvailableCurrencyCodes($store),
            'locale' => $this->_scopeConfig->getValue('general/locale/code', 'store', $store),
            'product_count' => $this->_feedHelper->getFeedableProducts($store)->getSize()
        );
    }

    public function generateStoreGroupMeta($storeGroupId)
    {
        $productMetadata = $this->_productMetadata;
        $storeGroupMeta = array(
            'magento_version' => $productMetadata->getVersion(),
            'extension_version' => $this->_moduleListInterface->getOne('Adroll_Pixel')['setup_version'],
            'advertisable_eid' => $this->_configHelper->getAdvertisableEid($storeGroupId),
            'pixel_eid' => $this->_configHelper->getPixelEid($storeGroupId),
            'stores' => array()
        );

        $storeGroup = $this->_storeManager->getGroup($storeGroupId);
        $stores = $storeGroup->getStores();
        foreach ($stores as $store) {
            $storeGroupMeta['stores'][] = $this->serializeStore($store);
        }
        return $storeGroupMeta;
    }
}
