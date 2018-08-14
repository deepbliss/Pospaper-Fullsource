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
    public function addData(array $arr)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addData');
        if (!$pluginInfo) {
            return parent::addData($arr);
        } else {
            return $this->___callPlugins('addData', func_get_args(), $pluginInfo);
        }
    }
}
