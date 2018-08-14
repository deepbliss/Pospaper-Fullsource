<?php

namespace CommerceExtensions\OrderImportExport\Model\Data;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Customer\Api\Data\AddressInterface;

class CsvImportHandler
{
    protected $requiredFields = ['name',
                                 'label',
                                 'codecode',
                                 'conditions_aggregator',
                                 'conditions',
                                 'actions_aggregator',
                                 'actions',
                                 'usage_limit',
                                 'expiration_date',
                                 'description',
                                 'from_date',
                                 'to_date',
                                 'uses_per_customer',
                                 'customer_group_ids',
                                 'is_active',
                                 'is_primary',
                                 'stop_rules_processing',
                                 'is_advanced',
                                 'product_ids',
                                 'sort_order',
                                 'simple_action',
                                 'discount_amount',
                                 'discount_qty',
                                 'discount_step',
                                 'simple_free_shipping',
                                 'apply_to_shipping',
                                 'times_used',
                                 'is_rss',
                                 'coupon_type',
                                 'use_auto_generation',
                                 'type',
                                 'coupon_times_used',
                                 'website_ids'];

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $params;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $file;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var \Magento\Sales\Model\Spi\OrderResourceInterfaceFactory
     */
    protected $orderResourceInterfaceFactory;

    /**
     * @var \Magento\Quote\Api\Data\CartInterfaceFactory
     */
    protected $cartInterfaceFactory;

    /**
     * @var \Magento\Quote\Api\Data\CartInterface
     */
    protected $currentQuote;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order
     */
    protected $orderResource;

    /**
     * @var \Magento\Store\Model\StoreManagerInterfaceFactory
     */
    protected $storeManagerInterfaceFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    protected $customerGroupCollectionFactory;

    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected $customerGroupRepositoryInterface;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Customer\Model\CustomerRegistry
     */
    protected $customerRegistry;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\AddressRepository
     */
    protected $addressRepository;

    /**
     * @var \Magento\Customer\Api\Data\AddressInterfaceFactory
     */
    protected $addressFactory;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $countryFactory;

    /**
     * @var \Magento\Directory\Model\RegionFactory
     */
    protected $regionFactory;

    /****** begin old properties ********/

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /****** end old properties ********/

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\File\CsvFactory $csvProcessor,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\ResourceModel\OrderFactory $orderResourceFactory,
        \Magento\Sales\Model\Order\Shipment\TrackFactory $shipmentTrackFactory,
        \Magento\Quote\Api\Data\CartInterfaceFactory $cartInterfaceFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Store\Model\StoreManagerInterfaceFactory $storeManagerInterfaceFactory,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $customerGroupCollectionFactory,
        \Magento\Customer\Api\GroupRepositoryInterface $customerGroupRepositoryInterface,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\ResourceModel\AddressRepository $addressRepository,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection,

        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->resourceConnection               = $resourceConnection;
        $this->scopeConfig                      = $scopeConfig;
        $this->adapter                          = $resourceConnection->getConnection();
        $this->csvProcessor                     = $csvProcessor->create();
        $this->orderCollectionFactory           = $orderCollectionFactory;
        $this->shipmentTrackFactory             = $shipmentTrackFactory;
        $this->cartInterfaceFactory             = $cartInterfaceFactory;
        $this->orderRepository                  = $orderRepository;
        $this->orderResource                    = $orderResourceFactory->create();
        $this->storeManagerInterfaceFactory     = $storeManagerInterfaceFactory;
        $this->customerGroupCollectionFactory   = $customerGroupCollectionFactory;
        $this->customerGroupRepositoryInterface = $customerGroupRepositoryInterface;
        $this->customerRepository               = $customerRepositoryInterface;
        $this->customerRegistry                 = $customerRegistry;
        $this->customerFactory                  = $customerFactory;
        $this->addressRepository                = $addressRepository;
        $this->addressFactory                   = $addressFactory;
        $this->countryFactory                   = $countryFactory;
        $this->regionFactory                    = $regionFactory;
        $this->regionCollection                 = $regionCollection;

        /****** begin old properties ********/
        $this->productRepository = $productRepository;
        $this->objectManager     = $objectManager;
        /****** end old properties ********/
    }

    /**
     * @param array $fields
     *
     * @return bool
     * @throws \Exception
     */
    protected function validateFields(array $fields)
    {
        foreach($this->requiredFields as $field) {
            if(!in_array($field, $fields)) {
                throw new \Exception(__(sprintf('%s missing from import request.', $field)));
            }
        }
        return true;
    }

    /**
     * @param array $file
     * @param array $params
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function importFromCsvFile(array $file, array $params)
    {
        $this->params = new \Magento\Framework\DataObject($params);
        $this->file   = new \Magento\Framework\DataObject($file);

        if(!$this->file->getTmpName()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));
        }

        if($this->params->getImportDelimiter()) {
            $this->csvProcessor->setDelimiter($this->params->getImportDelimiter());
        }
        if($this->params->getImportEnclose()) {
            $this->csvProcessor->setEnclosure($this->params->getImportEnclose());
        }

        $data    = $this->csvProcessor->getData($this->file->getTmpName());
        $columns = $data[0];
        unset($data[0]);
        $rowCounter=1;

        #foreach($data as &$row) {
        foreach($data as $key => &$row) {
            //TODO create model/interface for rows instead of using \Magento\Framework\DataObject
            try {
                $row = array_combine($columns, $row);
                $this->importOrder(new \Magento\Framework\DataObject($row));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__('ROW: ' . $rowCounter . ' ERROR: ' . $e->getMessage()), $e);
            }
            catch (\Exception $e){
                throw new \Magento\Framework\Exception\LocalizedException(__('ROW: ' . $rowCounter . ' ERROR: ' . $e->getMessage()), $e);
            }
            $rowCounter++;
        }
    }

    protected function _getStoreConfig($path, $storeId)
    {
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
    protected function _getNotCachedConfig( $path, $storeId)
    {
        $type = 'order';
        $cfg = $this->_getStoreConfig('customnumber/' . $type, $storeId);

        $scope   = 'default';
        $scopeId = 0;
        
        //'core/config_data_collection'
        $collection = $this->objectManager->create("Magento\Config\Model\ResourceModel\Config\Data\Collection");
        $collection->addFieldToFilter('scope', $scope);
        $collection->addFieldToFilter('scope_id', $scopeId);
        $collection->addFieldToFilter('path', 'customnumber/' . $type . '/' . $path);
        $collection->setPageSize(1);

        $v = $this->objectManager->create('Magento\Framework\App\Config\Value');
        if (count($collection)){
            $v = $collection->getFirstItem();
        }
        else {
            $v->setScope($scope);
            $v->setScopeId($scopeId);
            $v->setPath('customnumber/' . $type . '/' . $path);
        }

        return $v;
    }
    /**
     * @param \Magento\Framework\DataObject $row
     *
     * @return $this
     */
    protected function setOrderSequenceId(\Magento\Framework\DataObject $row)
    {
        //TODO test
        if(is_numeric($row->getOrderId()) && is_numeric($row->getStoreId())) {
            $finalId = (int)$row->getOrderId();
             
            $customnumber = $this->_getNotCachedConfig('customnumber', $row->getStoreId());
            $customnumber->setValue($finalId);
            $customnumber->save();
            #$table   = $this->orderResource->getTable('sequence_order_' . $row->getStoreId());
            #$this->adapter->query("ALTER TABLE {$table} AUTO_INCREMENT={$finalId}");
        }
        return $this;
    }

    /**
     * @param \Magento\Framework\DataObject $row
     *
     * @return $this
     */
    protected function setInvoiceSequenceId(\Magento\Framework\DataObject $row)
    {
        //TODO test
        if(is_numeric($row->getInvoiceId()) && is_numeric($row->getStoreId())) {
            $finalId = (int)$row->getInvoiceId() - 1;
            $table   = $this->orderResource->getTable('sequence_invoice_' . $row->getStoreId());
            $this->adapter->query("ALTER TABLE {$table} AUTO_INCREMENT={$finalId}");
        }
        return $this;
    }
    
    protected function setShipmentSequenceId(\Magento\Framework\DataObject $row)
    {
        //TODO test
        if(is_numeric($row->getShipmentId()) && is_numeric($row->getStoreId())) {
            $finalId = (int)$row->getShipmentId() - 1;
            $table   = $this->orderResource->getTable('sequence_shipment_' . $row->getStoreId());
            $this->adapter->query("ALTER TABLE {$table} AUTO_INCREMENT={$finalId}");
        }
        return $this;
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @param string                        $type
     *
     * @return array
     * @throws \Exception
     */
    protected function getMappedAddress(\Magento\Framework\DataObject $row, $type)
    {
        if(!in_array($type, ['billing', 'shipping'])) {
            throw new \Exception(__('Valid types for mapping address data are billing or shipping.'));
        }

        /** valid $type is either 'billing' or 'shipping' */
        return [
            AddressInterface::COMPANY    => $row->getData("{$type}_company"),
            AddressInterface::PREFIX     => $row->getData("{$type}_prefix"),
            AddressInterface::FIRSTNAME  => $row->getData("{$type}_firstname"),
            AddressInterface::MIDDLENAME => $row->getData("{$type}_middlename"),
            AddressInterface::LASTNAME   => $row->getData("{$type}_lastname"),
            AddressInterface::SUFFIX     => $row->getData("{$type}_suffix"),
            AddressInterface::STREET     => $row->getData("{$type}_street"),
            AddressInterface::CITY       => $row->getData("{$type}_city"),
            AddressInterface::REGION_ID  => $this->getRegionId($row, $type),
            AddressInterface::COUNTRY_ID => $row->getData("{$type}_country_id"),
            AddressInterface::POSTCODE   => $row->getData("{$type}_postcode"),
            AddressInterface::TELEPHONE  => $row->getData("{$type}_telephone"),
            AddressInterface::FAX        => $row->getData("{$type}_fax")
        ];
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @param string                        $type
     *
     * @return array
     * @throws \Exception
     */
    protected function getFormattedStreet(\Magento\Framework\DataObject $row, $type)
    {
        if(!in_array($type, ['billing', 'shipping'])) {
            throw new \Exception(__('Valid types for mapping address data are billing or shipping.'));
        }

        $street = [
            $row->getData("{$type}_street"),
            $row->getData("{$type}_street_full"),
            $row->getData("{$type}_street2"),
            $row->getData("{$type}_street3"),
            $row->getData("{$type}_street4")
        ];
        return array_filter($street, 'strlen');
    }

    /**
     * @param array $address
     *
     * @return string
     */
    public function getAddressComparisonString(array $address)
    {
        $address = array_map(function ($value) {
            if(is_array($value)) {
                $value = implode('', $value);
            }
            return $value;
        }, $address);
        return preg_replace('/[^\da-z]/i', '', strtolower(implode('', $address)));
    }

    /**
     * @param array $addressA
     * @param array $addressB
     *
     * @return bool
     */
    protected function compareAddresses(array $addressA, array $addressB)
    {
        $addressA = $this->getAddressComparisonString($addressA);
        $addressB = $this->getAddressComparisonString($addressB);
        return strcasecmp($addressA, $addressB) === 0;
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @param string                        $type
     *
     * @return mixed
     * @throws \Exception
     */
    protected function getCountryId(\Magento\Framework\DataObject $row, $type)
    {
        if(!in_array($type, ['billing', 'shipping'])) {
            throw new \Exception(__('Valid types for mapping address data are billing or shipping.'));
        }

        $id = $row->getData("{$type}_country");

        /** @var \Magento\Directory\Model\Country $country */
        if($id!=""){
            $country = $this->countryFactory->create();
            $country->loadByCode($id);
            if($country->getId()) {
                $id = $country->getId();
            }
        }
        return $id;
    }

    protected function getRegionId(\Magento\Framework\DataObject $row, $type)
    {
        if(!in_array($type, ['billing', 'shipping'])) {
            throw new \Exception(__('Valid types for mapping address data are billing or shipping.'));
        }

        $id = $row->getData("{$type}_region");

        /** @var \Magento\Directory\Model\Region $region */
        $region = $this->regionFactory->create();
        $region->loadByName($id, $row->getData("{$type}_country"));
        if($region->getId()) {
            $id = $region->getId();
        }
        return $id;
    }

    /**
     * @param \Magento\Framework\DataObject $row
     *
     * @return int|null
     * @throws \Exception
     */
    protected function getCustomerGroupId(\Magento\Framework\DataObject $row)
    {
        if(!$id = $row->getGroupId()) {
            throw new \Exception(__('Customer Group ID missing from import request.'));
        }

        if(is_numeric($id)) {
            return $this->customerGroupRepositoryInterface->getById($id)->getId();
        }

        /** @var \Magento\Customer\Model\ResourceModel\Group\Collection $collection */
        $collection = $this->customerGroupCollectionFactory->create();
        $collection->addFieldToFilter('customer_group_code', $id);
        if(!$id = $collection->getFirstItem()->getId()) {
            throw new \Exception(__('Invalid Customer Grouped ID in import request.'));
        }
        return $id;
    }

    /**
     * @param \Magento\Framework\DataObject $row
     *
     * @return \Magento\Customer\Model\Customer
     */
    protected function createCustomer(\Magento\Framework\DataObject $row)
    {
        /** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
        $storeManager = $this->storeManagerInterfaceFactory->create();
        $websiteId    = $storeManager->getStore($row->getStoreId())->getWebsiteId();

        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $this->customerFactory->create();
        $customer->setPasswordHash($row->getPasswordHash());
        $customer->setGroupId($row->getGroupId());
        $customer->setWebsiteId($websiteId);
        $customer->addData($row->getData());

        /** @var \Magento\Customer\Api\Data\CustomerInterface $dataModel */
        $dataModel = $customer->getDataModel();
        $dataModel = $this->customerRepository->save($dataModel);

        return $this->customerRegistry->retrieveByEmail($dataModel->getEmail(),$websiteId);
    }

    /**
     * @param array                            $data
     * @param \Magento\Customer\Model\Customer $customer
     * @param bool                             $isDefaultBilling
     * @param bool                             $isDefaultShipping
     *
     * @return \Magento\Customer\Model\Data\Address
     */
    protected function createAddress(array $data, \Magento\Customer\Model\Customer $customer, $isDefaultBilling = false, $isDefaultShipping = false)
    {
        /** @var \Magento\Customer\Model\Data\Address $address */
        $address = $this->addressFactory->create();
        $address->setCustomerId($customer->getId());
        $address->setCompany($data['company']);
        $address->setPrefix($data['prefix']);
        $address->setFirstname($data['firstname']);
        $address->setMiddlename($data['middlename']);
        $address->setLastname($data['lastname']);
        $address->setSuffix($data['suffix']);
        $address->setStreet($data['street']);
        $address->setCity($data['city']);
        $address->setRegionId($data['region_id']);
        $address->setCountryId($data['country_id']);
        $address->setPostcode($data['postcode']);
        $address->setTelephone($data['telephone']);
        $address->setFax($data['fax']);
        $address->setIsDefaultBilling($isDefaultBilling);
        $address->setIsDefaultShipping($isDefaultShipping);
        $this->addressRepository->save($address);
        return $address;
    }

    protected function handleCustomer(\Magento\Framework\DataObject $row)
    {
        if(!$row->getIsGuest()) {

            if(!$row->getCustomerId() && !$row->getEmail()) {
                throw new \Exception(__('Customer ID or E-mail address is required.'));
            }

            $row->setGroupId($this->getCustomerGroupId($row));

            $isNewCustomer = false;
            
            try {
                if($id = $row->getCustomerId()) {
                    $customer = $this->customerRegistry->retrieve($id);
                } else {
                    $storeManager = $this->storeManagerInterfaceFactory->create();
                    $websiteId    = $storeManager->getStore($row->getStoreId())->getWebsiteId();
                    if($websiteId > 0) {
                        $customer = $this->customerRegistry->retrieveByEmail($row->getEmail(), $websiteId);
                    } else {
                        $customer = $this->customerRegistry->retrieveByEmail($row->getEmail());
                    }
                }
            } catch(\Exception $e) {
                $customer = $this->createCustomer($row);
                $isNewCustomer = true;
            }

            if($this->params->getUpdateCustomerAddress() == 'true' || $isNewCustomer) {

                if($defaultBillingId = $customer->getData(AddressInterface::DEFAULT_BILLING)) {
                    $this->addressRepository->deleteById($defaultBillingId);
                }

                $defaultShippingId = $customer->getData(AddressInterface::DEFAULT_SHIPPING);
                if(($defaultBillingId != $defaultShippingId) && $defaultShippingId) {
                    $this->addressRepository->deleteById($defaultShippingId);
                }

                $row->setBillingStreet($this->getFormattedStreet($row, 'billing'));
                $row->setBillingCountryId($this->getCountryId($row, 'billing'));
                $row->setBillingRegionId($this->getRegionId($row, 'billing'));

                $row->setShippingStreet($this->getFormattedStreet($row, 'shipping'));
                $row->setShippingCountryId($this->getCountryId($row, 'shipping'));
                $row->setShippingRegionId($this->getRegionId($row, 'shipping'));

                $billingAddress   = $this->getMappedAddress($row, 'billing');
                $shippingAddress  = $this->getMappedAddress($row, 'shipping');
                $addressesAreSame = $this->compareAddresses($billingAddress, $shippingAddress);
                //these are commented out but i had one site only that oddly $address->isDefaultBilling() is always false
                #$defaultBilling = false;
                #$defaultShipping = false;
                
                if($addressesAreSame) {
                    $addressBilingData = $this->createAddress($billingAddress, $customer, true, true);
                    $customer->setDefaultBilling($addressBilingData->getId());
                    #$defaultBilling = true;
                } else {
                    $addressBilingData = $this->createAddress($billingAddress, $customer, true, false);
                    $customer->setDefaultBilling($addressBilingData->getId());
                    #$defaultBilling = true;
                    #$this->createAddress($shippingAddress, $customer, false, true);
                    if($row->getShippingCountryId()!="") {
                        $addressShippingData = $this->createAddress($shippingAddress, $customer, false, true);
                        $customer->setDefaultShipping($addressShippingData->getId());
                        #$defaultShipping = true;
                    }
                }
                /** update customer model with ids of default billing and shipping */
                $customer = $this->customerRepository->getById($customer->getId());
                //took the below out i think above is ok to do instead
                #foreach($customer->getAddresses() as $address) {
                    #if($address->isDefaultBilling()) {
                    #if($defaultBilling) {
                        #$customer->setDefaultBilling($address->getId());
                    #}
                    #if($address->isDefaultShipping()) {
                    #if($defaultShipping) {
                        #$customer->setDefaultShipping($address->getId());
                    #}
                #}
                $this->customerRepository->save($customer);

                /** freshly load customer model? */
                $customer = $this->customerRegistry->retrieve($customer->getId());
            }
            return $customer;
        }
        return false;
    }
    
    protected function setOrderProductData(\Magento\Framework\DataObject $row)
    {
        if(!$row->getProductsOrdered()) {
           throw new \Exception(__('products_ordered field is required.'));
        }
        
        $add_products_array = array();
        $productcounter   = 1;
        foreach(explode('|', $row->getProductsOrdered()) as $data) {

            $parts = explode(':', $data);
            //splitting options into string (e.g. Single-Eva:1:simple:8x10:Black:None:None)
            $fullSKU = $parts[0];
            if(isset($parts[1])) {
                $part_qty = $parts[1];
            } else {
                $part_qty = "1";
            }
            $i       = 3;
            while(isset($parts[$i])) {
                $fullSKU .= "-" . $parts[$i];
                $i++;
            }
            
            $keyforinsert = "I-oe_productstandin";
            $product_id = $parts[0];
            
            try {
                if($this->params->getSkipProductLookup() == 'true') {
                    $product = $this->productRepository->get($product_id);
                } else {
                    $product = $this->productRepository->get($product_id);
                }
                
            } catch(\Exception $e) {
                $product = $this->createProduct($row->getStoreId(), $keyforinsert);
            }
            
            
            try {
                $this->handleProduct($data, $add_products_array, $row->getData(), $product, $productcounter, $fullSKU);
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $row->getOrderId() . " ERROR PRODUCT DOESN'T EXIST: [sku]" . $product_id . " " . $e->getMessage()), $e);
            }

            //Alter Array Key for qty
            if(isset($add_products_array[$fullSKU]['productID'])) {
                $add_products_array[$fullSKU]['qty'] = $part_qty;
            } else {
                //There was a problem during handleProduct and our product wasn't made. A filler/import product was created instead
                unset($add_products_array[$fullSKU]);
            }
            
            $productcounter++;
        }
        return $add_products_array;
    }

    protected function importOrder(\Magento\Framework\DataObject $row)
    {
        //added this little check here because we would get duplicate key sql error because orderID already exists and was trying to reimport again
        $orders = $this->objectManager->get('Magento\Sales\Model\Order')->getCollection();
        $orderId = $row->getOrderId();
        $orders->addFilter("increment_id", $orderId);
        
        //if order_id already exists skip to next row
        if ($orderId) {
            $ord = $orders->getFirstItem();
            if ($ord && $ord->getId()) {
                return; 
            }
        }
        //TODO: make sure we only do this on US based orders
        //ZIPCODE LOOK UP FOR BILLING REGION
        /*
        if($row->getBillingRegion() == "" && $row->getBillingPostcode()!="") {
            
            $pos = strpos($row->getBillingPostcode(), "-");
            if ($pos !== false) {
                $billingPostcodeArray = explode("-", $row->getBillingPostcode());
                $billing_postcode = $billingPostcodeArray[0];
            } else {
                $billing_postcode = $row->getBillingPostcode();
            }
            try {
                $billing_data = file_get_contents("http://api.zippopotam.us/us/".$billing_postcode);
            } catch(\Exception $e) {
                $billing_data = file_get_contents("http://api.zippopotam.us/us/10001");
            }
            $billing_data_array = $this->objectManager->get(\Magento\Framework\Json\Helper\Data::class)->jsonDecode($billing_data);
            $row->setData("billing_region", $billing_data_array["places"][0]["state"]);
        
        }
        */
        //ZIPCODE LOOK UP FOR BILLING REGION
        //ZIPCODE LOOK UP FOR SHIPPING REGION
        /*
        if($row->getShippingRegion() == "" && $row->getShippingPostcode()!="") {
        
            $pos = strpos($row->getShippingPostcode(), "-");
            if ($pos !== false) {
                $shippingPostcodeArray = explode("-", $row->getShippingPostcode());
                $shipping_postcode = $shippingPostcodeArray[0];
            } else {
                $shipping_postcode = $row->getShippingPostcode();
            }
            try {
                $shipping_data = file_get_contents("http://api.zippopotam.us/us/".$shipping_postcode);
            } catch(\Exception $e) {
                $shipping_data = file_get_contents("http://api.zippopotam.us/us/10001");
            }
            $shipping_data_array = $this->objectManager->get(\Magento\Framework\Json\Helper\Data::class)->jsonDecode($shipping_data);
            $row->setData("shipping_region", $shipping_data_array["places"][0]["state"]);
        
        }
        */
        //ZIPCODE LOOK UP FOR SHIPPING REGION
        
        //LOOKING FOR BLANK SPACE
        /*
        if(strlen($row->getBillingTelephone())<=1) {
            $row->setBillingTelephone("000-000-0000");
        }
        if(strlen($row->getShippingTelephone())<=1 ) {
            $row->setShippingTelephone("000-000-0000");
        }
        */
        //LOOKING FOR BLANK SPACE
        /*
        if(strlen($row->getBillingCompany())<=1) {
            $row->setBillingCompany("N/A");
        }
        if(strlen($row->getShippingCompany())<=1 ) {
            $row->setShippingCompany("N/A");
        }
        
        if($row->getOrderStatus() == "completed") {
            $row->setOrderStatus("complete");
        }
        */
        //FULL CHECK HERE FOR BLANKS AND USE DUMBY DATA
        /*
        if($row->getBillingStreetFull() == "") {
            $row->setBillingStreetFull("26 Broadway");
        }
        if($row->getShippingStreetFull() == "") {
            $row->setShippingStreetFull("26 Broadway");
        }
        if($row->getBillingCity() == "") {
            $row->setBillingCity("New York");
        }
        if($row->getShippingCity() == "") {
            $row->setShippingCity("New York");
        }
        if($row->getBillingRegion() == "") {
            $row->setBillingRegion("New York");
        }
        if($row->getShippingRegion() == "") {
            $row->setShippingRegion("New York");
        }
        if($row->getBillingPostcode() == "") {
            $row->setBillingPostcode("10004");
        }
        if($row->getShippingPostcode() == "") {
            $row->setShippingPostcode("10004");
        }
        if($row->getFirstname() == "") {
            if($row->getBillingFirstname()!="") {
                $row->setFirstname($row->getBillingFirstname());
            }
        }
        if($row->getLastname() == "") {
            if($row->getBillingLastname()!="") {
                $row->setLastname($row->getBillingLastname());
            }
        }
        if($row->getShippingFirstname() == "") {
            if($row->getBillingFirstname()!="") {
                $row->setShippingFirstname($row->getBillingFirstname());
            }
        }
        if($row->getShippingLastname() == "") {
            if($row->getBillingLastname()!="") {
                $row->setShippingLastname($row->getBillingLastname());
            }
        }
        if( strpos($row->getEmail(), ".con") !== false ) {
            $finalEmail = str_replace(".con",".com",$row->getEmail());
            $row->setEmail($finalEmail);
        }
        if( strpos($row->getEmail(), ".netishtar") !== false ) {
            $finalEmail = str_replace(".netishtar",".net",$row->getEmail());
            $row->setEmail($finalEmail);
        }
        */
        //FULL CHECK HERE FOR BLANKS AND USE DUMBY DATA
        
        if(strpos($row->getOrderId(), '-') !== false) {
            $this->currentQuote = $this->cartInterfaceFactory->create();
            $parts = explode("-", $row->getOrderId());
            //TODO test the linking of the old orders
            /** @var \Magento\Sales\Model\ResourceModel\Order\Collection $collection */
            $collection = $this->orderCollectionFactory->create();
            $collection->addFilter(OrderInterface::INCREMENT_ID, $parts[0]);
            $order = $collection->getFirstItem();
            if($order->getId()) {
                $order->setReordered(true);
                $this->currentQuote->setOrigOrderId($order->getId());
                $this->currentQuote->setOrderId($order->getId());
                $this->objectManager->get('\Magento\Backend\Model\Session\Quote')->setOrderId($order->getId());
            }
        }

        $this->setOrderSequenceId($row); // this possible needs to be in a if else from above cause dont set if order # has -
        #$this->setInvoiceSequenceId($row); //dont need this now its set in createInvoice
        #$this->setShipmentSequenceId($row); //dont need this now its set in createShipments

        $customer           = "";
        $customerId         = "";
        if($customer = $this->handleCustomer($row)) {
            $customerId = $customer->getId();
        }

        $add_products_array = $this->setOrderProductData($row);
    
        //remove tracking data if empty
        if(strlen($row->getTrackingDate())<=1) { $row->unsTrackingDate(); }
        if(strlen($row->getTrackingShipMethod())<=1) { $row->unsTrackingShipMethod(); }
        if(strlen($row->getTrackingCodes())<=1) { $row->unsTrackingCodes(); }
            
        //shipping
        $final_shipping = $this->createShipping($customer, $row);
        

        //assemble data structure
        $orderData = $this->assembleOrderData($customerId,
                                              $row,
                                              $add_products_array,
                                              $customer,
                                              $final_shipping);
        //process order
        if(!empty($orderData)) {
            $this->_initSession($orderData['session']);
            $this->processOrder($orderData, $row->getData(), $this->params);
        }
        #exit;
    }

    /**
     * Retrieve a list of fields required for CSV file (order is important!)
     *
     * @return array
     */
    public function getRequiredCsvFields()
    {
        return $this->requiredFields;
    }

    /**
     * Filter file fields (i.e. unset invalid fields)
     *
     * @param array $fileFields
     *
     * @return string[] filtered fields
     */
    protected function _filterFileFields(array $fileFields)
    {
        $filteredFields    = $this->getRequiredCsvFields();
        $requiredFieldsNum = count($this->getRequiredCsvFields());
        $fileFieldsNum     = count($fileFields);

        // process title-related fields that are located right after required fields with store code as field name)
        for($index = $requiredFieldsNum; $index < $fileFieldsNum; $index++) {
            $titleFieldName = $fileFields[$index];
            if($this->_isStoreCodeValid($titleFieldName)) {
                // if store is still valid, append this field to valid file fields
                $filteredFields[$index] = $titleFieldName;
            }
        }

        return $filteredFields;
    }

    /**
     * Filter data (i.e. unset all invalid fields and check consistency)
     *
     * @param array $rateRawData
     * @param array $invalidFields assoc array of invalid file fields
     * @param array $validFields   assoc array of valid file fields
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function _filterData(array $RawDataHeader, array $RawData)
    {
        $rowCount = 0;
        #$RawDataHeader = array();
        $RawDataRows = array();
        #print_r($RawData);
        #$validFieldsNum = count($validFields);
        foreach($RawData as $rowIndex => $dataRow) {
            // skip headers
            if($rowIndex == 0) {
                continue;
            }
            // skip empty rows
            if(count($dataRow) <= 1) {
                unset($RawData[$rowIndex]);
                continue;
            }
            #print_r($RawDataHeader);
            /* we take rows from [0] = > value to [website] = base */
            if($rowIndex > 0) {
                foreach($dataRow as $rowIndex => $dataRowNew) {
                    $RawDataRows[$rowCount][$RawDataHeader[$rowIndex]] = $dataRowNew;
                }
            }
            #$RawDatafix[$dataRow] = $dataRow;
            // unset invalid fields from data row
            #foreach ($dataRow as $fieldIndex => $fieldValue) {
            #if (isset($invalidFields[$fieldIndex])) {
            #unset($RawData[$rowIndex][$fieldIndex]);
            #}
            #}
            // check if number of fields in row match with number of valid fields
            #if (count($RawData[$rowIndex]) != $validFieldsNum) {
            # throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file format.'));
            #}
            $rowCount++;
        }
        return $RawDataRows;
    }

    /**
     * Compose stores cache
     *
     * This cache is used to quickly retrieve store ID when handling locale-specific tax rate titles
     *
     * @param string[] $validFields list of valid CSV file fields
     *
     * @return array
     */
    protected function _composeStoreCache($validFields)
    {
        $storesCache       = [];
        $requiredFieldsNum = count($this->getRequiredCsvFields());
        $validFieldsNum    = count($validFields);
        // title related fields located right after required fields
        for($index = $requiredFieldsNum; $index < $validFieldsNum; $index++) {
            foreach($this->publicStores as $store) {
                $storeCode = $validFields[$index];
                if($storeCode === $store->getCode()) {
                    $storesCache[$index] = $store->getId();
                }
            }
        }
        return $storesCache;
    }

    /**
     * Check if public store with specified code still exists
     *
     * @param string $storeCode
     *
     * @return boolean
     */
    protected function _isStoreCodeValid($storeCode)
    {
        $isStoreCodeValid = false;
        foreach($this->publicStores as $store) {
            if($storeCode === $store->getCode()) {
                $isStoreCodeValid = true;
                break;
            }
        }
        return $isStoreCodeValid;
    }

    public function createProduct($store_id, $sku_key)
    {
        try {
        
            $productTypes = $this->_getProductTypes();
            $product = $this->productRepository->get($sku_key);
            
        } catch(\Exception $e) {
            /*$product = $this->objectManager->create('Magento\Catalog\Model\Product')
                                           ->setSku($sku_key)//'sku', $product_id); //'I-oe_productstandin';
                                           ->setStoreId($store_id)
                                           ->setName("Imported product")
                                           ->setTypeId($productTypes['simple'])//use to be virtual needs to be simple or shipments are ignored
                                           ->setAttributeSetId(4)
                                           ->setStatus(1)
                                           ->setTaxClassId(2)//makes products taxable as they all are
                                           ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE)
                                           ->setPrice(0)
                                           ->setStockData(array(
                                                                'use_config_manage_stock' => 0, //'Use config settings' checkbox
                                                                'manage_stock' => 0, //manage stock
                                                                'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
                                                                'max_sale_qty' => 2, //Maximum Qty Allowed in Shopping Cart
                                                                'is_in_stock' => 1, //Stock Availability
                                                                )
                                                            );
            try {
                $product->save();
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Can't save standin product: " . $e->getMessage()), $e);
            }*/
        }

        return $product;
    }

    protected function _getProductTypes()
    {

        $productTypes = array();
        $options      = $this->objectManager->get('Magento\Catalog\Model\Product\Type')->getOptionArray();

        foreach($options as $k => $v) {
            $productTypes[$k] = $k;
        }

        return $productTypes;
    }

    public function processOrder(&$orderData, $importData, $params)
    {

        try {
            
            $resource   = $this->objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            
            $products_ordered = explode('|', $importData['products_ordered']);
            //payment
            #$this->_processQuote($orderData);
            $this->_processQuote($orderData, $products_ordered, $importData, $params);
            
            if(!empty($orderData['payment'])) {
                $this->createPayment($orderData);
            }

            try {
                $order1 = $this->createOrder($orderData, $importData['order_id']);
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR SAVING ORDER: " . $e->getMessage()), $e);
                #Mage::log(sprintf('Order #'.$importData['order_id'].' saving error: %s', $e->getMessage()), null,'ce_order_import_errors.log');
            }

            #this is needed to not have orders repeat themselves. this is when you have items from previous order as part of new order
            #Mage::getSingleton('adminhtml/session_quote')->clear();
            $this->objectManager->get('\Magento\Backend\Model\Session\Quote')->clearStorage();

            if(isset($importData['created_at'])) {
                $dateTime  = strtotime($importData['created_at']);
                $orderDate = date("Y-m-d H:i:s", $dateTime);
                $order1->setCreatedAt($orderDate);
                $order1->setUpdatedAt($orderDate);
                $order1->setOrderCreatedAt($orderDate);
            }
            //Adding invoice/shipment creation
            /*
            if(method_exists($order1, 'getId')) {
                $this->updateOrderCreationTime($importData, $connection, $resource, $order1);
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Order #' . $importData['order_id'] . ' ERROR SAVING ORDER SKIPPING ROW')
                );
            }
            */
            //invoice
            if($params['create_invoice'] == "true") {
                if($importData['order_status'] == "processing" || $importData['order_status'] == "Processing" || $importData['order_status'] == "complete" || $importData['order_status'] == "Complete" || $importData['order_status'] == "closed") {
                    if(!$order1->hasInvoices()) {
                        $invoice_id = "";
                        if(isset($importData['invoice_id'])) {
                            $invoice_id = $importData['invoice_id'];
                        }
                        $this->createInvoice($order1, $invoice_id, $products_ordered, $importData, $params);
                    } else {
                        if(isset($importData['invoice_id'])) {
                            foreach ($order1->getInvoiceCollection() as $invoice) {
                                $invoice->setIncrementId($importData['invoice_id']);
                            }
                        }
                    }
                }
            }
            //shipment
            if($params['create_shipment'] == "true") {
                if($importData['order_status'] == "complete" || $importData['order_status'] == "Complete" || $importData['order_status'] == "closed") {
                    $this->createShipment($order1, $importData);
                }
            }
            //update creation time
            #if(isset($importData['created_at'])) {
                #$this->updateOrderCreationTime($importData, $connection, $resource, $order1);
            #}

            //invoice dates
            #if(isset($importData['created_at']) && $params['create_invoice'] == "true") {
                #$this->updateInvoiceDates($importData, $connection, $resource, $order1);
            #}

            //shipping dates
            #if(isset($importData['created_at']) && $params['create_shipment'] == "true") {
                #$this->updateShippingDates($importData, $connection, $resource, $order1);
            #}
            if(isset($importData['order_status'])) {
                $orderStatuses = array(
                                  'new' => \Magento\Sales\Model\Order::STATE_NEW,
                                  'processing' => \Magento\Sales\Model\Order::STATE_PROCESSING,
                                  'complete' => \Magento\Sales\Model\Order::STATE_COMPLETE,
                                  'closed' => \Magento\Sales\Model\Order::STATE_CLOSED,
                                  'holded' => \Magento\Sales\Model\Order::STATE_HOLDED
                                );
                if(isset($orderStatuses[$importData['order_status']])){
                    $order1->setStatus($orderStatuses[strtolower($importData['order_status'])]);
                }
                if(strtolower($importData['order_status']) == "canceled") {
                     $order1->cancel()->save();   
                }
            }
            //set as complete
            #if($importData['order_status'] == "complete" || $importData['order_status'] == "Complete") {
                #$this->setOrderAsComplete($connection, $resource, $order1, $importData);
            #} else {
                #$this->setAllOtherOrderStatus($connection, $resource, $order1, $importData);
            #}

            $key                     = 'I-oe_productstandin';
            $skusForOrder            = array();
            $additionalOptions       = array();
            $qouteproductcounter     = 1;
            $orderItemproductcounter = 0;
            
            foreach($order1->getAllItems() as $orderItem) {
                unset($options);
                if($qouteproductcounter == 1) {
                    $final_key = $key;
                } else {
                    $final_key = $key . $qouteproductcounter;
                }

                if($orderItem->getProduct()->getSku() == $final_key) {

                    if(isset($products_ordered[$orderItemproductcounter])) {
                        $origSku = array_shift($skusForOrder);
                        $partsforconfigurable = explode('^', $products_ordered[$orderItemproductcounter]);
                        if(isset($partsforconfigurable[2]) && $partsforconfigurable[2] != "") {
                            $configurable_parts = explode(':', $partsforconfigurable[0]);
                            $productPriceUpdate = $partsforconfigurable[1];
                        } else {
                            $configurable_parts = explode(':', $products_ordered[$orderItemproductcounter]);
                            $productPriceUpdate = $configurable_parts[2];
                        }
                        if(isset($configurable_parts[2])) { $imported_product_type = $configurable_parts[2]; } else { $imported_product_type = ""; }
    
                        if($imported_product_type == "simple" || $imported_product_type == "configurable") {
    
                            $options = $orderItem->getProductOptions();
                            $i       = 3;
                            while(isset($configurable_parts[$i])) {
                                $optionnum                       = $i - 2;
                                $options['additional_options'][] = array(
                                    'label' => "Option " . $optionnum,
                                    'value' => $configurable_parts[$i]
                                );
                                $i++;
                            }
                        } else if($imported_product_type == "bundle") {
                            //MC120SA:1.0000:bundle:MC120SACPE~1^299^COUPE^MC120SA-12~1^0^12"
                            $products_ordered_bundle = explode('^', $products_ordered[$orderItemproductcounter]);
                            $productPriceUpdate      = $products_ordered_bundle[1];
                            $options                 = $orderItem->getProductOptions();
                            $iBundle                 = 0;
                            $arrayCount              = 0;
                            while(isset($products_ordered_bundle[$iBundle])) {
    
                                $bundleData_parts = explode(':', $products_ordered_bundle[$iBundle]);
                                if($iBundle >= 3) {
                                    $i = 0; //grabs second or 3rd
                                } else {
                                    $i = 3;
                                }
                                while(isset($bundleData_parts[$i])) {
                                    $optionnum                                  = $arrayCount + 1;
                                    $finalQty                                   = explode('~', $bundleData_parts[$i]);
                                    $options['additional_options'][$arrayCount] = array(
                                        'label' => "Bundle " . $optionnum,
                                        'value' => $finalQty[1] . " X " . $finalQty[0]
                                    );
                                    $i++;
                                    $arrayCount++;
                                }
                                $iBundle = $iBundle + 3;
                            }
                        } else {
                            $options = $orderItem->getProductOptions();
                            if($origSku != "") {
                                $options['additional_options'] = array();
                                /*
                                $options['additional_options'] = array(array(
                                    'label' => "Sku",
                                    'value' => $origSku
                                ));
                                */
                            }
                        }
                        $orderItem->setPrice($productPriceUpdate);
                        $orderItem->setProductOptions($options);
                        $orderItemproductcounter++;
                    }
                }
                
                //support for applied_rule_ids
                if(isset($importData['applied_rule_ids'])) {
                    $orderItem->setAppliedRuleIds($importData['applied_rule_ids']);
                }
                //support for applied_rule_ids
                $orderItem->save();

                //$option = $quoteItem->getOptionByCode('additional_options');
                //$option->save();
                $qouteproductcounter++;
            }

            $productcounters = 1;

            //sync product names/prices, and update shipping
            /*
            foreach($products_ordered as $data) {

                $parts = explode(':', $data);
                $this->updateProductPrice($data, $connection, $resource, $order1, $importData, $select_qry, $newrowItemId, $item_id, $e, $productcounters, $params);
                $this->updateProductItemName($data, $importData, $connection, $resource, $order1, $e, $select_qry, $productcounters, $params);
                $this->updateShippingTotal($importData, $order1, $connection, $resource, $parts, $e, $select_qry, $newrowItemId, $item_id, $params);
                $productcounters++;
            }
            */
            //Adding invoice/shipment creation
            $productItemscounter = 1;
            foreach($products_ordered as $data) {
                $importData = $this->processProductOrdered($data, $importData, $connection, $resource, $order1, $productItemscounter, $params);
                $productItemscounter++;
            }
            $this->updateOrderCommentsHistory($importData, $order1, $connection, $resource);
            $this->_getSession()->clearStorage();
            $this->objectManager->get(\Magento\Framework\Registry::class)->unregister('rule_data'); //Mage::unregister('rule_data');
            
            #Mage::log('Order Successfull', null, 'ce_order_import_errors.log');

        } catch(\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " Saving Error:" . $e->getMessage()), $e);
            #Mage::log(sprintf('Order #' . $importData['order_id'] . ' saving error: %s', $e->getMessage()), null, 'ce_order_import_errors.log');

        }
        #exit;
    }

    public function handleProduct(&$partsdata, &$add_products_array, $importData, &$product, $productcounter, $fullSKU)
    {
        $parts = explode(':', $partsdata);

        if(method_exists($product, 'getTypeId')) {
            
            $stockItem = $this->objectManager->get('Magento\CatalogInventory\Model\StockRegistry')->getStockItem($product->getId());

            $super_attribute_order_values = array();
            $attributes                   = $values = $bundle_option_order_values = $bundle_option_qty_values = array();

            if(isset($parts[1])) {
                $part_qty = $parts[1];
            } else {
                $part_qty = "1";
            }
            
            if(isset($parts[2])) {
                $part_type = $parts[2];
            } else {
                $part_type = "";
            }

            if(isset($parts[3])) {
                $bundle_opt = $parts[3];
            } else {
                $bundle_opt = "";
            }
            if($stockItem->getIsInStock() || $stockItem->getManageStock() == 0) {
            #if($stockItem->getIsInStock() && $stockItem->getManageStock() == 1 && $stockItem->getQty() != "0.0000" || $stockItem->getManageStock() == 0) {

                //if ($productstockItem->getStockItem()->getIsInStock() && $productstockItem->getStockItem()->getManageStock() == 1 && $stockItem->getQty() != "0.0000" || $productstockItem->getStockItem()->getManageStock() == 0 || ($part_type == "bundle" && $productstockItem->getStockItem()->getIsInStock()) || ($part_type == "configurable" && $productstockItem->getStockItem()->getIsInStock())) {

                if($product->getTypeId() == "simple" && $part_type == "simple") {

                    $options                     = $this->objectManager->get('Magento\Catalog\Model\Product\Option')->getProductOptionCollection($product)->getItems();
                    $simple_custom_option_values = array();
                    $i                           = 1;

                    $partsforsimple = explode('^', $partsdata);
                    if(isset($partsforsimple[1]) && $partsforsimple[1] != "") {
                        $simple_parts = explode(':', $partsforsimple[0]);
                    } else {
                        $simple_parts = $parts;
                    }

                    foreach($options as $option) {

                        $itemtrue = false; //this is here because sometimes there are simple w/ custom options and 4 choices but only 3 in the export
                        if($option->getType() == "drop_down" || $option->getType() == "radio" || $option->getType() == "checkbox" || $option->getType() == "multiple") {
                            $values = $option->getValues();
                            foreach($values as $value) {
                                #echo "TITLECSV: " . $simple_parts[$i + 2] . " - " . $value->getTitle() . "<br/>";
                                if($value->getTitle() == $simple_parts[$i + 2]) {
                                    $simple_custom_option_values[$option->getId()] = $value->getId();
                                    $itemtrue                                      = true;
                                }
                            }
                        } else if($option->getType() == "field" || $option->getType() == "area" || $option->getType() == "date" || $option->getType() == "time" || $option->getType() == "date_time") {
                            $simple_custom_option_values[$option->getId()] = $simple_parts[$i + 2];
                            $itemtrue                                      = true;
                        } else if($option->getType() == "file") {
                            $simple_custom_option_values[$option->getId()] = "";
                            #$itemtrue = true;
                        }
                        if($itemtrue == true) {
                            $i = $i + 1;
                        }
                    }
                    $add_products_array[$fullSKU]['options']   = $simple_custom_option_values;
                    $add_products_array[$fullSKU]['productID'] = $product->getId();
                }

                if($product->getTypeId() == "configurable" && $part_type == "configurable") {

                    $partsforconfigurable = explode('^', $partsdata);

                    if(isset($partsforconfigurable[2]) && $partsforconfigurable[2] != "") {
                        $configurable_parts = explode(':', $partsforconfigurable[0]);
                    } else {
                        $configurable_parts = $parts;
                    }

                    $config = $product->getTypeInstance(true);

                    $super_attribute_order_values = $this->_array_replace($super_attribute_order_values, $this->copyConfigurableAttributes($config, $product, $configurable_parts));

                    //fixed for missing QTY
                    $add_products_array[$fullSKU]['super_attribute'] = $super_attribute_order_values;
                    $add_products_array[$fullSKU]['productID']       = $product->getId();
                } else {

                    if($stockItem->getQty() > 0 || $product->getTypeId() == "bundle") {

                        if($product->getTypeId() == "bundle" && $part_type == "bundle") {

                            $this->handleBundle($product, $bundle_opt, $bundle_option_order_values, $bundle_option_qty_values, $add_products_array);
                        }

                        if($part_type == "configurable") {

                            if($productcounter == 1) {

                                $key = 'I-oe_productstandin';

                                $product = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $key);
                                #$product = $this->createProduct($importData['store_id'], "I-oe_productstandin");
                                if(method_exists($product, 'getTypeId')) {
                                    $add_products_array[$product->getId()]['qty'] = $part_qty;
                                } else {
                                    $product                                      = $this->createProduct($importData['store_id'], $key);
                                    $add_products_array[$product->getId()]['qty'] = $part_qty;
                                }
                                #$add_products_array[$product->getId()]['qty'] = $parts[1];

                                $this->importedSkus[] = $parts[0];
                            } else if($productcounter > 1) {
                                $keyforinsert                                 = "I-oe_productstandin" . $productcounter;
                                $product                                      = $this->createProduct($importData['store_id'], $keyforinsert);
                                $add_products_array[$product->getId()]['qty'] = $part_qty;
                                $this->importedSkus[]                         = $parts[0];
                            }
                        }

                        if($part_type != "bundle" && $part_type != "configurable" && $part_type != "simple") {
                            if(method_exists($product, 'getTypeId')) {
                                //$add_products_array[$product->getId()]['qty'] = $parts[1];
                                $add_products_array[$fullSKU]['qty']       = $part_qty;
                                $add_products_array[$fullSKU]['productID'] = $product->getId();
                            }
                        }
                    } else {
                        #echo "Order #" . $importData['order_id'] . " WARNING PRODUCT OUT OF STOCK BUT STILL IMPORTING ORDER: [sku]" . $parts[0];
                        #Mage::log(sprintf('Order #' . $importData['order_id'] . ' PRODUCT OUT OF STOCK'), null, 'ce_order_import_errors.log');

                        if($part_type != "bundle" && $part_type != "configurable" && $part_type != "simple" || $fullSKU != $product->getSku()) {
                            if(method_exists($product, 'getTypeId')) {
                                //$add_products_array[$product->getId()]['qty'] = $parts[1];
                                $add_products_array[$fullSKU]['qty']       = $part_qty;
                                $add_products_array[$fullSKU]['productID'] = $product->getId();
                            }
                        }
                    }
                }
            } else {

                /*
                if ($product->getTypeId() == "configurable" && $part_type == "configurable") {

                    $config = $product->getTypeInstance(true);
                    #$configattributearraydata = $this->copyConfigurableAttributes($config, $product, $parts);
                    #print_r($config->getConfigurableAttributesAsArray($product));
                    #$super_attribute_order_values = array_merge($configattributearraydata, $super_attribute_order_values);
                    $super_attribute_order_values = $this->_array_replace($super_attribute_order_values, $this->copyConfigurableAttributes($config, $product, $parts));
                    $add_products_array[$product->getId()]['super_attribute'] = $super_attribute_order_values;

                }

                if ($product->getTypeId() == "simple" && $part_type == "simple") {
                    $options = $product->getOptions();
                    //$add_products_array[$product->getId()]['super_attribute'] = $super_attribute_order_values;
                }
                */

                if($productcounter == 1) {

                    $key = 'I-oe_productstandin';

                    $product = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $key);
                    if(method_exists($product, 'getTypeId')) {
                        //$add_products_array[$product->getId()]['qty'] = $parts[1];
                        $add_products_array[$fullSKU]['qty']       = $part_qty;
                        $add_products_array[$fullSKU]['productID'] = $product->getId();
                    } else {
                        $product = $this->createProduct($importData['store_id'], $key);
                        //$add_products_array[$product->getId()]['qty'] = $parts[1];
                        $add_products_array[$fullSKU]['qty']       = $part_qty;
                        $add_products_array[$fullSKU]['productID'] = $product->getId();
                    }

                    $this->importedSkus[] = $parts[0];
                } else if($productcounter > 1) {
                    $keyforinsert = "I-oe_productstandin" . $productcounter;
                    $product      = $this->createProduct($importData['store_id'], $keyforinsert);
                    //$add_products_array[$product->getId()]['qty'] = $parts[1];
                    $add_products_array[$fullSKU]['qty']       = $part_qty;
                    $add_products_array[$fullSKU]['productID'] = $product->getId();
                    $this->importedSkus[]                      = $parts[0];
                }
            }
        } else {

            throw new \Magento\Framework\Exception\LocalizedException(
                __("Order #" . $importData['order_id'] . " ERROR PRODUCT DOESN'T EXIST: [sku]" . $parts[0])
            );
            #Mage::log(sprintf('Order #'.$importData['order_id'].' ERROR PRODUCT DOES NOT EXIST: [sku]' . $parts[0], ''), null, 'ce_order_import_errors.log');

        }
    }

    public function handleBundle($product, $bundle_opt, $bundle_option_order_values, $bundle_option_qty_values, &$add_products_array)

    {

        $optionModel = $this->objectManager->get('bundle/option')->getResourceCollection()->setProductIdFilter($product->getId());

        foreach($optionModel as $eachOption) {

            $selectionModel = $this->objectManager->get('bundle/selection')->setOptionId($eachOption->getData('option_id'))->getResourceCollection();

            foreach($selectionModel as $eachselectionOption) {

                if($eachselectionOption->getData('option_id') == $eachOption->getData('option_id')) {

                    #echo "SKU: " . $eachselectionOption->getData('sku');
                    #echo "ID: " . $eachselectionOption->getData('option_id') . " --- " .  $eachOption->getData('option_id');

                    /*
                    if($eachselectionOption->getData('sku') == "396115904") {
                        $bundle_option_order_values[$eachselectionOption->getData('option_id')] = $eachselectionOption->getData('selection_id');
                    }
                    */
                    //LIABG:1:bundle:amdphenom~1^500gb5400~1

                    $products_ordered_bundle = explode('^', $bundle_opt);

                    foreach($products_ordered_bundle as $bundle_data) {
                        $bundle_parts = explode('~', $bundle_data);

                        if($eachselectionOption->getData('sku') == $bundle_parts[0]) {
                            $bundle_option_order_values[$eachselectionOption->getData('option_id')] = $eachselectionOption->getData('selection_id');
                            $bundle_option_qty_values[$eachselectionOption->getData('option_id')]   = $bundle_parts[1];
                        }
                    }
                }
            }
        }

        $add_products_array[$product->getId()]['bundle_option']     = $bundle_option_order_values;
        $add_products_array[$product->getId()]['bundle_option_qty'] = $bundle_option_qty_values;
    }

    public function copyConfigurableAttributes($config, $product, $parts)
    {

        $super_attribute_order_values = array();

        foreach($config->getConfigurableAttributesAsArray($product) as $attributes) {

            foreach($attributes["values"] as $values) {

                for($i = 3; $i <= 5; $i++) {

                    if(isset($parts[$i])) {

                        if($parts[$i] == $values["label"]) {

                            $super_attribute_order_values[$attributes["attribute_id"]] = $values["value_index"];
                        }
                    }
                }
            }
        }

        return $super_attribute_order_values;
    }

    public function copyCustomOptions($config, $product, $parts)
    {
        $super_attribute_order_values = array();

        foreach($config->getConfigurableAttributesAsArray($product) as $attributes) {
            foreach($attributes["values"] as $values) {
                for($i = 3; $i <= 5; $i++) {

                    if(isset($parts[$i])) {

                        if($parts[$i] == $values["label"]) {

                            $super_attribute_order_values[$attributes["attribute_id"]] = $values["value_index"];
                        }
                    }
                }
            }
        }

        return $super_attribute_order_values;
    }

    public function assembleOrderData($customerId, $row, $add_products_array, $customer, $final_shipping)
    {

        if(strtolower($row->getEmailConfirmation()) == "yes") { $csv_send_email = 1; } else { $csv_send_email = 0; }
        if($row->getOrderComments() != "") { $order_comments = $row->getOrderComments();  } else {  $order_comments = "API ORDER"; }
        if($row->getOrderPoNumber()) {  $orderDataId = $row->getOrderPoNumber();  } else { $orderDataId = "";  }
        if($row->getIsGuest()) {  $is_guest = $row->getIsGuest(); } else {  $is_guest = 0; }
        
        if($row->getIsGuest() == 1) {
            $customer_group_id = \Magento\Customer\Model\GroupManagement::NOT_LOGGED_IN_ID;
            //if billing address is incomplete lets use shipping
            if($row->getBillingFirstname() !="" && $row->getBillingLastname() !="" && $row->getBillingCity() !="" && $row->getBillingCountry() !="" && $row->getBillingPostcode() !="") {
                
                $street_b = array("0" => ($row->getBillingStreetFull()) ? $row->getBillingStreetFull() : $row->getBillingStreet(),
                                  "1" => ($row->getBillingStreet2()) ? $row->getBillingStreet2() : "",
                                  "2" => ($row->getBillingStreet3()) ? $row->getBillingStreet3() : "",
                                  "3" => ($row->getBillingStreet4()) ? $row->getBillingStreet4() : "");
                
                $billing_region = $row->getBillingRegion();
                if($billing_region !="") {
                    #$regions = $this->objectManager->get('\Magento\Directory\Model\ResourceModel\Region\Collection')->addRegionNameFilter($billing_region)->load();
                    $regions = $this->regionCollection->addRegionNameFilter($billing_region)->load();
                    if($regions) {
                        foreach($regions as $region) {
                            $billing_region = $region->getId();
                        }
                    }
                }
                $billing_address_final = array(
                    'prefix'     => $row->getBillingPrefix(),
                    'firstname'  => $row->getBillingFirstname(),
                    'middlename' => $row->getBillingMiddlename(),
                    'lastname'   => $row->getBillingLastname(),
                    'suffix'     => $row->getBillingSuffix(),
                    'street'     => $street_b,
                    'city'       => $row->getBillingCity(),
                    'region'     => $billing_region,
                    'country_id' => $row->getBillingCountry(),
                    'postcode'   => $row->getBillingPostcode(),
                    'telephone'  => $row->getBillingTelephone(),
                    'company'    => $row->getBillingCompany(),
                    'fax'        => $row->getBillingFax()
                );
            } else {
            
                $street_s = array("0" => ($row->getShippingStreetFull()) ? $row->getShippingStreetFull() : $row->getShippingStreet(),
                                  "1" => ($row->getShippingStreet2()) ? $row->getShippingStreet2() : "",
                                  "2" => ($row->getShippingStreet3()) ? $row->getShippingStreet3() : "",
                                  "3" => ($row->getShippingStreet4()) ? $row->getShippingStreet4() : "");
                                  
                $shipping_region = $row->getShippingRegion();
                if($shipping_region !="") {
                    #$regions = $this->objectManager->get('\Magento\Directory\Model\ResourceModel\Region\Collection')->addRegionNameFilter($shipping_region)->load();
                    $regions = $this->regionCollection->addRegionNameFilter($shipping_region)->load();
                    if($regions) {
                        foreach($regions as $region_s) {
                            $shipping_region = $region_s->getId();
                        }
                    }   
                }         

                $billing_address_final = array(
                    'prefix'     => $row->getShippingPrefix(),
                    'firstname'  => $row->getShippingFirstname(),
                    'middlename' => $row->getShippingMiddlename(),
                    'lastname'   => $row->getShippingLastname(),
                    'suffix'     => $row->getShippingSuffix(),
                    'street'     => $street_s,
                    'city'       => $row->getShippingCity(),
                    'region'     => $shipping_region,
                    'country_id' => $row->getShippingCountry(),
                    'postcode'   => $row->getShippingPostcode(),
                    'telephone'  => $row->getShippingTelephone(),
                    'company'    => $row->getShippingCompany(),
                    'fax'        => $row->getShippingFax()
                );

            }
        } else {
            $customer_group_id = $customer->getGroupId();
            if(!empty($customer->getDefaultBillingAddress())) {
                $billing_address_final = array(
                    'customer_address_id' => $customer->getDefaultBillingAddress()->getEntityId(),
                    'prefix'              => $customer->getDefaultBillingAddress()->getPrefix(),
                    'firstname'           => $customer->getDefaultBillingAddress()->getFirstname(),
                    'middlename'          => $customer->getDefaultBillingAddress()->getMiddlename(),
                    'lastname'            => $customer->getDefaultBillingAddress()->getLastname(),
                    'suffix'              => $customer->getDefaultBillingAddress()->getSuffix(),
                    'company'             => $customer->getDefaultBillingAddress()->getCompany(),
                    'street'              => $customer->getDefaultBillingAddress()->getStreet(),
                    'city'                => $customer->getDefaultBillingAddress()->getCity(),
                    'country_id'          => $customer->getDefaultBillingAddress()->getCountryId(),
                    'region'              => $customer->getDefaultBillingAddress()->getRegion(),
                    'region_id'           => $customer->getDefaultBillingAddress()->getRegionId(),
                    'postcode'            => $customer->getDefaultBillingAddress()->getPostcode(),
                    'telephone'           => $customer->getDefaultBillingAddress()->getTelephone(),
                    'fax'                 => $customer->getDefaultBillingAddress()->getFax(),
                );
            } else {
                $customerAddress = $this->objectManager->get('Magento\Customer\Model\Address');
                $customerAddress->load($customer->getDefaultBilling());
                $billing_address_final = array(
                    'customer_address_id' => $customerAddress->getEntityId(),
                    'prefix'              => $customerAddress->getPrefix(),
                    'firstname'           => $customerAddress->getFirstname(),
                    'middlename'          => $customerAddress->getMiddlename(),
                    'lastname'            => $customerAddress->getLastname(),
                    'suffix'              => $customerAddress->getSuffix(),
                    'company'             => $customerAddress->getCompany(),
                    'street'              => $customerAddress->getStreet(),
                    'city'                => $customerAddress->getCity(),
                    'country_id'          => $customerAddress->getCountryId(),
                    'region'              => $customerAddress->getRegion(),
                    'region_id'           => $customerAddress->getRegionId(),
                    'postcode'            => $customerAddress->getPostcode(),
                    'telephone'           => $customerAddress->getTelephone(),
                    'fax'                 => $customerAddress->getFax(),
                );
            }
        }
    
        $orderData = array(
            'session' => array(
                'customer_id' => $customerId,
                #'store_id'      => $customer->getStoreId(),
                'store_id'    => $row->getStoreId(),
            ),
            'payment'      => array(
                'method'    => $row->getPaymentMethod(),
                'method_additional_data'    => ($row->getPaymentMethodAdditionalData()) ? $row->getPaymentMethodAdditionalData() : '',
                'po_number' => $orderDataId,
            ),
            // 123456 denotes the product's ID value
            'add_products' => $add_products_array,
            'order'        => array(
                'currency'          => 'USD',
                'customer_is_guest' => $is_guest,
                'account'           => array(
                    'group_id' => $customer_group_id,
                    #'email' => (string)$customer->getEmail(),
                    'firstname'    => ($row->getFirstname()) ? $row->getFirstname() : '',
                    'lastname'    => ($row->getLastname()) ? $row->getLastname() : '',
                    'email'    => $row->getEmail(),
                ),
                'comment'           => array('customer_note' => $order_comments),
                'send_confirmation' => $csv_send_email,
                'shipping_method'   => $row->getShippingMethod(),
                'billing_address'   => $billing_address_final,
                'shipping_address'  => array(
                    'customer_address_id' => $final_shipping['entity_id'],
                    'prefix'              => $final_shipping['prefix'],
                    'firstname'           => $final_shipping['firstname'],
                    'middlename'          => $final_shipping['middlename'],
                    'lastname'            => $final_shipping['lastname'],
                    'suffix'              => $final_shipping['suffix'],
                    'company'             => $final_shipping['company'],
                    'street'              => $final_shipping['street'],
                    'city'                => $final_shipping['city'],
                    'country_id'          => $final_shipping['countryid'],
                    'region'              => $final_shipping['region'],
                    'region_id'           => $final_shipping['regionid'],
                    'postcode'            => $final_shipping['postcode'],
                    'telephone'           => $final_shipping['telephone'],
                    'fax'                 => $final_shipping['fax']
                ),
            ),
        );

        return $orderData;
    }

    public function createShipping($customer, $row)
    {
        
      if($row->getIsGuest() == 1) {
            //if billing address is incomplete lets use shipping
            if($row->getShippingFirstname() !="" && $row->getShippingLastname() !="" && $row->getShippingCity() !="" && $row->getShippingRegion() !="" && $row->getShippingCountry() !="" && $row->getShippingPostcode() !="") {
            
                $street_s = array("0" => ($row->getShippingStreetFull()) ? $row->getShippingStreetFull() : $row->getShippingStreet(),
                                  "1" => ($row->getShippingStreet2()) ? $row->getShippingStreet2() : "",
                                  "2" => ($row->getShippingStreet3()) ? $row->getShippingStreet3() : "",
                                  "3" => ($row->getShippingStreet4()) ? $row->getShippingStreet4() : "");
    
                $regionShip = $row->getShippingRegion();
                if($regionShip != "") {
                    #$shippingRegions = $this->objectManager->get('\Magento\Directory\Model\ResourceModel\Region\Collection')->addRegionNameFilter($regionShip)->load();
                    $shippingRegions = $this->regionCollection->addRegionNameFilter($regionShip)->load();
                    if($shippingRegions) {
                        foreach($shippingRegions as $regions) { $regionShip = $regions->getId(); }
                    }
                }
    
                $final_shipping = array(
                    'entity_id'  => '',
                    'prefix'     => $row->getShippingPrefix(),
                    'firstname'  => $row->getShippingFirstname(),
                    'middlename' => $row->getShippingMiddlename(),
                    'lastname'   => $row->getShippingLastname(),
                    'suffix'     => $row->getShippingSuffix(),
                    'street'     => $street_s,
                    'city'       => $row->getShippingCity(),
                    'region'     => $regionShip,
                    'regionid'   => $regionShip,
                    'countryid'  => $row->getShippingCountry(),
                    'postcode'   => $row->getShippingPostcode(),
                    'telephone'  => $row->getShippingTelephone(),
                    'company'    => $row->getShippingCompany(),
                    'fax'        => $row->getShippingFax(),
                );
            
            } else {
            
                $street_b = array("0" => ($row->getBillingStreetFull()) ? $row->getBillingStreetFull() : $row->getBillingStreet(),
                                  "1" => ($row->getBillingStreet2()) ? $row->getBillingStreet2() : "",
                                  "2" => ($row->getBillingStreet3()) ? $row->getBillingStreet3() : "",
                                  "3" => ($row->getBillingStreet4()) ? $row->getBillingStreet4() : "");
                
                $region         = $row->getBillingRegion();
                if($region != "") {
                    #$regions = $this->objectManager->get('\Magento\Directory\Model\ResourceModel\Region\Collection')->addRegionNameFilter($region)->load();
                    $regions = $this->regionCollection->addRegionNameFilter($region)->load();
                    if($regions) {
                        foreach($regions as $regionModel) { $region = $regionModel->getId(); }
                    }
                }
                              
                $final_shipping = array(
                    'entity_id'  => '',
                    'prefix'     => $row->getBillingPrefix(),
                    'firstname'  => $row->getBillingFirstname(),
                    'middlename' => $row->getBillingMiddlename(),
                    'lastname'   => $row->getBillingLastname(),
                    'suffix'     => $row->getBillingSuffix(),
                    'street'     => $street_b,
                    'city'       => $row->getBillingCity(),
                    'region'     => $region,
                    'regionid'   => $region,
                    'countryid'  => $row->getBillingCountry(),
                    'postcode'   => $row->getBillingPostcode(),
                    'telephone'  => $row->getBillingTelephone(),
                    'company'    => $row->getBillingCompany(),
                    'fax'        => $row->getBillingFax()
                );
            }
            
            return $final_shipping;
                
        } else {
            $customerAddress = $this->objectManager->get('Magento\Customer\Model\Address');
            if(!empty($customer->getDefaultShipping())) {
                $customerAddress->load($customer->getDefaultShipping());
                $final_shipping = array(
                    'entity_id' => $customerAddress->getEntityId(),
                    'prefix' => $customerAddress->getPrefix(),
                    'firstname' => $customerAddress->getFirstname(),
                    'middlename' => $customerAddress->getMiddlename(),
                    'lastname' => $customerAddress->getLastname(),
                    'suffix' => $customerAddress->getSuffix(),
                    'company' => $customerAddress->getCompany(),
                    'street' => $customerAddress->getStreet(),
                    'city' => $customerAddress->getCity(),
                    'countryid' => $customerAddress->getCountryId(),
                    'region' => $customerAddress->getRegion(),
                    'regionid' => $customerAddress->getRegionId(),
                    'postcode' => $customerAddress->getPostcode(),
                    'telephone' => $customerAddress->getTelephone(),
                    'fax' => $customerAddress->getFax()
                );
                return $final_shipping;
            } else if(!empty($customer->getDefaultBilling())) {
    
                $customerAddress->load($customer->getDefaultBilling());
                $final_shipping = array(
                    'entity_id' => $customerAddress->getEntityId(),
                    'prefix' => $customerAddress->getPrefix(),
                    'firstname' => $customerAddress->getFirstname(),
                    'middlename' => $customerAddress->getMiddlename(),
                    'lastname' => $customerAddress->getLastname(),
                    'suffix' => $customerAddress->getSuffix(),
                    'company' => $customerAddress->getCompany(),
                    'street' => $customerAddress->getStreet(),
                    'city' => $customerAddress->getCity(),
                    'countryid' => $customerAddress->getCountryId(),
                    'region' => $customerAddress->getRegion(),
                    'regionid' => $customerAddress->getRegionId(),
                    'postcode' => $customerAddress->getPostcode(),
                    'telephone' => $customerAddress->getTelephone(),
                    'fax' => $customerAddress->getFax()
                );
                return $final_shipping;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __("ERROR: CUSTOMERID[ " . $customer->getId() . " ] - PLEASE UPDATE CUSTOMER TO HAVE A DEFAULT BILLING OR SHIPPING ADDRESS")
                );
            }
        }
    }

    public function createOrder($orderData, $order_id)
    {
        try {
            $order1 = $this->_getOrderCreateModel()
                           ->importPostData($orderData['order'])
                           ->createOrder();
            return $order1;
        } catch(\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $order_id . " Error Saving Order: " . $e->getMessage()), $e);
            #Mage::log(sprintf('Order #' . $importData['order_id'] . ' saving error: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
        }
    }

    public function createPayment($orderData)
    {
        try {
            $payment = $orderData['payment'];
            $this->_getOrderCreateModel()->setPaymentData($payment);
        } catch(\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__("ERROR With Payment: " . $e->getMessage()), $e);
            #Mage::log(sprintf('ERROR With Payment: %s', $e->getMessage()), null, 'csv_order_import_errors.log');
        }
    }

    public function createInvoice($order1, $invoice_id, $products_ordered, $importData, $params)
    {
        try {

            $itemCounter = 0;
            $convertOrder = $this->objectManager->get('Magento\Sales\Model\Convert\Order');
            $invoice      = $convertOrder->toInvoice($order1);
            
            if(isset($importData['created_at'])) {
                $dateTime = strtotime($importData['created_at']);
                $invoiceCreatedAt = date("Y-m-d H:i:s", $dateTime);
                $invoice->setCreatedAt($invoiceCreatedAt);
                $invoice->setUpdatedAt($invoiceCreatedAt);
                $invoice->setOrderCreatedAt($invoiceCreatedAt);
            }
            
            // Loop through order items
            foreach($order1->getAllItems() AS $orderItem) {
                // Check if order item has qty to order or is virtual
                if(!$orderItem->getQtyOrdered() || $orderItem->getIsVirtual()) {
                    continue;
                }
                $qtyOrdered = $orderItem->getQtyOrdered();

                // Create invoice item with qty
                $invoiceItem = $convertOrder->itemToInvoiceItem($orderItem)->setQty($qtyOrdered);
                
                if($params['skip_product_lookup'] == "true") {
                    if(isset($products_ordered[$itemCounter])) {
                        $parts = explode(':', $products_ordered[$itemCounter]);
                        if(isset($parts[2]) && $parts[2] == "configurable" || isset($parts[2]) && $parts[2] == "simple" || isset($parts[2]) && $parts[2] == "bundle") {
                             $partsfornamepricing = explode('^', $products_ordered[$itemCounter]);
                             if(isset($partsfornamepricing[1])) {
                                $rowTotal = $partsfornamepricing[1] * $qtyOrdered;
                                $rowProductPrice = $partsfornamepricing[1];
                             }
                        } else {
                            $rowProductPrice = $parts[2];
                            $rowTotal = $qtyOrdered * $rowProductPrice;
                        }
                        if($params['use_historic_tax'] == "true") {
                            if(isset($importData['tax_percent'])) {
                                $tax_percent = $importData['tax_percent'];
                                $decimalfortaxpercent = $tax_percent / 100;
                            } elseif(isset($importData['tax_amount']) && isset($importData['grand_total'])) {
                                $decimalfortaxpercent = $importData['tax_amount'] / $importData['grand_total'];
                                $tax_percent = round((float)$decimalfortaxpercent * 100 ) . '%';
                            }
                            $tax_amount_for_row_total = $rowTotal * $decimalfortaxpercent;
                            $invoiceItem->setTaxPercent($tax_percent);
                            $invoiceItem->setTaxAmount($tax_amount_for_row_total);
                        }
                        #$orderItem->setPrice($rowProductPrice);
                        $invoiceItem->setPrice($rowProductPrice);
                        $invoiceItem->setRowTotal($rowTotal);
                        $invoiceItem->setBaseRowInvoiced($rowTotal);
                        $itemCounter++;
                    }
                }

                // Add invoice item to invoice
                $invoice->addItem($invoiceItem);
            }
            $invoice->getOrder()->setIsInProcess(true);
            if($invoice_id !="") { $invoice->setIncrementId($invoice_id); }
            $invoice->register();

            $transactionSave = $this->objectManager->get('Magento\Framework\DB\Transaction')
                                                   ->addObject($invoice)
                                                   ->addObject($invoice->getOrder())
                                                   ->save();
        } catch(\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__("Failed to create a invoice: : " . $e->getMessage()), $e);
            #Mage::log(sprintf('failed to create a invoice: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
        }
    }

    public function updateShippingTotal(&$importData, $order1, $connection, $resource, $parts, &$e, &$select_qry, &$newrowItemId, &$item_id, $params)
    {

        #$parts = explode(':', $partsdata);

        $_quote               = $resource->getTableName('quote'); //gives table name with prefix
        $_quote_address       = $resource->getTableName('quote_address');
        $_quote_shipping_rate = $resource->getTableName('quote_shipping_rate');
        $_sales_order         = $resource->getTableName('sales_order');
        $_sales_order_tax     = $resource->getTableName('sales_order_tax');
        $_sales_order_item    = $resource->getTableName('sales_order_item');
        $_sales_order_grid    = $resource->getTableName('sales_order_grid');
        $_sales_invoice       = $resource->getTableName('sales_invoice');
        $_sales_invoice_grid  = $resource->getTableName('sales_invoice_grid');
        $_sales_invoice_item  = $resource->getTableName('sales_invoice_item');
        $_sales_order_payment = $resource->getTableName('sales_order_payment');

        if(isset($importData['subtotal']) && isset($importData['shipping_amount'])) {

            #$customordergrandtotalamt = $importData['subtotal'] + $importData['shipping_amount'];
            if(isset($importData['discount_amount']) && $importData['discount_amount'] != "0.0000" && $importData['discount_amount'] != "0.00") {
                $final_discount_amount                  = str_replace('-', '', $importData['discount_amount']);
                $customordergrandtotalamtbeforediscount = $importData['subtotal'] + $importData['shipping_amount'] + $importData['tax_amount'];
                $customordergrandtotalamt               = $customordergrandtotalamtbeforediscount - $final_discount_amount;
            } else {
                $final_discount_amount    = "0.00";
                $customordergrandtotalamt = $importData['subtotal'] + $importData['shipping_amount'] + $importData['tax_amount'];
            }

            #$custom_order_base_price_w_o_tax = $importData['subtotal'] - $importData['tax_amount'];
            $custom_order_base_price_w_o_tax = $importData['subtotal'];
            $base_shipping_tax_amount        = $importData['shipping_amount'] - $importData['base_shipping_amount'];
            $subtotal_w_o_tax                = $custom_order_base_price_w_o_tax + $importData['base_shipping_amount'];
            $base_tax_amount                 = $importData['tax_amount'];

            try {

                if($params['create_invoice'] == "true") {

                    $connection->query("UPDATE `" . $_sales_order . "` SET base_subtotal = '" . $custom_order_base_price_w_o_tax . "', base_tax_amount = '" . $base_tax_amount . "', tax_amount = '" . $base_tax_amount . "', tax_invoiced = '" . $base_tax_amount . "', base_grand_total = '" . $customordergrandtotalamt . "', base_total_paid = '" . $customordergrandtotalamt . "', total_paid = '" . $customordergrandtotalamt . "', base_total_invoiced = '" . $customordergrandtotalamt . "', total_invoiced = '" . $customordergrandtotalamt . "', subtotal = '" . $custom_order_base_price_w_o_tax . "', base_subtotal_incl_tax = '" . $importData['subtotal'] . "', subtotal_incl_tax = '" . $importData['subtotal'] . "', grand_total = '" . $customordergrandtotalamt . "', base_shipping_amount = '" . $importData['base_shipping_amount'] . "', base_shipping_tax_amount = '" . $base_shipping_tax_amount . "', shipping_tax_amount = '" . $base_shipping_tax_amount . "', shipping_amount = '" . $importData['base_shipping_amount'] . "', shipping_invoiced = '" . $importData['shipping_amount'] . "', base_shipping_invoiced = '" . $importData['base_shipping_amount'] . "', shipping_incl_tax = '" . $importData['shipping_amount'] . "', base_shipping_incl_tax = '" . $importData['shipping_amount'] . "', discount_amount = '" . $final_discount_amount . "', base_discount_amount = '" . $final_discount_amount . "' WHERE entity_id = '" . $order1->getId() . "' AND store_id = '" . $importData['store_id'] . "'");

                    //EXTRA DATA SET FOR PRODUCT/ORDER DATA ON INVOICES
                    $connection->query("UPDATE `" . $_sales_invoice . "` SET base_subtotal = '" . $custom_order_base_price_w_o_tax . "', base_tax_amount = '" . $base_tax_amount . "', tax_amount = '" . $base_tax_amount . "', subtotal = '" . $custom_order_base_price_w_o_tax . "', base_subtotal_incl_tax = '" . $importData['subtotal'] . "', subtotal_incl_tax = '" . $importData['subtotal'] . "', base_grand_total = '" . $customordergrandtotalamt . "', grand_total = '" . $customordergrandtotalamt . "', base_shipping_amount = '" . $importData['base_shipping_amount'] . "', shipping_amount = '" . $importData['base_shipping_amount'] . "', shipping_incl_tax = '" . $importData['shipping_amount'] . "', base_shipping_incl_tax = '" . $importData['shipping_amount'] . "', discount_amount = '" . $final_discount_amount . "', base_discount_amount = '" . $final_discount_amount . "' WHERE order_id = '" . $order1->getId() . "' AND store_id = '" . $importData['store_id'] . "'");

                    $connection->query("UPDATE `" . $_sales_invoice_grid . "` SET grand_total = '" . $customordergrandtotalamt . "' WHERE order_id = '" . $order1->getId() . "' AND store_id = '" . $importData['store_id'] . "'");
                } else {

                    $connection->query("UPDATE `" . $_sales_order . "` SET base_subtotal = '" . $custom_order_base_price_w_o_tax . "', base_tax_amount = '" . $base_tax_amount . "', tax_amount = '" . $base_tax_amount . "', base_grand_total = '" . $customordergrandtotalamt . "', subtotal = '" . $custom_order_base_price_w_o_tax . "', base_subtotal_incl_tax = '" . $importData['subtotal'] . "', subtotal_incl_tax = '" . $importData['subtotal'] . "', grand_total = '" . $customordergrandtotalamt . "', base_shipping_amount = '" . $importData['base_shipping_amount'] . "', base_shipping_tax_amount = '" . $base_shipping_tax_amount . "', shipping_tax_amount = '" . $base_shipping_tax_amount . "', shipping_amount = '" . $importData['base_shipping_amount'] . "', shipping_incl_tax = '" . $importData['shipping_amount'] . "', base_shipping_incl_tax = '" . $importData['shipping_amount'] . "', discount_amount = '" . $final_discount_amount . "', base_discount_amount = '" . $final_discount_amount . "' WHERE entity_id = '" . $order1->getId() . "' AND store_id = '" . $importData['store_id'] . "'");
                }
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR Saving order: " . $e->getMessage()), $e);
                #Mage::log(sprintf('Order #' . $importData['order_id'] . ' saving error: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
            }

            $shippingMethod = $importData['shipping_method'];

            if($shippingMethod != 'flatrate_flatrate') {

                #$addressId = $order1->getQuote()->getShippingAddress()->getId();
                $addressId = $order1->getShippingAddress()->getId();

                #$shippingDescription = "Imported - " . $shippingMethod;
                $shippingDescription = $shippingMethod;

                try {

                    $connection->query(
                        "UPDATE `" . $_sales_order . "` " .
                        " SET shipping_method = 'imported_imported'"
                        . ", shipping_description = \"" . addslashes($shippingDescription) . "\""
                        . " WHERE entity_id = " . $order1->getId());

                    //dont need this in 1.7.x CE or greater
                    /*
                        $connection->query(

                            "UPDATE `" . $_sales_order_grid . "` " .

                                " SET shipping_method = 'imported_imported'"

                                . ", shipping_description = \"" . addslashes($shippingDescription) . "\""

                                . " WHERE entity_id = " . $order1->getId());
                    */
                    $connection->query(
                        "UPDATE `" . $_quote_address . "` " .
                        " SET shipping_method = 'imported_imported'"
                        . ", shipping_description = \"" . addslashes($shippingDescription) . "\""
                        . " WHERE address_id = " . $addressId);

                    $connection->query(
                        "UPDATE `" . $_quote_shipping_rate . "` " .
                        " SET code = 'imported_imported'"
                        . ", carrier = 'imported'"
                        . ", carrier_title = 'Imported'"
                        . ", method = 'imported'"
                        . ", method_description = \"" . addslashes($shippingDescription) . "\""
                        . ", method_title = \"" . addslashes($shippingDescription) . "\""
                        . " WHERE address_id = '" . $addressId . "'");
                } catch(\Exception $e) {
                    throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR: " . $e->getMessage()), $e);
                    #Mage::log(sprintf('Order #' . $importData['order_id'] . ' saving error: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
                }
            }

            $connection->query("UPDATE `" . $_sales_order_tax . "` SET amount = '" . $base_tax_amount . "', base_amount = '" . $base_tax_amount . "', base_real_amount = '" . $base_tax_amount . "'  WHERE order_id = '" . $order1->getId() . "'");

            //UPDATE FOR SALES GRID VIEW -- sales -> orders
            if($params['create_invoice'] == "true") {
                $connection->query("UPDATE `" . $_sales_order_grid . "` SET base_total_paid = '" . $customordergrandtotalamt . "', total_paid = '" . $customordergrandtotalamt . "', base_grand_total = '" . $customordergrandtotalamt . "', grand_total = '" . $customordergrandtotalamt . "' WHERE entity_id = '" . $order1->getId() . "' AND store_id = '" . $importData['store_id'] . "'");

                $connection->query("UPDATE `" . $_sales_order_payment . "` SET base_shipping_captured = '" . $importData['base_shipping_amount'] . "', shipping_captured = '" . $importData['base_shipping_amount'] . "', base_shipping_amount = '" . $importData['base_shipping_amount'] . "', shipping_amount = '" . $importData['base_shipping_amount'] . "', base_amount_ordered = '" . $customordergrandtotalamt . "', amount_ordered = '" . $customordergrandtotalamt . "', base_amount_paid = '" . $customordergrandtotalamt . "', amount_paid = '" . $customordergrandtotalamt . "' WHERE entity_id = '" . $order1->getId() . "'");
            } else {
                $connection->query("UPDATE `" . $_sales_order_grid . "` SET base_grand_total = '" . $customordergrandtotalamt . "', grand_total = '" . $customordergrandtotalamt . "' WHERE entity_id = '" . $order1->getId() . "' AND store_id = '" . $importData['store_id'] . "'");
            }

            $select_qry = "SELECT item_id FROM `" . $_sales_order_item . "` WHERE order_id = '" . $order1->getId() . "'";
            $rows       = $connection->fetchAll($select_qry);

            foreach($rows as $newrowItemId) {
                $item_id = $newrowItemId['item_id'];
            }

            $connection->query("UPDATE `" . $_quote . "` SET base_subtotal = '" . $custom_order_base_price_w_o_tax . "', base_grand_total = '" . $customordergrandtotalamt . "', subtotal = '" . $custom_order_base_price_w_o_tax . "', grand_total = '" . $customordergrandtotalamt . "', subtotal_with_discount = '" . $importData['subtotal'] . "', base_subtotal_with_discount = '" . $importData['subtotal'] . "' WHERE entity_id = '" . $order1->getQuoteId() . "'");
        } else if(isset($importData['shipping_amount'])) {

            $shippingMethod = $importData['shipping_method'];

            if($shippingMethod != 'flatrate_flatrate') {

                #$addressId = $order1->getQuote()->getShippingAddress()->getId();
                $addressId = $order1->getShippingAddress()->getId();

                #$shippingDescription = "Imported - " . $shippingMethod;
                $shippingDescription = $shippingMethod;

                try {

                    $connection->query(
                        "UPDATE `" . $_sales_order . "` " .
                        " SET shipping_method = 'imported_imported'"
                        . ", shipping_description = \"" . addslashes($shippingDescription) . "\""
                        . " WHERE entity_id = " . $order1->getId());

                    //dont need this in 1.7.x CE or greater
                    /*
                        $connection->query(

                            "UPDATE `" . $_sales_order_grid . "` " .

                                " SET shipping_method = 'imported_imported'"

                                . ", shipping_description = \"" . addslashes($shippingDescription) . "\""

                                . " WHERE entity_id = " . $order1->getId());
                    */
                    $connection->query(
                        "UPDATE `" . $_quote_address . "` " .
                        " SET shipping_method = 'imported_imported'"
                        . ", shipping_description = \"" . addslashes($shippingDescription) . "\""
                        . " WHERE address_id = " . $addressId);

                    $connection->query(
                        "UPDATE `" . $_quote_shipping_rate . "` " .
                        " SET code = 'imported_imported'"
                        . ", carrier = 'imported'"
                        . ", carrier_title = 'Imported'"
                        . ", method = 'imported'"
                        . ", method_description = \"" . addslashes($shippingDescription) . "\""
                        . ", method_title = \"" . addslashes($shippingDescription) . "\""
                        . " WHERE address_id = '" . $addressId . "'");
                } catch(\Exception $e) {
                    throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR: " . $e->getMessage()), $e);
                    #Mage::log(sprintf('Order #' . $importData['order_id'] . ' saving error: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
                }
            }
        }
    }

    public function updateProductPrice(&$partsdata, $connection, $resource, $order1, $importData, &$select_qry, &$newrowItemId, &$item_id, &$e, $productcounters, $params)
    {

        $_sales_order_item   = $resource->getTableName('sales_order_item'); //gives table name with prefix
        $_sales_invoice      = $resource->getTableName('sales_invoice'); //gives table name with prefix
        $_sales_invoice_item = $resource->getTableName('sales_invoice_item'); //gives table name with prefix

        $parts = explode(':', $partsdata);

        if(isset($parts[1])) {
            $part_qty = $parts[1];
        } else {
            $part_qty = "1";
        }
        //had to add this to set prices back from 0.00 oddly on import of products in stock on 1 instance always 0.00
        if(!isset($parts[2])) {
                /*  
                if($params['skip_product_lookup'] == "true") {
                    if($productcounters == 1) {
                        $key = 'I-oe_productstandin';
                    } else {
                        $key = 'I-oe_productstandin' . $productcounters;
                    }
                    $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $key);
                    $productskutocheck = $key;
                } else {
                    $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $parts[0]);
                    $productskutocheck = $parts[0];
                }
                
                $select_qry = $connection->query("SELECT quote_item_id,tax_amount,tax_percent FROM `" . $_sales_order_item . "` WHERE order_id = '" . $order1->getId() . "' AND sku = '" . $productskutocheck . "'");

                $newrowItemId = $select_qry->fetch();
                $item_id      = $newrowItemId['quote_item_id'];
                $tax_amount   = $importData['tax_amount'];

                if($params['use_historic_tax'] == "true") {
                    $tax_percent = $importData['tax_percent'];
                } else {
                    $tax_percent = $newrowItemId['tax_percent'];
                }
                
                
                if(method_exists($product2, 'getTypeId')) {
                    $productprice = $product2->getPrice();
                    $custom_row_total = $productprice * $parts[1];
                    $decimalfortaxpercent        = $tax_percent / 100;
                    $tax_percent_for_row_total   = $custom_row_total * $decimalfortaxpercent;
                    $order_total_without_tax     = $custom_row_total - $tax_percent_for_row_total;
                    $per_item_tax_amount         = $tax_amount / $parts[1];
                    $final_item_price_before_tax = $productprice - $per_item_tax_amount;
                    
                    $connection->query("UPDATE `" . $_sales_order_item . "` SET price_incl_tax = '" . $productprice . "', base_price_incl_tax = '" . $productprice . "', base_row_total_incl_tax = '" . $custom_row_total . "', row_total_incl_tax = '" . $custom_row_total . "', row_total = '" . $order_total_without_tax . "', base_row_total = '" . $order_total_without_tax . "', tax_percent = '" . $tax_percent . "', tax_amount = '" . $tax_percent_for_row_total . "', base_tax_amount = '" . $tax_percent_for_row_total . "', original_price = '" . $productprice . "', base_original_price = '" . $productprice . "', price = '" . $productprice . "', base_price = '" . $productprice . "' WHERE order_id = '" . $order1->getId() . "' AND sku = '" . $productskutocheck . "'");
                    
                     if($params['create_invoice'] == "true") {
                            $select_qry        = $connection->query("SELECT entity_id FROM `" . $_sales_invoice . "` WHERE order_id = '" . $order1->getId() . "'");
                            $newrowItemId      = $select_qry->fetch();
                            $invoice_entity_id = $newrowItemId['entity_id'];
    
                            $connection->query("UPDATE `" . $_sales_invoice_item . "` SET price_incl_tax = '" . $productprice . "', base_price_incl_tax = '" . $productprice . "', base_row_total_incl_tax = '" . $custom_row_total . "', row_total_incl_tax = '" . $custom_row_total . "', row_total = '" . $order_total_without_tax . "', base_row_total = '" . $order_total_without_tax . "', tax_amount = '" . $tax_percent_for_row_total . "', base_tax_amount = '" . $tax_percent_for_row_total . "', price = '" . $productprice . "', base_price = '" . $productprice . "' WHERE parent_id = '" . $invoice_entity_id . "' AND sku = '" . $productskutocheck . "'");
                    }
                }
                */
        }
                
        if(isset($parts[2]) && $parts[2] != "configurable" && $parts[2] != "bundle" && $parts[2] != "simple") {

            $custom_row_total                = $parts[2] * $part_qty;
            $productexistsletsnotupdateprice = false;

            try {
                if($params['skip_product_lookup'] == "true") {
                   $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $parts[0]);
                } else {
                    $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $parts[0]);
                }

                if(method_exists($product2, 'getTypeId')) {

                    #$stockItem = $this->_objectManager->get('cataloginventory/stock_item')->loadByProduct($product2->getId());
                    $stockItem = $this->objectManager->get('Magento\CatalogInventory\Model\StockRegistry')->getStockItem($product2->getId());

                    try {
                        if($stockItem->getIsInStock() && $stockItem->getManageStock() == 1 && $stockItem->getQty() != "0.0000" || $stockItem->getManageStock() == 0) {

                            if(method_exists($product2, 'getTypeId')) {
                                if($params['skip_product_lookup'] == "true") {
                                    $productskutocheck               = $parts[0];
                                    $productexistsletsnotupdateprice = true;
                                } else {
                                    $productskutocheck               = $parts[0];
                                    $productexistsletsnotupdateprice = true;
                                }
                            } else if($productcounters > 1) {
                                $productskutocheck = 'I-oe_productstandin' . $productcounters;
                            } else {
                                $productskutocheck = 'I-oe_productstandin';
                            }
                        } else {
                            if($productcounters > 1) {
                                $productskutocheck = 'I-oe_productstandin' . $productcounters;
                            } else {
                                $productskutocheck = 'I-oe_productstandin';
                            }
                        }
                    } catch(\Exception $e) {
                        throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT PRICE: " . $e->getMessage()), $e);
                        #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT PRICE: %s', $e->getMessage()), null, 'ce_order_import_errors.log');

                    }
                } else {

                    try {

                        if($productcounters > 1) {
                            $productskutocheck = 'I-oe_productstandin' . $productcounters;
                        } else {
                            $productskutocheck = 'I-oe_productstandin';
                        }
                    } catch(\Exception $e) {
                        throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT PRICE: " . $e->getMessage()), $e);
                        #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT PRICE: %s', $e->getMessage()), null, 'ce_order_import_errors.log');

                    }
                }

                $select_qry = $connection->query("SELECT quote_item_id,tax_amount,tax_percent FROM `" . $_sales_order_item . "` WHERE order_id = '" . $order1->getId() . "' AND sku = '" . $productskutocheck . "'");

                $newrowItemId = $select_qry->fetch();
                $item_id      = $newrowItemId['quote_item_id'];
                $tax_amount   = $importData['tax_amount'];

                if($params['use_historic_tax'] == "true") {
                    $tax_percent = $importData['tax_percent'];
                } else {
                    $tax_percent = $newrowItemId['tax_percent'];
                }

                $decimalfortaxpercent        = $tax_percent / 100;
                $tax_percent_for_row_total   = $custom_row_total * $decimalfortaxpercent;
                $order_total_without_tax     = $custom_row_total - $tax_percent_for_row_total;
                $per_item_tax_amount         = $tax_amount / $part_qty;
                $final_item_price_before_tax = $parts[2] - $per_item_tax_amount;

                //row_total_before_redemptions row_total_before_redemptions_incl_tax row_total_after_redemptions row_total_after_redemptions_incl_tax

                #echo "CUSTOMER ORDER TOTAL: " . $custom_row_total . "<br/>";
                #echo "ORDER TOTAL w/o TAX: " . $order_total_without_tax . "<br/>";
                #echo "ITEM PRICE BEFORE TAX: " . $final_item_price_before_tax . "<br/>";

                if($productexistsletsnotupdateprice != true) {
                    $connection->query("UPDATE `" . $_sales_order_item . "` SET price_incl_tax = '" . $parts[2] . "', base_price_incl_tax = '" . $parts[2] . "', base_row_total_incl_tax = '" . $custom_row_total . "', row_total_incl_tax = '" . $custom_row_total . "', row_total = '" . $order_total_without_tax . "', base_row_total = '" . $order_total_without_tax . "', tax_percent = '" . $tax_percent . "', tax_amount = '" . $tax_percent_for_row_total . "', base_tax_amount = '" . $tax_percent_for_row_total . "', original_price = '" . $parts[2] . "', base_original_price = '" . $parts[2] . "', price = '" . $parts[2] . "', base_price = '" . $parts[2] . "' WHERE order_id = '" . $order1->getId() . "' AND sku = '" . $productskutocheck . "'");
                }

                if(isset($importData['discount_amount']) && $importData['discount_amount'] != "0.0000" && $importData['discount_amount'] != "0.00") {

                    $final_discount_amount      = str_replace('-', '', $importData['discount_amount']);
                    $final_base_discount_amount = str_replace('-', '', $importData['base_discount_amount']);

                    //don't think we need to discount on indvidual items atm
                    #$connection->query("UPDATE `" . $prefix . "sales_flat_order_item` SET discount_amount = '" . $final_discount_amount . "', base_discount_amount = '" . $final_base_discount_amount . "' WHERE order_id = '" . $order1->getId() . "' AND sku = '" . $parts[0] . "'");

                }

                if($params['create_invoice'] == "true") {

                    if($productexistsletsnotupdateprice != true) {

                        $select_qry        = $connection->query("SELECT entity_id FROM `" . $_sales_invoice . "` WHERE order_id = '" . $order1->getId() . "'");
                        $newrowItemId      = $select_qry->fetch();
                        $invoice_entity_id = $newrowItemId['entity_id'];

                        $connection->query("UPDATE `" . $_sales_invoice_item . "` SET price_incl_tax = '" . $parts[2] . "', base_price_incl_tax = '" . $parts[2] . "', base_row_total_incl_tax = '" . $custom_row_total . "', row_total_incl_tax = '" . $custom_row_total . "', row_total = '" . $order_total_without_tax . "', base_row_total = '" . $order_total_without_tax . "', tax_amount = '" . $tax_percent_for_row_total . "', base_tax_amount = '" . $tax_percent_for_row_total . "', price = '" . $parts[2] . "', base_price = '" . $parts[2] . "' WHERE parent_id = '" . $invoice_entity_id . "' AND sku = '" . $productskutocheck . "'");
                    }
                }
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT PRICE: " . $e->getMessage()), $e);
                #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT PRICE: %s', $e->getMessage()), null, 'ce_order_import_errors.log');

            }
        } else if(isset($parts[2]) && $parts[2] == "bundle") {

            //this is for bundle product setups that are not in stock or are not available in the store at all.
            if($params['skip_product_lookup'] == "true") {
                if($productcounters == 1) {
                    $key = 'I-oe_productstandin' . $productcounters;
                } else {
                    $key = 'I-oe_productstandin' . $productcounters;
                }
                $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $key);
            } else {
                $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $parts[0]);
            }
            if(method_exists($product2, 'getTypeId')) {

                $stockItem = $this->objectManager->get('Magento\CatalogInventory\Model\StockRegistry')->getStockItem($product2->getId());

                try {

                    if($stockItem->getIsInStock() && $stockItem->getManageStock() == 1 && $stockItem->getQty() != "0.0000" || $stockItem->getManageStock() == 0) {

                        if(method_exists($product2, 'getTypeId') && $product2->getTypeId() == "bundle") {
                            $productskutocheck = $parts[0];
                        } else if($productcounters > 1) {
                            $productskutocheck = 'I-oe_productstandin' . $productcounters;
                        } else {
                            $productskutocheck = 'I-oe_productstandin';
                        }
                    } else {

                        if($productcounters > 1) {
                            $productskutocheck = 'I-oe_productstandin' . $productcounters;
                        } else {
                            $productskutocheck = 'I-oe_productstandin';
                        }
                    }
                } catch(\Exception $e) {
                    throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT PRICE: " . $e->getMessage()), $e);
                    #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT PRICE: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
                }
            } else {
                try {
                    if($productcounters > 1) {
                        $productskutocheck = 'I-oe_productstandin' . $productcounters;
                    } else {
                        $productskutocheck = 'I-oe_productstandin';
                    }
                } catch(\Exception $e) {
                    throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT PRICE: " . $e->getMessage()), $e);
                    #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT PRICE: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
                }
            }

            $partsfornamepricing = explode('^', $partsdata);
            $custom_row_total    = $partsfornamepricing[1] * $part_qty;
            $custom_unit_price   = $partsfornamepricing[1];

            try {

                $select_qry = $connection->query("SELECT quote_item_id,tax_amount,tax_percent FROM `" . $_sales_order_item . "` WHERE order_id = '" . $order1->getId() . "' AND sku = '" . $productskutocheck . "'");

                $newrowItemId = $select_qry->fetch();
                $item_id      = $newrowItemId['quote_item_id'];
                $tax_amount   = $importData['tax_amount'];

                if($params['use_historic_tax'] == "true") {
                    $tax_percent = $importData['tax_percent'];
                } else {
                    $tax_percent = $newrowItemId['tax_percent'];
                }

                $decimalfortaxpercent        = $tax_percent / 100;
                $tax_percent_for_row_total   = $custom_row_total * $decimalfortaxpercent;
                $order_total_without_tax     = $custom_row_total;
                $order_total_has_tax         = $custom_row_total + $tax_percent_for_row_total;
                $per_item_tax_amount         = $tax_amount / $part_qty;
                $final_item_price_before_tax = $partsfornamepricing[1] - $per_item_tax_amount;

                $connection->query("UPDATE `" . $_sales_order_item . "` SET price_incl_tax = '" . $partsfornamepricing[1] . "', base_price_incl_tax = '" . $partsfornamepricing[1] . "', base_row_total_incl_tax = '" . $order_total_has_tax . "', row_total_incl_tax = '" . $order_total_has_tax . "', row_total = '" . $order_total_without_tax . "', base_row_total = '" . $order_total_without_tax . "', tax_percent = '" . $tax_percent . "', tax_amount = '" . $tax_percent_for_row_total . "', base_tax_amount = '" . $tax_percent_for_row_total . "', original_price = '" . $custom_unit_price . "', base_original_price = '" . $custom_unit_price . "', price = '" . $custom_unit_price . "', base_price = '" . $custom_unit_price . "' WHERE order_id = '" . $order1->getId() . "' AND sku = '" . $productskutocheck . "'");

                if(isset($importData['discount_amount']) && $importData['discount_amount'] != "0.0000" && $importData['discount_amount'] != "0.00") {

                    $final_discount_amount      = str_replace('-', '', $importData['discount_amount']);
                    $final_base_discount_amount = str_replace('-', '', $importData['base_discount_amount']);

                    $connection->query("UPDATE `" . $_sales_order_item . "` SET discount_amount = '" . $final_discount_amount . "', base_discount_amount = '" . $final_base_discount_amount . "' WHERE order_id = '" . $order1->getId() . "' AND sku = '" . $productskutocheck . "'");
                }

                if($params['create_invoice'] == "true") {

                    $select_qry          = $connection->query("SELECT entity_id FROM `" . $_sales_invoice . "` WHERE order_id = '" . $order1->getId() . "'");
                    $newrowInvoiceItemId = $select_qry->fetch();
                    $invoice_item_id     = $newrowInvoiceItemId['entity_id'];

                    if($invoice_item_id > 0) {

                        $connection->query("UPDATE `" . $_sales_invoice_item . "` SET price_incl_tax = '" . $partsfornamepricing[1] . "', base_price_incl_tax = '" . $partsfornamepricing[1] . "', base_row_total_incl_tax = '" . $order_total_has_tax . "', row_total_incl_tax = '" . $order_total_has_tax . "', row_total = '" . $order_total_without_tax . "', base_row_total = '" . $order_total_without_tax . "', tax_amount = '" . $tax_percent_for_row_total . "', base_tax_amount = '" . $tax_percent_for_row_total . "', price = '" . $custom_unit_price . "', base_price = '" . $custom_unit_price . "' WHERE parent_id = '" . $invoice_item_id . "' AND sku = '" . $productskutocheck . "'");

                        if(isset($importData['discount_amount']) && $importData['discount_amount'] != "") {

                            $final_discount_amount      = str_replace('-', '', $importData['discount_amount']);
                            $final_base_discount_amount = str_replace('-', '', $importData['base_discount_amount']);

                            $connection->query("UPDATE `" . $_sales_invoice_item . "` SET discount_amount = '" . $final_discount_amount . "', base_discount_amount = '" . $final_base_discount_amount . "' WHERE parent_id = '" . $invoice_item_id . "' AND sku = '" . $productskutocheck . "'");
                        }
                    }
                }

                //credit memo
                #if ($this->getBatchParams('create_creditmemo') == "true") {

                #}

            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT PRICE: " . $e->getMessage()), $e);
                #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT PRICE: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
            }
        } else if(isset($parts[2]) && $parts[2] == "configurable" || isset($parts[2]) && $parts[2] == "simple") {

            $configurableskusomatchonlike = false;
            //this is for configurable product setups that are not in stock or are not available in the store at all.
            if($params['skip_product_lookup'] == "true") {
                if($productcounters == 1) {
                    $key = 'I-oe_productstandin' . $productcounters;
                } else {
                    $key = 'I-oe_productstandin' . $productcounters;
                }
                $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $key);
            } else {
                $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $parts[0]);
            }

            if(method_exists($product2, 'getTypeId')) {

                $stockItem = $this->objectManager->get('Magento\CatalogInventory\Model\StockRegistry')->getStockItem($product2->getId());

                try {

                    if($stockItem->getIsInStock() && $stockItem->getManageStock() == 1 && $stockItem->getQty() != "0.0000" || $stockItem->getManageStock() == 0) {

                        if(method_exists($product2, 'getTypeId') && $product2->getTypeId() == "configurable") {

                            if($params['skip_product_lookup'] == "true") {
                                $productskutocheck            = $parts[0];
                                $configurableskusomatchonlike = true;
                            } else {
                                $productskutocheck            = $parts[0];
                                $configurableskusomatchonlike = true;
                            }
                        } else if(method_exists($product2, 'getTypeId') && $product2->getTypeId() == "simple" && $parts[2] != "configurable") {

                            if($params['skip_product_lookup'] == "true") {
                                $productskutocheck            = $parts[0];
                                $partsfornamepricing = explode('^', $partsdata);
                                if(isset($partsfornamepricing[1])) {
                                    $configurableskusomatchonlike = true;
                                }
                            } else {
                                $productskutocheck            = $parts[0];
                                $partsfornamepricing = explode('^', $partsdata);
                                if(isset($partsfornamepricing[1])) {
                                    $configurableskusomatchonlike = true;
                                }
                            }
                        } else if($productcounters > 1) {
                            $productskutocheck = 'I-oe_productstandin' . $productcounters;
                        } else {
                            $productskutocheck = 'I-oe_productstandin';
                        }
                    } else {

                        if($productcounters > 1) {
                            $productskutocheck = 'I-oe_productstandin' . $productcounters;
                        } else {
                            $productskutocheck = 'I-oe_productstandin';
                        }
                    }
                } catch(\Exception $e) {
                    throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT PRICE: " . $e->getMessage()), $e);
                    #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT PRICE: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
                }
            } else {
                try {
                    if($productcounters > 1) {
                        $productskutocheck = 'I-oe_productstandin' . $productcounters;
                    } else {
                        $productskutocheck = 'I-oe_productstandin';
                    }
                } catch(\Exception $e) {
                    throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT PRICE: " . $e->getMessage()), $e);
                    #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT PRICE: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
                }
            }

            $partsfornamepricing = explode('^', $partsdata);
            if(isset($partsfornamepricing[1])) {
            $custom_row_total    = $partsfornamepricing[1] * $part_qty;
            $custom_unit_price   = $partsfornamepricing[1];

            try {

                $select_qry = $connection->query("SELECT quote_item_id,tax_amount,tax_percent FROM `" . $_sales_order_item . "` WHERE order_id = '" . $order1->getId() . "' AND sku = '" . $productskutocheck . "'");

                $newrowItemId = $select_qry->fetch();
                $item_id      = $newrowItemId['quote_item_id'];
                $tax_amount   = $importData['tax_amount'];

                if($params['use_historic_tax'] == "true") {
                    $tax_percent = $importData['tax_percent'];
                } else {
                    $tax_percent = $newrowItemId['tax_percent'];
                }

                #$tax_percent_for_row_total = $custom_row_total * .09090909090909091;

                $decimalfortaxpercent        = $tax_percent / 100;
                $tax_percent_for_row_total   = $custom_row_total * $decimalfortaxpercent;
                $order_total_without_tax     = $custom_row_total;
                $order_total_has_tax         = $custom_row_total + $tax_percent_for_row_total;
                $per_item_tax_amount         = $tax_amount / $part_qty;
                $final_item_price_before_tax = $partsfornamepricing[1] - $per_item_tax_amount;

                //row_total_before_redemptions row_total_before_redemptions_incl_tax row_total_after_redemptions row_total_after_redemptions_incl_tax
                #echo "CUSTOMER ORDER TOTAL: " . $custom_row_total . "<br/>";
                #echo "ORDER TOTAL w/o TAX: " . $order_total_without_tax . "<br/>";
                #echo "ITEM PRICE BEFORE TAX: " . $final_item_price_before_tax . "<br/>";

                if($configurableskusomatchonlike) {
                    //this needs to be fixed... because LIKE sku could conflict if order has 2 products orders with like sku.. so a configurable ordered twice in 2 different sizes for example.. need to do by item_id or something but no data available to support this custom configurable pricing atm
                    $connection->query("UPDATE `" . $_sales_order_item . "` SET price_incl_tax = '" . $partsfornamepricing[1] . "', base_price_incl_tax = '" . $partsfornamepricing[1] . "', base_row_total_incl_tax = '" . $order_total_has_tax . "', row_total_incl_tax = '" . $order_total_has_tax . "', row_total = '" . $order_total_without_tax . "', base_row_total = '" . $order_total_without_tax . "', tax_percent = '" . $tax_percent . "', tax_amount = '" . $tax_percent_for_row_total . "', base_tax_amount = '" . $tax_percent_for_row_total . "', original_price = '" . $custom_unit_price . "', base_original_price = '" . $custom_unit_price . "', price = '" . $custom_unit_price . "', base_price = '" . $custom_unit_price . "' WHERE order_id = '" . $order1->getId() . "' AND sku LIKE '%" . $productskutocheck . "%'");
                } else {
                    $connection->query("UPDATE `" . $_sales_order_item . "` SET price_incl_tax = '" . $partsfornamepricing[1] . "', base_price_incl_tax = '" . $partsfornamepricing[1] . "', base_row_total_incl_tax = '" . $order_total_has_tax . "', row_total_incl_tax = '" . $order_total_has_tax . "', row_total = '" . $order_total_without_tax . "', base_row_total = '" . $order_total_without_tax . "', tax_percent = '" . $tax_percent . "', tax_amount = '" . $tax_percent_for_row_total . "', base_tax_amount = '" . $tax_percent_for_row_total . "', original_price = '" . $custom_unit_price . "', base_original_price = '" . $custom_unit_price . "', price = '" . $custom_unit_price . "', base_price = '" . $custom_unit_price . "' WHERE order_id = '" . $order1->getId() . "' AND sku = '" . $productskutocheck . "'");
                }

                if(isset($importData['discount_amount']) && $importData['discount_amount'] != "0.0000" && $importData['discount_amount'] != "0.00") {

                    $final_discount_amount      = str_replace('-', '', $importData['discount_amount']);
                    $final_base_discount_amount = str_replace('-', '', $importData['base_discount_amount']);

                    $connection->query("UPDATE `" . $_sales_order_item . "` SET discount_amount = '" . $final_discount_amount . "', base_discount_amount = '" . $final_base_discount_amount . "' WHERE order_id = '" . $order1->getId() . "' AND sku = '" . $productskutocheck . "'");
                }

                if($params['create_invoice'] == "true") {

                    $select_qry          = $connection->query("SELECT entity_id FROM `" . $_sales_invoice . "` WHERE order_id = '" . $order1->getId() . "'");
                    $newrowInvoiceItemId = $select_qry->fetch();
                    $invoice_item_id     = $newrowInvoiceItemId['entity_id'];

                    if($invoice_item_id > 0) {

                        $connection->query("UPDATE `" . $_sales_invoice_item . "` SET price_incl_tax = '" . $partsfornamepricing[1] . "', base_price_incl_tax = '" . $partsfornamepricing[1] . "', base_row_total_incl_tax = '" . $order_total_has_tax . "', row_total_incl_tax = '" . $order_total_has_tax . "', row_total = '" . $order_total_without_tax . "', base_row_total = '" . $order_total_without_tax . "', tax_amount = '" . $tax_percent_for_row_total . "', base_tax_amount = '" . $tax_percent_for_row_total . "', price = '" . $custom_unit_price . "', base_price = '" . $custom_unit_price . "' WHERE parent_id = '" . $invoice_item_id . "' AND sku = '" . $productskutocheck . "'");

                        if(isset($importData['discount_amount']) && $importData['discount_amount'] != "") {

                            $final_discount_amount      = str_replace('-', '', $importData['discount_amount']);
                            $final_base_discount_amount = str_replace('-', '', $importData['base_discount_amount']);

                            $connection->query("UPDATE `" . $_sales_invoice_item . "` SET discount_amount = '" . $final_discount_amount . "', base_discount_amount = '" . $final_base_discount_amount . "' WHERE parent_id = '" . $invoice_item_id . "' AND sku = '" . $productskutocheck . "'");
                        }
                    }
                }

                //credit memo
                #if ($this->getBatchParams('create_creditmemo') == "true") {

                #}

            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT PRICE: " . $e->getMessage()), $e);
                #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT PRICE: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
            }
            }//make sure we have pricing to update the item row
        }
    }

    public function updateProductItemName(&$partsdata, $importData, $connection, $resource, $order1, &$e, &$select_qry, $productcounters, $params)
    {
        $parts = explode(':', $partsdata);

        $_quote_item         = $resource->getTableName('quote_item'); //gives table name with prefix
        $_sales_order_item   = $resource->getTableName('sales_order_item'); //gives table name with prefix
        $_sales_invoice      = $resource->getTableName('sales_invoice'); //gives table name with prefix
        $_sales_invoice_item = $resource->getTableName('sales_invoice_item'); //gives table name with prefix

        if(isset($parts[3]) && $parts[2] != "configurable" && $parts[2] != "bundle" && $parts[2] != "simple") {

            if($params['skip_product_lookup'] == "true") {
               $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $parts[0]);
            } else {
                $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $parts[0]);
            }

            try {

                if(method_exists($product2, 'getTypeId')) {

                    $stockItem = $this->objectManager->get('Magento\CatalogInventory\Model\StockRegistry')->getStockItem($product2->getId());

                    if($stockItem->getIsInStock() && $stockItem->getManageStock() == 1 && $stockItem->getQty() != "0.0000" || $stockItem->getManageStock() == 0) {
                        if($params['skip_product_lookup'] == "true") {
                             $productskutocheck = $parts[0];
                        } else {
                            $productskutocheck = $parts[0];
                        }
                    } else {
                        if($productcounters > 1) {
                            $productskutocheck = 'I-oe_productstandin' . $productcounters;
                        } else {
                            $productskutocheck = 'I-oe_productstandin';
                        }
                    }
                } else {
                    #$productskutocheck = 'I-oe_productstandin';
                    if($productcounters > 1) {
                        $productskutocheck = 'I-oe_productstandin' . $productcounters;
                    } else {
                        $productskutocheck = 'I-oe_productstandin';
                    }
                }
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT NAME: " . $e->getMessage()), $e);
                #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT NAME: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
            }

            $customproductname = $parts[3];
            $select_qry        = $connection->query("SELECT item_id FROM `" . $_sales_order_item . "` WHERE order_id = '" . $order1->getId() . "' AND sku = \"" . $productskutocheck . "\"");

            $newrowItemId2 = $select_qry->fetch();
            $db_item_id    = $newrowItemId2['item_id'];

            try {

                $connection->query("UPDATE `" . $_sales_order_item . "` SET name = \"" . addslashes($customproductname) . "\", sku = \"" . $parts[0] . "\" WHERE item_id = '" . $db_item_id . "'");

                $connection->query("UPDATE `" . $_quote_item . "` SET name = \"" . addslashes($customproductname) . "\", sku = \"" . $parts[0] . "\" WHERE quote_id = '" . $db_item_id . "'");
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT NAME: " . $e->getMessage()), $e);
                #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT NAME: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
            }
        } else if(isset($parts[2]) && $parts[2] == "bundle") {

            if($params['skip_product_lookup'] == "true") {
                $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $parts[0]);
            } else {
                $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $parts[0]);
            }

            if(method_exists($product2, 'getTypeId')) {

                $stockItem = $this->objectManager->get('Magento\CatalogInventory\Model\StockRegistry')->getStockItem($product2->getId());

                if($product2->getTypeId() == "bundle") {
                    try {
                        if($stockItem->getIsInStock() && $stockItem->getManageStock() == 1 && $stockItem->getQty() != "0.0000" || $stockItem->getManageStock() == 0) {
                            if($productcounters > 1) {
                                $productskutocheck = 'I-oe_productstandin' . $productcounters;
                            } else {
                                $productskutocheck = 'I-oe_productstandin';
                            }
                        } else {
                            if($productcounters > 1) {
                                $productskutocheck = 'I-oe_productstandin' . $productcounters;
                            } else {
                                $productskutocheck = 'I-oe_productstandin';
                            }
                        }
                    } catch(\Exception $e) {
                        throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT NAME: " . $e->getMessage()), $e);
                        #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT NAME: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
                    }
                } else {
                    #echo "MISMATCH PRODUCT TYPE";
                    if($productcounters > 1) {
                        $productskutocheck = 'I-oe_productstandin' . $productcounters;
                    } else {
                        $productskutocheck = 'I-oe_productstandin';
                    }
                }
            } else {
                try {
                    if($productcounters > 1) {
                        $productskutocheck = 'I-oe_productstandin' . $productcounters;
                    } else {
                        $productskutocheck = 'I-oe_productstandin';
                    }
                } catch(\Exception $e) {
                    throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT NAME: " . $e->getMessage()), $e);
                    #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT NAME: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
                }
            }

            if(isset($parts[6]) && $parts[6] != "") {
                $customproductname = htmlspecialchars($parts[6], ENT_NOQUOTES, "UTF-8");
                $customproductsku  = $parts[3];
            } else {
                $partsfornamepricing = explode('^', $partsdata);
                if(isset($partsfornamepricing[2]) && $partsfornamepricing[2] != "") {
                    $customproductname = $partsfornamepricing[2];
                } else {
                    $customproductname = $parts[3];
                }
                $customproductsku = $parts[0];
            }

            $select_qry = $connection->query("SELECT item_id FROM `" . $_sales_order_item . "` WHERE order_id = '" . $order1->getId() . "' AND sku = \"" . $productskutocheck . "\"");

            $newrowItemId2 = $select_qry->fetch();
            $db_item_id    = $newrowItemId2['item_id'];

            try {

                $connection->query("UPDATE `" . $_sales_order_item . "` SET name = \"" . addslashes($customproductname) . "\", sku = \"" . $customproductsku . "\" WHERE item_id = '" . $db_item_id . "'");

                $connection->query("UPDATE `" . $_quote_item . "` SET name = \"" . addslashes($customproductname) . "\", sku = \"" . $customproductsku . "\" WHERE quote_id = '" . $db_item_id . "'");
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT NAME: " . $e->getMessage()), $e);
                #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT NAME: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
            }
        } else if(isset($parts[2]) && $parts[2] == "configurable" || isset($parts[2]) && $parts[2] == "simple") {

            if($params['skip_product_lookup'] == "true") {
                $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $parts[0]);
            } else {
                $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $parts[0]);
            }

            if(method_exists($product2, 'getTypeId')) {

                $stockItem = $this->objectManager->get('Magento\CatalogInventory\Model\StockRegistry')->getStockItem($product2->getId());

                if($product2->getTypeId() == "configurable") {
                    try {

                        if($stockItem->getIsInStock() && $stockItem->getManageStock() == 1 && $stockItem->getQty() != "0.0000" || $stockItem->getManageStock() == 0) {

                            if(method_exists($product2, 'getTypeId')) {
                                if($this->getBatchParams('skip_product_lookup') == "true") {
                                    $productskutocheck = $parts[0];
                                } else {
                                    $productskutocheck = $parts[0];
                                }
                            } else if($productcounters > 1) {
                                $productskutocheck = 'I-oe_productstandin' . $productcounters;
                            } else {
                                $productskutocheck = 'I-oe_productstandin';
                            }
                        } else {
                            if($productcounters > 1) {
                                $productskutocheck = 'I-oe_productstandin' . $productcounters;
                            } else {
                                $productskutocheck = 'I-oe_productstandin';
                            }
                        }
                    } catch(\Exception $e) {
                        throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT NAME: " . $e->getMessage()), $e);
                        #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT NAME: %s', $e->getMessage()), null, 'ce_order_import_errors.log');

                    }
                } else {
                    #echo "MISMATCH PRODUCT TYPE";
                    if($productcounters > 1) {
                        $productskutocheck = 'I-oe_productstandin' . $productcounters;
                    } else {
                        $productskutocheck = 'I-oe_productstandin';
                    }
                }
            } else {
                try {
                    if($productcounters > 1) {
                        $productskutocheck = 'I-oe_productstandin' . $productcounters;
                    } else {
                        $productskutocheck = 'I-oe_productstandin';
                    }
                } catch(\Exception $e) {
                    throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT NAME: " . $e->getMessage()), $e);
                    #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT NAME: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
                }
            }

            $partsfornamepricing = explode('^', $partsdata);

            if(isset($partsfornamepricing[2]) && $partsfornamepricing[2] != "") {
                $customproductname = $partsfornamepricing[2];
            } else {
                $customproductname = $parts[3];
            }

            $select_qry = $connection->query("SELECT item_id FROM `" . $_sales_order_item . "` WHERE order_id = '" . $order1->getId() . "' AND sku = \"" . $productskutocheck . "\"");

            $newrowItemId2 = $select_qry->fetch();
            $db_item_id    = $newrowItemId2['item_id'];

            try {
                $connection->query("UPDATE `" . $_sales_order_item . "` SET name = \"" . addslashes($customproductname) . "\", sku = \"" . $parts[0] . "\" WHERE item_id = '" . $db_item_id . "'");

                $connection->query("UPDATE `" . $_quote_item . "` SET name = \"" . addslashes($customproductname) . "\", sku = \"" . $parts[0] . "\" WHERE quote_id = '" . $db_item_id . "'");
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT NAME: " . $e->getMessage()), $e);
                # Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR UPDATING PRODUCT NAME: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
            }
        }
    }

    public function createShipment($order1, $importData)

    {
        //Create new Shipment for the order
        #$shipment = $order1->toShipment($itemQty); //removed in m2
        #$shipment->register();

        try {

            $convertOrder = $this->objectManager->get('Magento\Sales\Model\Convert\Order');
            $shipment     = $convertOrder->toShipment($order1);
            
            if(isset($importData['created_at'])) {
                $dateTime = strtotime($importData['created_at']);
                $shipmentCreatedAt = date("Y-m-d H:i:s", $dateTime);
                $shipment->setCreatedAt($shipmentCreatedAt);
                $shipment->setUpdatedAt($shipmentCreatedAt);
                $shipment->setOrderCreatedAt($shipmentCreatedAt);
            }
            // Loop through order items
            foreach($order1->getAllItems() AS $orderItem) {
                // Check if order item has qty to ship or is virtual
                if(!$orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                    continue;
                }

                $qtyShipped = $orderItem->getQtyToShip();

                // Create shipment item with qty
                $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);

                // Add shipment item to shipment
                $shipment->addItem($shipmentItem);
            }
            $shipment->getOrder()->setIsInProcess(true);

            if(isset($importData['tracking_date']) && isset($importData['tracking_codes']) && isset($importData['tracking_ship_method'])) {
                if($importData['tracking_codes'] != "") {

                    $tracking_codes = $importData['tracking_codes'];
                    if($importData['tracking_ship_method'] != "") {
                        $shipmentCarrierCode = $importData['tracking_ship_method'];
                    } else {
                        $shipmentCarrierCode = "custom";
                    }
                    $shipmentCarrierTitle = $importData['tracking_ship_method'];

                    $tracking_codes_collection = explode(",", $tracking_codes);
                    #$logger = $this->objectManager->get('\Psr\Log\LoggerInterface');
                    foreach($tracking_codes_collection as $shipmentTrackingNumber) {

                        $arrTracking = array(
                            'carrier_code' => isset($shipmentCarrierCode) ? $shipmentCarrierCode : $order1->getShippingCarrier()->getCarrierCode(),
                            'title'        => isset($shipmentCarrierTitle) ? $shipmentCarrierTitle : $order1->getShippingCarrier()->getConfigData('title'),
                            'number'       => $shipmentTrackingNumber,
                            'order_id'     => $order1->getId(),
                        );
                        #$logger->log(100,print_r("ID: " .$order1->getId(),true));
                        #$logger->log(100,print_r($arrTracking,true));
                        #$track = $shipmentTrack->addData($arrTracking);
                        $track = $this->shipmentTrackFactory->create()->addData($arrTracking);
                        $shipment->addTrack($track);
                    }
                }
            }
            if(isset($importData['shipment_id'])) {
                if($importData['shipment_id'] !="") { $shipment->setIncrementId($importData['shipment_id']); }
            }
            $shipment->register();

            $transactionSave = $this->objectManager->get('Magento\Framework\DB\Transaction')
                                                   ->addObject($shipment)
                                                   ->addObject($shipment->getOrder())
                                                   ->save();
        } catch(\Exception $e) {

            #Mage::log("failed to create a shipment");
            #Mage::log($e->getMessage());
            #Mage::log($e->getTraceAsString());
            throw new \Magento\Framework\Exception\LocalizedException(__("failed to create a shipment: " . $e->getMessage()), $e);
            #Mage::log(sprintf('failed to create a shipment: %s', $e->getMessage()), null, 'ce_order_import_errors.log');

        }
    }

    public function processProductOrdered($data, $importData, $connection, $resource, $order1, $productcounters, $params)

    {

        $parts = explode(':', $data);

        if(isset($parts[1])) {
            $part_qty = $parts[1];
        } else {
            $part_qty = "1";
        }
        
        $_quote              = $resource->getTableName('quote');
        $_quote_item         = $resource->getTableName('quote_item');
        $_sales_order        = $resource->getTableName('sales_order');
        $_sales_invoice      = $resource->getTableName('sales_invoice');
        $_sales_invoice_grid = $resource->getTableName('sales_invoice_grid');
        $_sales_order_grid   = $resource->getTableName('sales_order_grid');
        $_sales_order_tax    = $resource->getTableName('sales_order_tax');
        $_sales_order_item   = $resource->getTableName('sales_order_item');
        $_sales_invoice_item = $resource->getTableName('sales_invoice_item');

        /*
        if(isset($parts[3]) && $parts[2] != "configurable" && $parts[2] != "bundle" && $parts[2] != "simple") {
            if($params['skip_product_lookup'] == "true") {
                if($productcounters == 1) {
                    $key = 'I-oe_productstandin';
                } else {
                    $key = 'I-oe_productstandin' . $productcounters;
                }
                $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $key);
            } else {
                $product2 = $this->objectManager->get('Magento\Catalog\Model\Product')->loadByAttribute('sku', $parts[0]);
            }

            try {
                if(method_exists($product2, 'getTypeId')) {
                    $productskutocheck = $parts[0];
                } else {
                    if($productcounters > 1) {
                        $productskutocheck = 'I-oe_productstandin' . $productcounters;
                    } else {
                        $productskutocheck = 'I-oe_productstandin';
                    }
                }
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT NAME: " . $e->getMessage()), $e);
                #Mage::log(sprintf('Order #' . $importData['order_id'] . $message), null, 'ce_order_import_errors.log');
            }
            $customproductname = $parts[3];
            $select_qry        = $connection->query("SELECT item_id FROM `" . $_sales_order_item . "` WHERE order_id = '" . $order1->getId() . "' AND sku = \"" . $productskutocheck . "\"");
            $newrowItemId2     = $select_qry->fetch();
            $db_item_id        = $newrowItemId2['item_id'];

            try {

                $connection->query(
                    "UPDATE `" . $_sales_order_item . "` "
                    . "SET name = \"" . addslashes($customproductname)
                    . "\", sku = \"" . $parts[0]
                    . "\" WHERE item_id = '" . $db_item_id . "'");

                $connection->query(
                    "UPDATE `" . $_quote_item . "` "
                    . "SET name = \"" . addslashes($customproductname)
                    . "\", sku = \"" . $parts[0]
                    . "\" WHERE quote_id = '" . $db_item_id . "'");
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING PRODUCT NAME: " . $e->getMessage()), $e);
                #Mage::log(sprintf('Order #' . $importData['order_id'] . $message), null, 'ce_order_import_errors.log');
            }
            
        }
        */
        //JUST PRICE UPDATE
        /*
        if(isset($parts[2]) && $parts[2] != "configurable" && $parts[2] != "bundle" && $parts[2] != "simple") {

            $custom_row_total = $parts[2] * $part_qty;
            
            try {

                $select_qry = $connection->query("SELECT quote_item_id,tax_amount,tax_percent FROM `" . $_sales_order_item . "` WHERE order_id = '" . $order1->getId() ."' AND sku = '" . $parts[0] . "'");
                $newrowItemId = $select_qry->fetch();
                $item_id      = $newrowItemId['quote_item_id'];

                if($params['use_historic_tax'] == "true") {
                    $tax_percent = $importData['tax_percent'];
                } else {
                    $tax_percent = $newrowItemId['tax_percent'];
                }
                #$tax_percent = 8.00;

                $decimalfortaxpercent     = $tax_percent / 100;
                $tax_amount_for_row_total = $custom_row_total * $decimalfortaxpercent;
                $order_total_without_tax  = $custom_row_total;
                $order_total_with_tax     = $tax_amount_for_row_total + $custom_row_total;
                
                $connection->query(
                    "UPDATE `" . $_sales_order_item . "` "
                    . "SET price_incl_tax = '" . $parts[2]
                    . "', base_price_incl_tax = '" . $parts[2]
                    . "', base_row_total_incl_tax = '" . $order_total_with_tax
                    . "', row_total_incl_tax = '" . $order_total_with_tax
                    . "', row_total = '" . $order_total_without_tax
                    . "', base_row_total = '" . $order_total_without_tax
                    . "', tax_percent = '" . $tax_percent
                    . "', tax_amount = '" . $tax_amount_for_row_total
                    . "', base_tax_amount = '" . $tax_amount_for_row_total
                    . "', price = '" . $parts[2]
                    . "', base_price = '" . $parts[2]
                    . "' WHERE order_id = '" . $order1->getId()
                    . "' AND sku = '" . $parts[0] . "'");

                $connection->query(
                    "UPDATE `" . $_quote_item . "` SET "
                    . "row_total_with_discount = '" . $order_total_without_tax
                    . "', base_row_total = '" . $order_total_without_tax
                    . "', row_total = '" . $order_total_without_tax
                    . "', price = '" . $parts[2]
                    . "', base_price = '" . $parts[2]
                    . "', custom_price = '" . $parts[2]
                    . "', original_custom_price = '" . $parts[2]
                    . "', price_incl_tax = '" . $parts[2]
                    . "', base_price_incl_tax = '" . $parts[2]
                    . "', row_total_incl_tax = '" . $order_total_with_tax
                    . "', base_row_total_incl_tax = '" . $order_total_with_tax
                    . "', tax_percent = '" . $tax_percent
                    . "', tax_amount = '" . $tax_amount_for_row_total
                    . "', base_tax_amount = '" . $tax_amount_for_row_total
                    . "' WHERE item_id = '" . $item_id . "'");
                
                if($params['create_invoice'] == "true") {
                    $connection->query(
                        "UPDATE `" . $_sales_invoice_item . "` "
                        . "SET price_incl_tax = '" . $parts[2]
                        . "', base_price_incl_tax = '" . $parts[2]
                        . "', base_row_total_incl_tax = '" . $order_total_with_tax
                        . "', row_total_incl_tax = '" . $order_total_with_tax
                        . "', row_total = '" . $order_total_without_tax
                        . "', base_row_total = '" . $order_total_without_tax
                        . "', tax_amount = '" . $tax_amount_for_row_total
                        . "', base_tax_amount = '" . $tax_amount_for_row_total
                        . "', price = '" . $parts[2]
                        . "', base_price = '" . $parts[2]
                        . "' WHERE order_item_id = '" . $order1->getId()
                        . "' AND sku = '" . $parts[0] . "'");
                }
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING INVOICE PRICE: " . $e->getMessage()), $e);
                #Mage::log(sprintf('Order #' . $importData['order_id'] . $message), null, 'ce_order_import_errors.log');
            }
        }
        */
        //JUST UPDATE SHIPPING TOTAL
        if(isset($importData['subtotal']) && isset($importData['shipping_amount'])) {

            $customordergrandtotalamt        = $importData['subtotal'] + $importData['shipping_amount'] + $importData['tax_amount'];
            $custom_order_base_price_w_o_tax = $importData['subtotal'];
            $base_shipping_tax_amount        = $importData['shipping_amount'] - $importData['base_shipping_amount'];
            $base_tax_amount                 = $importData['tax_amount'];

            try {

                if($params['create_invoice'] == "true") {

                    $connection->query(
                    //update order totals
                        "UPDATE `" . $_sales_order . "` "
                        . "SET base_subtotal = '" . $custom_order_base_price_w_o_tax
                        . "', base_tax_amount = '" . $base_tax_amount
                        . "', tax_amount = '" . $base_tax_amount
                        . "', tax_invoiced = '" . $base_tax_amount
                        . "', base_grand_total = '" . $customordergrandtotalamt
                        . "', base_total_paid = '" . $customordergrandtotalamt
                        . "', total_paid = '" . $customordergrandtotalamt
                        . "', base_total_invoiced = '" . $customordergrandtotalamt
                        . "', total_invoiced = '" . $customordergrandtotalamt
                        . "', subtotal = '" . $custom_order_base_price_w_o_tax
                        . "', base_subtotal_incl_tax = '" . $importData['subtotal']
                        . "', subtotal_incl_tax = '" . $importData['subtotal']
                        . "', grand_total = '" . $customordergrandtotalamt
                        . "', base_shipping_amount = '" . $importData['base_shipping_amount']
                        . "', base_shipping_tax_amount = '" . $base_shipping_tax_amount
                        . "', shipping_tax_amount = '" . $base_shipping_tax_amount
                        . "', shipping_amount = '" . $importData['base_shipping_amount']
                        . "', shipping_invoiced = '" . $importData['shipping_amount']
                        . "', base_shipping_invoiced = '" . $importData['base_shipping_amount']
                        . "', shipping_incl_tax = '" . $importData['shipping_amount']
                        . "', base_shipping_incl_tax = '" . $importData['shipping_amount']
                        . "' WHERE entity_id = '" . $order1->getId()
                        . "' AND store_id = '" . $importData['store_id'] . "'");

                    //EXTRA DATA SET FOR PRODUCT/ORDER DATA ON INVOICES
                    $connection->query(
                        "UPDATE `" . $_sales_invoice . "` "
                        . "SET base_subtotal = '" . $custom_order_base_price_w_o_tax
                        . "', base_tax_amount = '" . $base_tax_amount
                        . "', tax_amount = '" . $base_tax_amount
                        . "', subtotal = '" . $custom_order_base_price_w_o_tax
                        . "', base_subtotal_incl_tax = '" . $importData['subtotal']
                        . "', subtotal_incl_tax = '" . $importData['subtotal']
                        . "', base_grand_total = '" . $customordergrandtotalamt
                        . "', grand_total = '" . $customordergrandtotalamt
                        . "', base_shipping_amount = '" . $importData['base_shipping_amount']
                        . "', shipping_amount = '" . $importData['base_shipping_amount']
                        . "', shipping_incl_tax = '" . $importData['shipping_amount']
                        . "', base_shipping_incl_tax = '" . $importData['shipping_amount']
                        . "' WHERE order_id = '" . $order1->getId()
                        . "' AND store_id = '" . $importData['store_id'] . "'");

                    $connection->query(
                        "UPDATE `" . $_sales_invoice_grid . "` "
                        . "SET grand_total = '" . $customordergrandtotalamt
                        . "' WHERE order_id = '" . $order1->getId()
                        . "' AND store_id = '" . $importData['store_id'] . "'");
                } else {
                    //, tax_percent = '" . $tax_percent . "' --removed no such column 1.6.2
                    $connection->query("UPDATE `" . $_sales_order . "` SET base_subtotal = '" . $custom_order_base_price_w_o_tax . "', base_tax_amount = '" . $base_tax_amount . "', tax_amount = '" . $base_tax_amount . "', base_grand_total = '" . $customordergrandtotalamt . "', subtotal = '" . $custom_order_base_price_w_o_tax . "', base_subtotal_incl_tax = '" . $importData['subtotal'] . "', subtotal_incl_tax = '" . $importData['subtotal'] . "', grand_total = '" . $customordergrandtotalamt . "', base_shipping_amount = '" . $importData['base_shipping_amount'] . "', base_shipping_tax_amount = '" . $base_shipping_tax_amount . "', shipping_tax_amount = '" . $base_shipping_tax_amount . "', shipping_amount = '" . $importData['base_shipping_amount'] . "', shipping_incl_tax = '" . $importData['shipping_amount'] . "', base_shipping_incl_tax = '" . $importData['shipping_amount'] . "' WHERE entity_id = '" . $order1->getId() . "' AND store_id = '" . $importData['store_id'] . "'");
                }
            } catch(\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " saving error: " . $e->getMessage()), $e);
                #Mage::log(sprintf('Order #' . $importData['order_id'] . ' saving error: %s', $e->getMessage()), null, 'ce_order_import_errors.log');

            }

            //Update tax amt
            $connection->query(
                "UPDATE `" . $_sales_order_tax . "` "
                . "SET amount = '" . $base_tax_amount
                . "', base_amount = '" . $base_tax_amount
                . "', base_real_amount = '" . $base_tax_amount
                . "'  WHERE order_id = '" . $order1->getId() . "'");

            //UPDATE FOR SALES GRID VIEW -- sales -> orders
            $connection->query(
                "UPDATE `" . $_sales_order_grid . "` "
                . "SET base_grand_total = '" . $customordergrandtotalamt
                . "', grand_total = '" . $customordergrandtotalamt
                . "' WHERE entity_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");

            $select_qry   = $connection->query("SELECT item_id FROM `" . $_sales_order_item . "` WHERE order_id = '" . $order1->getId() . "'");
            $newrowItemId = $select_qry->fetch();

            //Track the quantities ordered. Need the item_id as the key.
            $item_id = $newrowItemId['item_id'];
            $connection->query(
                "UPDATE `" . $_quote . "` "
                . "SET base_subtotal = '" . $custom_order_base_price_w_o_tax
                . "', base_grand_total = '" . $customordergrandtotalamt
                . "', subtotal = '" . $custom_order_base_price_w_o_tax
                . "', grand_total = '" . $customordergrandtotalamt
                . "', subtotal_with_discount = '" . $importData['subtotal']
                . "', base_subtotal_with_discount = '" . $importData['subtotal']
                . "' WHERE entity_id = '" . $order1->getQuoteId() . "'");
        }

        return $importData;
    }

    public function updateOrderCreationTime($importData, $connection, $resource, $order1)
    {

        $dateTime                         = strtotime($importData['created_at']);
        $_sales_flat_order                = $resource->getTableName('sales_order'); //gives table name with prefix
        $_sales_flat_order_grid           = $resource->getTableName('sales_order_grid');
        $_sales_flat_order_item           = $resource->getTableName('sales_order_item');
        $_sales_flat_order_status_history = $resource->getTableName('sales_order_status_history');

        try {

            $connection->query(
                "UPDATE `" . $_sales_flat_order . "` "
                . "SET created_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "', updated_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "' WHERE entity_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");

            $connection->query(
                "UPDATE `" . $_sales_flat_order_grid . "` "
                . "SET created_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "', updated_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "' WHERE entity_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");

            $connection->query(
                "UPDATE `" . $_sales_flat_order_item . "` "
                . "SET created_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "', updated_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "' WHERE order_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");

            $connection->query(
                "UPDATE `" . $_sales_flat_order_status_history . "` "
                . "SET created_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "' WHERE parent_id = '" . $order1->getId() . "'");
        } catch(\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR UPDATING ORDER CREATION TIME: " . $e->getMessage()), $e);
            #Mage::log(sprintf('Order #' . $importData['order_id'] . ' saving error: %s', $message), null, 'ce_order_import_errors.log');

        }
    }

    public function setOrderAsComplete($connection, $resource, $order1, $importData)

    {
        $_sales_order                = $resource->getTableName('sales_order'); //gives table name with prefix
        $_sales_order_grid           = $resource->getTableName('sales_order_grid');
        $_sales_order_status_history = $resource->getTableName('sales_order_status_history');

        try {

            $connection->query(
                "UPDATE `" . $_sales_order . "` "
                . "SET state = 'complete', "
                . "status = 'complete' "
                . "WHERE entity_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");

            $connection->query(
                "UPDATE `" . $_sales_order_grid . "` "
                . "SET status = 'complete' "
                . "WHERE entity_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");

            $connection->query(
                "UPDATE `" . $_sales_order_status_history . "` "
                . "SET status = 'complete' "
                . "WHERE parent_id = '" . $order1->getId() . "'");
        } catch(\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__("ERROR SETTING ORDER AS COMPLETE: " . $e->getMessage()), $e);
            #Mage::log(sprintf('Order #' . $importData['order_id'] . ' saving error: %s', message), null, 'ce_order_import_errors.log');

        }
        /*
        $order1->setStatus(Mage_Sales_Model_Order::STATE_COMPLETE);
        $order1->setState(Mage_Sales_Model_Order::STATE_COMPLETE, false);
        $order1->addStatusToHistory($order1->getStatus(), '', false);
        $order1->save();
        */
    }

    public function setAllOtherOrderStatus($connection, $resource, $order1, $importData)

    {

        $_sales_order                = $resource->getTableName('sales_order'); //gives table name with prefix
        $_sales_order_grid           = $resource->getTableName('sales_order_grid');
        $_sales_order_status_history = $resource->getTableName('sales_order_status_history');
        $importDataOrderStatus       = $importData['order_status'];

        try {
            //removed set state on first sql due to issue with order history on orders the dropdown being blank and also makes order not show on frontend
            $connection->query(
                "UPDATE `" . $_sales_order . "` "
                . "SET status = '" . $importDataOrderStatus . "' "
                . "WHERE entity_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");

            $connection->query(
                "UPDATE `" . $_sales_order_grid . "` "
                . "SET status = '" . $importDataOrderStatus . "' "
                . "WHERE entity_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");

            $connection->query(
                "UPDATE `" . $_sales_order_status_history . "` "
                . "SET status = '" . $importDataOrderStatus . "' "
                . "WHERE parent_id = '" . $order1->getId() . "'");
        } catch(\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__("ERROR SETTING ORDER AS STATUS: " . $e->getMessage()), $e);
            #Mage::log(sprintf('Order #' . $importData['order_id'] . ' saving error: %s', message), null, 'ce_order_import_errors.log');
        }
    }

    public function updateInvoiceDates($importData, $connection, $resource, $order1)
    {

        #$_sales_invoice      = $resource->getTableName('sales_invoice'); //gives table name with prefix
        $_sales_invoice_grid = $resource->getTableName('sales_invoice_grid');

        $dateTime = strtotime($importData['created_at']);
        try {
            /*
            $connection->query(
                "UPDATE `" . $_sales_invoice . "` "
                . "SET created_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "', updated_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "' WHERE order_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");
            $connection->query(
                "UPDATE `" . $_sales_invoice_grid . "` "
                . "SET created_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "', order_created_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "' WHERE order_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");
            */
            $connection->query(
                "UPDATE `" . $_sales_invoice_grid . "` "
                . "SET order_created_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "' WHERE order_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");
        } catch(\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__("ERROR UPDATING INVOICE DATES: " . $e->getMessage()), $e);
            #Mage::log(sprintf('Order #' . $importData['order_id'] . ' saving error: %s', $message), null, 'ce_order_import_errors.log');

        }
    }

    public function updateOrderCommentsHistory($importData, $order1, $connection, $resource)
    {
        if(isset($importData['additional_comments'])) {
            if($importData['additional_comments'] != "") {
                $_sales_order_status_history = $resource->getTableName('sales_order_status_history'); //gives table name with prefix
                $parts                       = explode('||', $importData['additional_comments']);

                foreach($parts as $individual_comment) {
                    $comment = explode('^^', $individual_comment);
                    try {

                        $insertComment = "INSERT INTO `" . $_sales_order_status_history . "` (parent_id, is_customer_notified, is_visible_on_front, comment, status, created_at) VALUES (\"" . $order1->getId() . "\",\"" . $comment[0] . "\",\"" . $comment[1] . "\",\"" . addslashes($comment[2]) . "\",\"" . $comment[3] . "\",\"" . str_replace('"','',$comment[4]) . "\")";

                        $connection->query($insertComment);
                    } catch(\Exception $e) {
                        throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR ADDING ORDER COMMENTS: " . $e->getMessage()), $e);
                        #Mage::log(sprintf('Order #' . $importData['order_id'] . ' ERROR ADDING ORDER COMMENTS: %s', $e->getMessage()), null, 'ce_order_import_errors.log');
                    }
                }
            }
        }
    }

    public function updateShippingDates($importData, $connection, $resource, $order1)
    {
        $dateTime             = strtotime($importData['created_at']);
        $_sales_shipment      = $resource->getTableName('sales_shipment'); //gives table name with prefix
        $_sales_shipment_grid = $resource->getTableName('sales_shipment_grid');

        try {
            $connection->query(
                "UPDATE `" . $_sales_shipment . "` "
                . "SET created_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "', updated_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "' WHERE order_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");

            $connection->query(
                "UPDATE `" . $_sales_shipment_grid . "` "
                . "SET created_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "', order_created_at = '" . date("Y-m-d H:i:s", $dateTime)
                . "' WHERE order_id = '" . $order1->getId()
                . "' AND store_id = '" . $importData['store_id'] . "'");
        } catch(\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__("ERROR UPDATING SHIPPING DATES: " . $e->getMessage()), $e);
            #Mage::log(sprintf('Order #' . $importData['order_id'] . ' saving error: %s', $message), null, 'ce_order_import_errors.log');
        }
    }

    protected function _getOrderCreateModel()
    {
        if(!isset($this->orderCreateModel)) {
            $this->orderCreateModel = $this->objectManager->get("CommerceExtensions\OrderImportExport\Model\Data\Import\Ordercreate");
        }

        return $this->orderCreateModel;
    }

    /**
     * Retrieve session object
     *
     * @return  Mage_Adminhtml_Model_Session_Quote
     */

    protected function _getSession()
    {
        #return Mage::getSingleton('adminhtml/session_quote');
        return $this->objectManager->get('\Magento\Backend\Model\Session\Quote');
    }

    /**
     * Initialize order creation session data
     *
     * @param   array $data
     *
     * @return  Mage_Adminhtml_Sales_Order_CreateController
     */

    protected function _initSession($data)
    {

        /**
         * Identify customer
         */

        if(!empty($data['customer_id'])) {
            $this->_getSession()->setCustomerId((int)$data['customer_id']);
        } else {
            $this->_getSession()->setCustomerId(false);
        }

        /**
         * Identify store
         */

        if(!empty($data['store_id'])) {
            $this->_getSession()->setStoreId((int)$data['store_id']);
        }

        return $this;
    }

    /**
     * Processing quote data
     *
     * @param   array $data
     *
     * @return  Yournamespace_Yourmodule_IndexController
     */

    protected function _processQuote($data = array(), $products_ordered, $importData, $params)
    {

        /**
         * Saving order data
         */

        if(!empty($data['order'])) {
            $this->_getOrderCreateModel()->importPostData($data['order']);
        }

        /**
         * init first billing address, need for virtual products
         */

        $this->_getOrderCreateModel()->getBillingAddress();
        $this->_getOrderCreateModel()->setShippingAsBilling(true);

        /**
         * Adding products to quote from special grid and
         */

        if(!empty($data['add_products'])) {
            $this->_getOrderCreateModel()->addProducts($data['add_products']);
            $this->_getOrderCreateModel()->getQuote()->setTotalsCollectedFlag(false)->collectTotals();
        }
        //sync product names/prices, and update shipping
        $itemCounter = 0;
        $baseSubTotal = 0;
        $subTotal = 0;
        $forceDataUpdate = false;
        
        foreach ($this->_getOrderCreateModel()->getQuote()->getAllItems() as $item) {
            #echo "SKU: " .$item->getSku();
            if (strpos($item->getSku(), 'I-oe_productstandin') !== false) {
                if(isset($products_ordered[$itemCounter])) {
                    $parts = explode(':', $products_ordered[$itemCounter]);
                    if(isset($parts[1])) { $part_qty = $parts[1]; } else { $part_qty = "1"; }
                    if(isset($parts[2]) && $parts[2] == "configurable" || isset($parts[2]) && $parts[2] == "simple" || isset($parts[2]) && $parts[2] == "bundle") {
                         $partsfornamepricing = explode('^', $products_ordered[$itemCounter]);
                         if(isset($partsfornamepricing[1])) {
                            $rowTotal          = $partsfornamepricing[1] * $part_qty;
                            $rowProductPrice   = $partsfornamepricing[1];
                         } else {
                            throw new \Magento\Framework\Exception\LocalizedException(__("Order #" . $importData['order_id'] . " ERROR [products_ordered] DOES NOT CONTAIN PRICE: " . $products_ordered[$itemCounter]));
                         }
                         if(isset($partsfornamepricing[2]) && $partsfornamepricing[2] != "") {
                            $rowProductName = $partsfornamepricing[2];
                         } else {
                            $rowProductName = $parts[3];
                         }
                         $rowProductSku = $parts[0];
                         //This is For Bundle Format w/ Pricing
                         if(isset($parts[6]) && $parts[6] != "") {
                            #$rowProductName = htmlspecialchars($parts[6], ENT_NOQUOTES, "UTF-8");
                            $rowProductSku  = $parts[3];
                            $rowProductName = $parts[6];
                         }
                    } else {
                        $rowProductSku     = $parts[0];
                        $rowProductPrice   = $parts[2];
                        $rowProductName    = $parts[3];
                        $rowTotal          = $part_qty * $parts[2];
                    }
                    $item->setSku($rowProductSku);
                    $item->setName($rowProductName);
                    $item->setPrice($rowProductPrice);
                    $item->setBasePrice($rowProductPrice);
                    $item->setOriginalPrice($rowProductPrice);
                    $item->setBaseOriginalPrice($rowProductPrice);
                    $item->setBaseRowTotal($rowTotal);
                    $item->setRowTotal($rowTotal);
                    $item->setBaseRowInvoiced($rowTotal);
                    
                    if($params['use_historic_tax'] == "true") {
                        if(isset($importData['tax_percent'])) {
                            $tax_percent = $importData['tax_percent'];
                            $decimalfortaxpercent = $tax_percent / 100;
                            #$tax_percent = "8";
                        } elseif(isset($importData['tax_amount']) && isset($importData['grand_total'])) {
                            $decimalfortaxpercent = $importData['tax_amount'] / $importData['grand_total'];
                            $tax_percent = round((float)$decimalfortaxpercent * 100 ) . '%';
                        }
                        $tax_amount_for_row_total = $rowTotal * $decimalfortaxpercent;
                        $item->setTaxPercent($tax_percent);
                        $item->setTaxAmount($tax_amount_for_row_total);
                        $rowTotalInclTax = $rowTotal + $tax_amount_for_row_total;
                    } else {
                        $rowTotalInclTax = $rowTotal;
                    }
                    
                    $item->setBasePriceInclTax($rowProductPrice);
                    $item->setPriceInclTax($rowProductPrice);
                    $item->setRowTotalInclTax($rowTotalInclTax); 
                    $item->setBaseRowTotalInclTax($rowTotalInclTax); 
                    $item->setRowInvoiced($rowTotalInclTax);
                    
                    //$importData['subtotal'] could set from csv but will auto calc instead
                    #$baseSubTotal = $baseSubTotal + $rowTotal;
                    #$subTotal = $subTotal + $rowTotalInclTax;
                    #$forceDataUpdate = true;
                    $itemCounter++;
                }
            }
        }
        #if($forceDataUpdate) {
            #$this->_getOrderCreateModel()->getQuote()->setSubtotal($subTotal)->setBaseSubtotal($baseSubTotal);
            #$this->_getOrderCreateModel()->getQuote()->collectTotals()->save();
            #$this->_getOrderCreateModel()->getQuote()->setTotalsCollectedFlag(false)->collectTotals();
        #}

        // you may need to use this if your having issues with tax rates not applying on shipping address only
        #$this->_getOrderCreateModel()->setShippingAddress($data['order']['shipping_address']);

        /**
         * Collecting shipping rates
         */

        $this->_getOrderCreateModel()->collectShippingRates();

        /**
         * Adding payment data
         */

        //was commented out
        if(!empty($data['payment'])) {
            $this->_getOrderCreateModel()->getQuote()->getPayment()->addData($data['payment']);
        }
        if($data['order']['customer_is_guest'] == 1) {
            $this->_getOrderCreateModel()->getQuote()->setCustomerIsGuest(true);
            $this->_getOrderCreateModel()->getQuote()->getCustomer()->setFirstname($data['order']['account']['firstname']);
            $this->_getOrderCreateModel()->getQuote()->getCustomer()->setLastname($data['order']['account']['lastname']);
        }
        
        $this->_getOrderCreateModel()->initRuleData()->saveQuote();
    }


    public function _array_replace()
    {

        $array = array();

        $n = func_num_args();

        while($n-- > 0) {

            $array += func_get_arg($n);
        }

        return $array;
    }
}