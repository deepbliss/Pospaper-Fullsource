<?php
namespace Magento\Quote\Model\Quote\Address;

/**
 * Interceptor class for @see \Magento\Quote\Model\Quote\Address
 */
class Interceptor extends \Magento\Quote\Model\Quote\Address implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Directory\Helper\Data $directoryData, \Magento\Eav\Model\Config $eavConfig, \Magento\Customer\Model\Address\Config $addressConfig, \Magento\Directory\Model\RegionFactory $regionFactory, \Magento\Directory\Model\CountryFactory $countryFactory, \Magento\Customer\Api\AddressMetadataInterface $metadataService, \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory, \Magento\Customer\Api\Data\RegionInterfaceFactory $regionDataFactory, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Quote\Model\Quote\Address\ItemFactory $addressItemFactory, \Magento\Quote\Model\ResourceModel\Quote\Address\Item\CollectionFactory $itemCollectionFactory, \Magento\Quote\Model\Quote\Address\RateFactory $addressRateFactory, \Magento\Quote\Model\Quote\Address\RateCollectorInterfaceFactory $rateCollector, \Magento\Quote\Model\ResourceModel\Quote\Address\Rate\CollectionFactory $rateCollectionFactory, \Magento\Quote\Model\Quote\Address\RateRequestFactory $rateRequestFactory, \Magento\Quote\Model\Quote\Address\Total\CollectorFactory $totalCollectorFactory, \Magento\Quote\Model\Quote\Address\TotalFactory $addressTotalFactory, \Magento\Framework\DataObject\Copy $objectCopyService, \Magento\Shipping\Model\CarrierFactoryInterface $carrierFactory, \Magento\Quote\Model\Quote\Address\Validator $validator, \Magento\Customer\Model\Address\Mapper $addressMapper, \Magento\Quote\Model\Quote\Address\CustomAttributeListInterface $attributeList, \Magento\Quote\Model\Quote\TotalsCollector $totalsCollector, \Magento\Quote\Model\Quote\TotalsReader $totalsReader, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array(), \Magento\Framework\Serialize\Serializer\Json $serializer = null, \Magento\Store\Model\StoreManagerInterface $storeManager = null)
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $directoryData, $eavConfig, $addressConfig, $regionFactory, $countryFactory, $metadataService, $addressDataFactory, $regionDataFactory, $dataObjectHelper, $scopeConfig, $addressItemFactory, $itemCollectionFactory, $addressRateFactory, $rateCollector, $rateCollectionFactory, $rateRequestFactory, $totalCollectorFactory, $addressTotalFactory, $objectCopyService, $carrierFactory, $validator, $addressMapper, $attributeList, $totalsCollector, $totalsReader, $resource, $resourceCollection, $data, $serializer, $storeManager);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'beforeSave');
        if (!$pluginInfo) {
            return parent::beforeSave();
        } else {
            return $this->___callPlugins('beforeSave', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQuote(\Magento\Quote\Model\Quote $quote)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQuote');
        if (!$pluginInfo) {
            return parent::setQuote($quote);
        } else {
            return $this->___callPlugins('setQuote', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQuote()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQuote');
        if (!$pluginInfo) {
            return parent::getQuote();
        } else {
            return $this->___callPlugins('getQuote', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function importCustomerAddressData(\Magento\Customer\Api\Data\AddressInterface $address)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'importCustomerAddressData');
        if (!$pluginInfo) {
            return parent::importCustomerAddressData($address);
        } else {
            return $this->___callPlugins('importCustomerAddressData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exportCustomerAddress()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'exportCustomerAddress');
        if (!$pluginInfo) {
            return parent::exportCustomerAddress();
        } else {
            return $this->___callPlugins('exportCustomerAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $arrAttributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toArray');
        if (!$pluginInfo) {
            return parent::toArray($arrAttributes);
        } else {
            return $this->___callPlugins('toArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemsCollection');
        if (!$pluginInfo) {
            return parent::getItemsCollection();
        } else {
            return $this->___callPlugins('getItemsCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllItems');
        if (!$pluginInfo) {
            return parent::getAllItems();
        } else {
            return $this->___callPlugins('getAllItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllVisibleItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllVisibleItems');
        if (!$pluginInfo) {
            return parent::getAllVisibleItems();
        } else {
            return $this->___callPlugins('getAllVisibleItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemQty($itemId = 0)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemQty');
        if (!$pluginInfo) {
            return parent::getItemQty($itemId);
        } else {
            return $this->___callPlugins('getItemQty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasItems');
        if (!$pluginInfo) {
            return parent::hasItems();
        } else {
            return $this->___callPlugins('hasItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemById($itemId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemById');
        if (!$pluginInfo) {
            return parent::getItemById($itemId);
        } else {
            return $this->___callPlugins('getItemById', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getValidItemById($itemId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getValidItemById');
        if (!$pluginInfo) {
            return parent::getValidItemById($itemId);
        } else {
            return $this->___callPlugins('getValidItemById', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemByQuoteItemId($itemId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemByQuoteItemId');
        if (!$pluginInfo) {
            return parent::getItemByQuoteItemId($itemId);
        } else {
            return $this->___callPlugins('getItemByQuoteItemId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem($itemId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeItem');
        if (!$pluginInfo) {
            return parent::removeItem($itemId);
        } else {
            return $this->___callPlugins('removeItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(\Magento\Quote\Model\Quote\Item\AbstractItem $item, $qty = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addItem');
        if (!$pluginInfo) {
            return parent::addItem($item, $qty);
        } else {
            return $this->___callPlugins('addItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingRatesCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingRatesCollection');
        if (!$pluginInfo) {
            return parent::getShippingRatesCollection();
        } else {
            return $this->___callPlugins('getShippingRatesCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllShippingRates()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllShippingRates');
        if (!$pluginInfo) {
            return parent::getAllShippingRates();
        } else {
            return $this->___callPlugins('getAllShippingRates', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGroupedAllShippingRates()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGroupedAllShippingRates');
        if (!$pluginInfo) {
            return parent::getGroupedAllShippingRates();
        } else {
            return $this->___callPlugins('getGroupedAllShippingRates', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingRateById($rateId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingRateById');
        if (!$pluginInfo) {
            return parent::getShippingRateById($rateId);
        } else {
            return $this->___callPlugins('getShippingRateById', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingRateByCode($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingRateByCode');
        if (!$pluginInfo) {
            return parent::getShippingRateByCode($code);
        } else {
            return $this->___callPlugins('getShippingRateByCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllShippingRates()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeAllShippingRates');
        if (!$pluginInfo) {
            return parent::removeAllShippingRates();
        } else {
            return $this->___callPlugins('removeAllShippingRates', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addShippingRate(\Magento\Quote\Model\Quote\Address\Rate $rate)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addShippingRate');
        if (!$pluginInfo) {
            return parent::addShippingRate($rate);
        } else {
            return $this->___callPlugins('addShippingRate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collectShippingRates()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'collectShippingRates');
        if (!$pluginInfo) {
            return parent::collectShippingRates();
        } else {
            return $this->___callPlugins('collectShippingRates', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function requestShippingRates(\Magento\Quote\Model\Quote\Item\AbstractItem $item = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'requestShippingRates');
        if (!$pluginInfo) {
            return parent::requestShippingRates($item);
        } else {
            return $this->___callPlugins('requestShippingRates', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotals()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotals');
        if (!$pluginInfo) {
            return parent::getTotals();
        } else {
            return $this->___callPlugins('getTotals', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addTotal($total)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addTotal');
        if (!$pluginInfo) {
            return parent::addTotal($total);
        } else {
            return $this->___callPlugins('addTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function itemsCollectionWasSet()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'itemsCollectionWasSet');
        if (!$pluginInfo) {
            return parent::itemsCollectionWasSet();
        } else {
            return $this->___callPlugins('itemsCollectionWasSet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function shippingRatesCollectionWasSet()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'shippingRatesCollectionWasSet');
        if (!$pluginInfo) {
            return parent::shippingRatesCollectionWasSet();
        } else {
            return $this->___callPlugins('shippingRatesCollectionWasSet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateMinimumAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validateMinimumAmount');
        if (!$pluginInfo) {
            return parent::validateMinimumAmount();
        } else {
            return $this->___callPlugins('validateMinimumAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAppliedTaxes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAppliedTaxes');
        if (!$pluginInfo) {
            return parent::getAppliedTaxes();
        } else {
            return $this->___callPlugins('getAppliedTaxes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setAppliedTaxes($data)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAppliedTaxes');
        if (!$pluginInfo) {
            return parent::setAppliedTaxes($data);
        } else {
            return $this->___callPlugins('setAppliedTaxes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingAmount($value, $alreadyExclTax = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingAmount');
        if (!$pluginInfo) {
            return parent::setShippingAmount($value, $alreadyExclTax);
        } else {
            return $this->___callPlugins('setShippingAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseShippingAmount($value, $alreadyExclTax = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseShippingAmount');
        if (!$pluginInfo) {
            return parent::setBaseShippingAmount($value, $alreadyExclTax);
        } else {
            return $this->___callPlugins('setBaseShippingAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalAmount($code, $amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTotalAmount');
        if (!$pluginInfo) {
            return parent::setTotalAmount($code, $amount);
        } else {
            return $this->___callPlugins('setTotalAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTotalAmount($code, $amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTotalAmount');
        if (!$pluginInfo) {
            return parent::setBaseTotalAmount($code, $amount);
        } else {
            return $this->___callPlugins('setBaseTotalAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addTotalAmount($code, $amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addTotalAmount');
        if (!$pluginInfo) {
            return parent::addTotalAmount($code, $amount);
        } else {
            return $this->___callPlugins('addTotalAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addBaseTotalAmount($code, $amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addBaseTotalAmount');
        if (!$pluginInfo) {
            return parent::addBaseTotalAmount($code, $amount);
        } else {
            return $this->___callPlugins('addBaseTotalAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalAmount($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotalAmount');
        if (!$pluginInfo) {
            return parent::getTotalAmount($code);
        } else {
            return $this->___callPlugins('getTotalAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTotalAmount($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTotalAmount');
        if (!$pluginInfo) {
            return parent::getBaseTotalAmount($code);
        } else {
            return $this->___callPlugins('getBaseTotalAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseSubtotalWithDiscount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseSubtotalWithDiscount');
        if (!$pluginInfo) {
            return parent::getBaseSubtotalWithDiscount();
        } else {
            return $this->___callPlugins('getBaseSubtotalWithDiscount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubtotalWithDiscount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSubtotalWithDiscount');
        if (!$pluginInfo) {
            return parent::getSubtotalWithDiscount();
        } else {
            return $this->___callPlugins('getSubtotalWithDiscount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllTotalAmounts()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllTotalAmounts');
        if (!$pluginInfo) {
            return parent::getAllTotalAmounts();
        } else {
            return $this->___callPlugins('getAllTotalAmounts', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllBaseTotalAmounts()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllBaseTotalAmounts');
        if (!$pluginInfo) {
            return parent::getAllBaseTotalAmounts();
        } else {
            return $this->___callPlugins('getAllBaseTotalAmounts', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCountryId');
        if (!$pluginInfo) {
            return parent::getCountryId();
        } else {
            return $this->___callPlugins('getCountryId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCountryId($countryId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCountryId');
        if (!$pluginInfo) {
            return parent::setCountryId($countryId);
        } else {
            return $this->___callPlugins('setCountryId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStreet()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStreet');
        if (!$pluginInfo) {
            return parent::getStreet();
        } else {
            return $this->___callPlugins('getStreet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStreet($street)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStreet');
        if (!$pluginInfo) {
            return parent::setStreet($street);
        } else {
            return $this->___callPlugins('setStreet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCompany()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCompany');
        if (!$pluginInfo) {
            return parent::getCompany();
        } else {
            return $this->___callPlugins('getCompany', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCompany($company)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCompany');
        if (!$pluginInfo) {
            return parent::setCompany($company);
        } else {
            return $this->___callPlugins('setCompany', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTelephone()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTelephone');
        if (!$pluginInfo) {
            return parent::getTelephone();
        } else {
            return $this->___callPlugins('getTelephone', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTelephone($telephone)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTelephone');
        if (!$pluginInfo) {
            return parent::setTelephone($telephone);
        } else {
            return $this->___callPlugins('setTelephone', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFax()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFax');
        if (!$pluginInfo) {
            return parent::getFax();
        } else {
            return $this->___callPlugins('getFax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setFax($fax)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setFax');
        if (!$pluginInfo) {
            return parent::setFax($fax);
        } else {
            return $this->___callPlugins('setFax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPostcode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPostcode');
        if (!$pluginInfo) {
            return parent::getPostcode();
        } else {
            return $this->___callPlugins('getPostcode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPostcode($postcode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPostcode');
        if (!$pluginInfo) {
            return parent::setPostcode($postcode);
        } else {
            return $this->___callPlugins('setPostcode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCity()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCity');
        if (!$pluginInfo) {
            return parent::getCity();
        } else {
            return $this->___callPlugins('getCity', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCity($city)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCity');
        if (!$pluginInfo) {
            return parent::setCity($city);
        } else {
            return $this->___callPlugins('setCity', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstname()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFirstname');
        if (!$pluginInfo) {
            return parent::getFirstname();
        } else {
            return $this->___callPlugins('getFirstname', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstname($firstname)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setFirstname');
        if (!$pluginInfo) {
            return parent::setFirstname($firstname);
        } else {
            return $this->___callPlugins('setFirstname', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLastname()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLastname');
        if (!$pluginInfo) {
            return parent::getLastname();
        } else {
            return $this->___callPlugins('getLastname', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setLastname($lastname)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setLastname');
        if (!$pluginInfo) {
            return parent::setLastname($lastname);
        } else {
            return $this->___callPlugins('setLastname', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMiddlename()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMiddlename');
        if (!$pluginInfo) {
            return parent::getMiddlename();
        } else {
            return $this->___callPlugins('getMiddlename', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setMiddlename($middlename)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setMiddlename');
        if (!$pluginInfo) {
            return parent::setMiddlename($middlename);
        } else {
            return $this->___callPlugins('setMiddlename', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPrefix()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPrefix');
        if (!$pluginInfo) {
            return parent::getPrefix();
        } else {
            return $this->___callPlugins('getPrefix', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPrefix($prefix)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPrefix');
        if (!$pluginInfo) {
            return parent::setPrefix($prefix);
        } else {
            return $this->___callPlugins('setPrefix', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSuffix()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSuffix');
        if (!$pluginInfo) {
            return parent::getSuffix();
        } else {
            return $this->___callPlugins('getSuffix', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setSuffix($suffix)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setSuffix');
        if (!$pluginInfo) {
            return parent::setSuffix($suffix);
        } else {
            return $this->___callPlugins('setSuffix', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getVatId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getVatId');
        if (!$pluginInfo) {
            return parent::getVatId();
        } else {
            return $this->___callPlugins('getVatId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setVatId($vatId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setVatId');
        if (!$pluginInfo) {
            return parent::setVatId($vatId);
        } else {
            return $this->___callPlugins('setVatId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerId');
        if (!$pluginInfo) {
            return parent::getCustomerId();
        } else {
            return $this->___callPlugins('getCustomerId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerId($customerId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerId');
        if (!$pluginInfo) {
            return parent::setCustomerId($customerId);
        } else {
            return $this->___callPlugins('setCustomerId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEmail');
        if (!$pluginInfo) {
            return parent::getEmail();
        } else {
            return $this->___callPlugins('getEmail', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setEmail');
        if (!$pluginInfo) {
            return parent::setEmail($email);
        } else {
            return $this->___callPlugins('setEmail', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRegion($region)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRegion');
        if (!$pluginInfo) {
            return parent::setRegion($region);
        } else {
            return $this->___callPlugins('setRegion', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRegionId($regionId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRegionId');
        if (!$pluginInfo) {
            return parent::setRegionId($regionId);
        } else {
            return $this->___callPlugins('setRegionId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRegionCode($regionCode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRegionCode');
        if (!$pluginInfo) {
            return parent::setRegionCode($regionCode);
        } else {
            return $this->___callPlugins('setRegionCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSameAsBilling()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSameAsBilling');
        if (!$pluginInfo) {
            return parent::getSameAsBilling();
        } else {
            return $this->___callPlugins('getSameAsBilling', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setSameAsBilling($sameAsBilling)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setSameAsBilling');
        if (!$pluginInfo) {
            return parent::setSameAsBilling($sameAsBilling);
        } else {
            return $this->___callPlugins('setSameAsBilling', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerAddressId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerAddressId');
        if (!$pluginInfo) {
            return parent::getCustomerAddressId();
        } else {
            return $this->___callPlugins('getCustomerAddressId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerAddressId($customerAddressId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerAddressId');
        if (!$pluginInfo) {
            return parent::setCustomerAddressId($customerAddressId);
        } else {
            return $this->___callPlugins('setCustomerAddressId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSaveInAddressBook()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSaveInAddressBook');
        if (!$pluginInfo) {
            return parent::getSaveInAddressBook();
        } else {
            return $this->___callPlugins('getSaveInAddressBook', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setSaveInAddressBook($saveInAddressBook)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setSaveInAddressBook');
        if (!$pluginInfo) {
            return parent::setSaveInAddressBook($saveInAddressBook);
        } else {
            return $this->___callPlugins('setSaveInAddressBook', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getExtensionAttributes');
        if (!$pluginInfo) {
            return parent::getExtensionAttributes();
        } else {
            return $this->___callPlugins('getExtensionAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(\Magento\Quote\Api\Data\AddressExtensionInterface $extensionAttributes)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setExtensionAttributes');
        if (!$pluginInfo) {
            return parent::setExtensionAttributes($extensionAttributes);
        } else {
            return $this->___callPlugins('setExtensionAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingMethod()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingMethod');
        if (!$pluginInfo) {
            return parent::getShippingMethod();
        } else {
            return $this->___callPlugins('getShippingMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getName');
        if (!$pluginInfo) {
            return parent::getName();
        } else {
            return $this->___callPlugins('getName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStreetLine($number)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStreetLine');
        if (!$pluginInfo) {
            return parent::getStreetLine($number);
        } else {
            return $this->___callPlugins('getStreetLine', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStreetFull()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStreetFull');
        if (!$pluginInfo) {
            return parent::getStreetFull();
        } else {
            return $this->___callPlugins('getStreetFull', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStreetFull($street)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStreetFull');
        if (!$pluginInfo) {
            return parent::setStreetFull($street);
        } else {
            return $this->___callPlugins('setStreetFull', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setData($key, $value = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setData');
        if (!$pluginInfo) {
            return parent::setData($key, $value);
        } else {
            return $this->___callPlugins('setData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function explodeStreetAddress()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'explodeStreetAddress');
        if (!$pluginInfo) {
            return parent::explodeStreetAddress();
        } else {
            return $this->___callPlugins('explodeStreetAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRegion()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRegion');
        if (!$pluginInfo) {
            return parent::getRegion();
        } else {
            return $this->___callPlugins('getRegion', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRegionCode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRegionCode');
        if (!$pluginInfo) {
            return parent::getRegionCode();
        } else {
            return $this->___callPlugins('getRegionCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRegionId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRegionId');
        if (!$pluginInfo) {
            return parent::getRegionId();
        } else {
            return $this->___callPlugins('getRegionId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCountry()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCountry');
        if (!$pluginInfo) {
            return parent::getCountry();
        } else {
            return $this->___callPlugins('getCountry', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryModel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCountryModel');
        if (!$pluginInfo) {
            return parent::getCountryModel();
        } else {
            return $this->___callPlugins('getCountryModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRegionModel($regionId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRegionModel');
        if (!$pluginInfo) {
            return parent::getRegionModel($regionId);
        } else {
            return $this->___callPlugins('getRegionModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function format($type)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'format');
        if (!$pluginInfo) {
            return parent::format($type);
        } else {
            return $this->___callPlugins('format', func_get_args(), $pluginInfo);
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
    public function getDataModel($defaultBillingAddressId = null, $defaultShippingAddressId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataModel');
        if (!$pluginInfo) {
            return parent::getDataModel($defaultBillingAddressId, $defaultShippingAddressId);
        } else {
            return $this->___callPlugins('getDataModel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate();
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsRegion()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsRegion');
        if (!$pluginInfo) {
            return parent::unsRegion();
        } else {
            return $this->___callPlugins('unsRegion', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomAttributes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomAttributes');
        if (!$pluginInfo) {
            return parent::getCustomAttributes();
        } else {
            return $this->___callPlugins('getCustomAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomAttribute($attributeCode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomAttribute');
        if (!$pluginInfo) {
            return parent::getCustomAttribute($attributeCode);
        } else {
            return $this->___callPlugins('getCustomAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomAttributes(array $attributes)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomAttributes');
        if (!$pluginInfo) {
            return parent::setCustomAttributes($attributes);
        } else {
            return $this->___callPlugins('setCustomAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomAttribute($attributeCode, $attributeValue)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomAttribute');
        if (!$pluginInfo) {
            return parent::setCustomAttribute($attributeCode, $attributeValue);
        } else {
            return $this->___callPlugins('setCustomAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsetData($key = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsetData');
        if (!$pluginInfo) {
            return parent::unsetData($key);
        } else {
            return $this->___callPlugins('unsetData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getData($key = '', $index = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getData');
        if (!$pluginInfo) {
            return parent::getData($key, $index);
        } else {
            return $this->___callPlugins('getData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setId($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setId');
        if (!$pluginInfo) {
            return parent::setId($value);
        } else {
            return $this->___callPlugins('setId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setIdFieldName($name)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setIdFieldName');
        if (!$pluginInfo) {
            return parent::setIdFieldName($name);
        } else {
            return $this->___callPlugins('setIdFieldName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIdFieldName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIdFieldName');
        if (!$pluginInfo) {
            return parent::getIdFieldName();
        } else {
            return $this->___callPlugins('getIdFieldName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getId');
        if (!$pluginInfo) {
            return parent::getId();
        } else {
            return $this->___callPlugins('getId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isDeleted($isDeleted = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isDeleted');
        if (!$pluginInfo) {
            return parent::isDeleted($isDeleted);
        } else {
            return $this->___callPlugins('isDeleted', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasDataChanges()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasDataChanges');
        if (!$pluginInfo) {
            return parent::hasDataChanges();
        } else {
            return $this->___callPlugins('hasDataChanges', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDataChanges($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDataChanges');
        if (!$pluginInfo) {
            return parent::setDataChanges($value);
        } else {
            return $this->___callPlugins('setDataChanges', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrigData($key = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOrigData');
        if (!$pluginInfo) {
            return parent::getOrigData($key);
        } else {
            return $this->___callPlugins('getOrigData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOrigData($key = null, $data = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setOrigData');
        if (!$pluginInfo) {
            return parent::setOrigData($key, $data);
        } else {
            return $this->___callPlugins('setOrigData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dataHasChangedFor($field)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dataHasChangedFor');
        if (!$pluginInfo) {
            return parent::dataHasChangedFor($field);
        } else {
            return $this->___callPlugins('dataHasChangedFor', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResourceName');
        if (!$pluginInfo) {
            return parent::getResourceName();
        } else {
            return $this->___callPlugins('getResourceName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResourceCollection');
        if (!$pluginInfo) {
            return parent::getResourceCollection();
        } else {
            return $this->___callPlugins('getResourceCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCollection');
        if (!$pluginInfo) {
            return parent::getCollection();
        } else {
            return $this->___callPlugins('getCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load($modelId, $field = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'load');
        if (!$pluginInfo) {
            return parent::load($modelId, $field);
        } else {
            return $this->___callPlugins('load', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeLoad($identifier, $field = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'beforeLoad');
        if (!$pluginInfo) {
            return parent::beforeLoad($identifier, $field);
        } else {
            return $this->___callPlugins('beforeLoad', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterLoad()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterLoad');
        if (!$pluginInfo) {
            return parent::afterLoad();
        } else {
            return $this->___callPlugins('afterLoad', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isSaveAllowed()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isSaveAllowed');
        if (!$pluginInfo) {
            return parent::isSaveAllowed();
        } else {
            return $this->___callPlugins('isSaveAllowed', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setHasDataChanges($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setHasDataChanges');
        if (!$pluginInfo) {
            return parent::setHasDataChanges($flag);
        } else {
            return $this->___callPlugins('setHasDataChanges', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save();
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterCommitCallback()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterCommitCallback');
        if (!$pluginInfo) {
            return parent::afterCommitCallback();
        } else {
            return $this->___callPlugins('afterCommitCallback', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isObjectNew($flag = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isObjectNew');
        if (!$pluginInfo) {
            return parent::isObjectNew($flag);
        } else {
            return $this->___callPlugins('isObjectNew', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateBeforeSave()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validateBeforeSave');
        if (!$pluginInfo) {
            return parent::validateBeforeSave();
        } else {
            return $this->___callPlugins('validateBeforeSave', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheTags()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCacheTags');
        if (!$pluginInfo) {
            return parent::getCacheTags();
        } else {
            return $this->___callPlugins('getCacheTags', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function cleanModelCache()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'cleanModelCache');
        if (!$pluginInfo) {
            return parent::cleanModelCache();
        } else {
            return $this->___callPlugins('cleanModelCache', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterSave');
        if (!$pluginInfo) {
            return parent::afterSave();
        } else {
            return $this->___callPlugins('afterSave', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete();
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeDelete()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'beforeDelete');
        if (!$pluginInfo) {
            return parent::beforeDelete();
        } else {
            return $this->___callPlugins('beforeDelete', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterDelete');
        if (!$pluginInfo) {
            return parent::afterDelete();
        } else {
            return $this->___callPlugins('afterDelete', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterDeleteCommit()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterDeleteCommit');
        if (!$pluginInfo) {
            return parent::afterDeleteCommit();
        } else {
            return $this->___callPlugins('afterDeleteCommit', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResource');
        if (!$pluginInfo) {
            return parent::getResource();
        } else {
            return $this->___callPlugins('getResource', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEntityId');
        if (!$pluginInfo) {
            return parent::getEntityId();
        } else {
            return $this->___callPlugins('getEntityId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityId($entityId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setEntityId');
        if (!$pluginInfo) {
            return parent::setEntityId($entityId);
        } else {
            return $this->___callPlugins('setEntityId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearInstance()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clearInstance');
        if (!$pluginInfo) {
            return parent::clearInstance();
        } else {
            return $this->___callPlugins('clearInstance', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStoredData()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoredData');
        if (!$pluginInfo) {
            return parent::getStoredData();
        } else {
            return $this->___callPlugins('getStoredData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEventPrefix()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEventPrefix');
        if (!$pluginInfo) {
            return parent::getEventPrefix();
        } else {
            return $this->___callPlugins('getEventPrefix', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addData(array $arr)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addData');
        if (!$pluginInfo) {
            return parent::addData($arr);
        } else {
            return $this->___callPlugins('addData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByPath($path)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataByPath');
        if (!$pluginInfo) {
            return parent::getDataByPath($path);
        } else {
            return $this->___callPlugins('getDataByPath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByKey($key)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataByKey');
        if (!$pluginInfo) {
            return parent::getDataByKey($key);
        } else {
            return $this->___callPlugins('getDataByKey', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDataUsingMethod($key, $args = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDataUsingMethod');
        if (!$pluginInfo) {
            return parent::setDataUsingMethod($key, $args);
        } else {
            return $this->___callPlugins('setDataUsingMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataUsingMethod($key, $args = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataUsingMethod');
        if (!$pluginInfo) {
            return parent::getDataUsingMethod($key, $args);
        } else {
            return $this->___callPlugins('getDataUsingMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasData($key = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasData');
        if (!$pluginInfo) {
            return parent::hasData($key);
        } else {
            return $this->___callPlugins('hasData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToArray(array $keys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToArray');
        if (!$pluginInfo) {
            return parent::convertToArray($keys);
        } else {
            return $this->___callPlugins('convertToArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toXml(array $keys = array(), $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toXml');
        if (!$pluginInfo) {
            return parent::toXml($keys, $rootName, $addOpenTag, $addCdata);
        } else {
            return $this->___callPlugins('toXml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToXml(array $arrAttributes = array(), $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToXml');
        if (!$pluginInfo) {
            return parent::convertToXml($arrAttributes, $rootName, $addOpenTag, $addCdata);
        } else {
            return $this->___callPlugins('convertToXml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toJson(array $keys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toJson');
        if (!$pluginInfo) {
            return parent::toJson($keys);
        } else {
            return $this->___callPlugins('toJson', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToJson(array $keys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToJson');
        if (!$pluginInfo) {
            return parent::convertToJson($keys);
        } else {
            return $this->___callPlugins('convertToJson', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toString($format = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toString');
        if (!$pluginInfo) {
            return parent::toString($format);
        } else {
            return $this->___callPlugins('toString', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, $args)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, '__call');
        if (!$pluginInfo) {
            return parent::__call($method, $args);
        } else {
            return $this->___callPlugins('__call', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isEmpty');
        if (!$pluginInfo) {
            return parent::isEmpty();
        } else {
            return $this->___callPlugins('isEmpty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($keys = array(), $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'serialize');
        if (!$pluginInfo) {
            return parent::serialize($keys, $valueSeparator, $fieldSeparator, $quote);
        } else {
            return $this->___callPlugins('serialize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function debug($data = null, &$objects = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'debug');
        if (!$pluginInfo) {
            return parent::debug($data, $objects);
        } else {
            return $this->___callPlugins('debug', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetSet');
        if (!$pluginInfo) {
            return parent::offsetSet($offset, $value);
        } else {
            return $this->___callPlugins('offsetSet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetExists');
        if (!$pluginInfo) {
            return parent::offsetExists($offset);
        } else {
            return $this->___callPlugins('offsetExists', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetUnset');
        if (!$pluginInfo) {
            return parent::offsetUnset($offset);
        } else {
            return $this->___callPlugins('offsetUnset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetGet');
        if (!$pluginInfo) {
            return parent::offsetGet($offset);
        } else {
            return $this->___callPlugins('offsetGet', func_get_args(), $pluginInfo);
        }
    }
}
