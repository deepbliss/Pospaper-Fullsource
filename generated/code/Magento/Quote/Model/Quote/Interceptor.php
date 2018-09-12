<?php
namespace Magento\Quote\Model\Quote;

/**
 * Interceptor class for @see \Magento\Quote\Model\Quote
 */
class Interceptor extends \Magento\Quote\Model\Quote implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Quote\Model\QuoteValidator $quoteValidator, \Magento\Catalog\Helper\Product $catalogProduct, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\App\Config\ScopeConfigInterface $config, \Magento\Quote\Model\Quote\AddressFactory $quoteAddressFactory, \Magento\Customer\Model\CustomerFactory $customerFactory, \Magento\Customer\Api\GroupRepositoryInterface $groupRepository, \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory $quoteItemCollectionFactory, \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory, \Magento\Framework\Message\Factory $messageFactory, \Magento\Sales\Model\Status\ListFactory $statusListFactory, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Quote\Model\Quote\PaymentFactory $quotePaymentFactory, \Magento\Quote\Model\ResourceModel\Quote\Payment\CollectionFactory $quotePaymentCollectionFactory, \Magento\Framework\DataObject\Copy $objectCopyService, \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry, \Magento\Quote\Model\Quote\Item\Processor $itemProcessor, \Magento\Framework\DataObject\Factory $objectFactory, \Magento\Customer\Api\AddressRepositoryInterface $addressRepository, \Magento\Framework\Api\SearchCriteriaBuilder $criteriaBuilder, \Magento\Framework\Api\FilterBuilder $filterBuilder, \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory, \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter, \Magento\Quote\Model\Cart\CurrencyFactory $currencyFactory, \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor, \Magento\Quote\Model\Quote\TotalsCollector $totalsCollector, \Magento\Quote\Model\Quote\TotalsReader $totalsReader, \Magento\Quote\Model\ShippingFactory $shippingFactory, \Magento\Quote\Model\ShippingAssignmentFactory $shippingAssignmentFactory, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array(), \Magento\Sales\Model\OrderIncrementIdChecker $orderIncrementIdChecker = null)
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $quoteValidator, $catalogProduct, $scopeConfig, $storeManager, $config, $quoteAddressFactory, $customerFactory, $groupRepository, $quoteItemCollectionFactory, $quoteItemFactory, $messageFactory, $statusListFactory, $productRepository, $quotePaymentFactory, $quotePaymentCollectionFactory, $objectCopyService, $stockRegistry, $itemProcessor, $objectFactory, $addressRepository, $criteriaBuilder, $filterBuilder, $addressDataFactory, $customerDataFactory, $customerRepository, $dataObjectHelper, $extensibleDataObjectConverter, $currencyFactory, $extensionAttributesJoinProcessor, $totalsCollector, $totalsReader, $shippingFactory, $shippingAssignmentFactory, $resource, $resourceCollection, $data, $orderIncrementIdChecker);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrency()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCurrency');
        if (!$pluginInfo) {
            return parent::getCurrency();
        } else {
            return $this->___callPlugins('getCurrency', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrency(\Magento\Quote\Api\Data\CurrencyInterface $currency = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCurrency');
        if (!$pluginInfo) {
            return parent::setCurrency($currency);
        } else {
            return $this->___callPlugins('setCurrency', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItems');
        if (!$pluginInfo) {
            return parent::getItems();
        } else {
            return $this->___callPlugins('getItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setItems(array $items = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setItems');
        if (!$pluginInfo) {
            return parent::setItems($items);
        } else {
            return $this->___callPlugins('setItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCreatedAt');
        if (!$pluginInfo) {
            return parent::getCreatedAt();
        } else {
            return $this->___callPlugins('getCreatedAt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCreatedAt');
        if (!$pluginInfo) {
            return parent::setCreatedAt($createdAt);
        } else {
            return $this->___callPlugins('setCreatedAt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUpdatedAt');
        if (!$pluginInfo) {
            return parent::getUpdatedAt();
        } else {
            return $this->___callPlugins('getUpdatedAt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt($updatedAt)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setUpdatedAt');
        if (!$pluginInfo) {
            return parent::setUpdatedAt($updatedAt);
        } else {
            return $this->___callPlugins('setUpdatedAt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConvertedAt()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConvertedAt');
        if (!$pluginInfo) {
            return parent::getConvertedAt();
        } else {
            return $this->___callPlugins('getConvertedAt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setConvertedAt($convertedAt)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setConvertedAt');
        if (!$pluginInfo) {
            return parent::setConvertedAt($convertedAt);
        } else {
            return $this->___callPlugins('setConvertedAt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIsActive()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIsActive');
        if (!$pluginInfo) {
            return parent::getIsActive();
        } else {
            return $this->___callPlugins('getIsActive', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setIsActive($isActive)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setIsActive');
        if (!$pluginInfo) {
            return parent::setIsActive($isActive);
        } else {
            return $this->___callPlugins('setIsActive', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setIsVirtual($isVirtual)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setIsVirtual');
        if (!$pluginInfo) {
            return parent::setIsVirtual($isVirtual);
        } else {
            return $this->___callPlugins('setIsVirtual', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsCount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemsCount');
        if (!$pluginInfo) {
            return parent::getItemsCount();
        } else {
            return $this->___callPlugins('getItemsCount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setItemsCount($itemsCount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setItemsCount');
        if (!$pluginInfo) {
            return parent::setItemsCount($itemsCount);
        } else {
            return $this->___callPlugins('setItemsCount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsQty()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemsQty');
        if (!$pluginInfo) {
            return parent::getItemsQty();
        } else {
            return $this->___callPlugins('getItemsQty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setItemsQty($itemsQty)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setItemsQty');
        if (!$pluginInfo) {
            return parent::setItemsQty($itemsQty);
        } else {
            return $this->___callPlugins('setItemsQty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrigOrderId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOrigOrderId');
        if (!$pluginInfo) {
            return parent::getOrigOrderId();
        } else {
            return $this->___callPlugins('getOrigOrderId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOrigOrderId($origOrderId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setOrigOrderId');
        if (!$pluginInfo) {
            return parent::setOrigOrderId($origOrderId);
        } else {
            return $this->___callPlugins('setOrigOrderId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getReservedOrderId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getReservedOrderId');
        if (!$pluginInfo) {
            return parent::getReservedOrderId();
        } else {
            return $this->___callPlugins('getReservedOrderId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setReservedOrderId($reservedOrderId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setReservedOrderId');
        if (!$pluginInfo) {
            return parent::setReservedOrderId($reservedOrderId);
        } else {
            return $this->___callPlugins('setReservedOrderId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerIsGuest()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerIsGuest');
        if (!$pluginInfo) {
            return parent::getCustomerIsGuest();
        } else {
            return $this->___callPlugins('getCustomerIsGuest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerIsGuest($customerIsGuest)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerIsGuest');
        if (!$pluginInfo) {
            return parent::setCustomerIsGuest($customerIsGuest);
        } else {
            return $this->___callPlugins('setCustomerIsGuest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerNote()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerNote');
        if (!$pluginInfo) {
            return parent::getCustomerNote();
        } else {
            return $this->___callPlugins('getCustomerNote', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerNote($customerNote)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerNote');
        if (!$pluginInfo) {
            return parent::setCustomerNote($customerNote);
        } else {
            return $this->___callPlugins('setCustomerNote', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerNoteNotify()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerNoteNotify');
        if (!$pluginInfo) {
            return parent::getCustomerNoteNotify();
        } else {
            return $this->___callPlugins('getCustomerNoteNotify', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerNoteNotify($customerNoteNotify)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerNoteNotify');
        if (!$pluginInfo) {
            return parent::setCustomerNoteNotify($customerNoteNotify);
        } else {
            return $this->___callPlugins('setCustomerNoteNotify', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoreId');
        if (!$pluginInfo) {
            return parent::getStoreId();
        } else {
            return $this->___callPlugins('getStoreId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId($storeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStoreId');
        if (!$pluginInfo) {
            return parent::setStoreId($storeId);
        } else {
            return $this->___callPlugins('setStoreId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStore()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStore');
        if (!$pluginInfo) {
            return parent::getStore();
        } else {
            return $this->___callPlugins('getStore', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStore(\Magento\Store\Model\Store $store)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStore');
        if (!$pluginInfo) {
            return parent::setStore($store);
        } else {
            return $this->___callPlugins('setStore', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSharedStoreIds()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSharedStoreIds');
        if (!$pluginInfo) {
            return parent::getSharedStoreIds();
        } else {
            return $this->___callPlugins('getSharedStoreIds', func_get_args(), $pluginInfo);
        }
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
    public function loadByCustomer($customer)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadByCustomer');
        if (!$pluginInfo) {
            return parent::loadByCustomer($customer);
        } else {
            return $this->___callPlugins('loadByCustomer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadActive($quoteId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadActive');
        if (!$pluginInfo) {
            return parent::loadActive($quoteId);
        } else {
            return $this->___callPlugins('loadActive', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadByIdWithoutStore($quoteId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadByIdWithoutStore');
        if (!$pluginInfo) {
            return parent::loadByIdWithoutStore($quoteId);
        } else {
            return $this->___callPlugins('loadByIdWithoutStore', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function assignCustomer(\Magento\Customer\Api\Data\CustomerInterface $customer)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'assignCustomer');
        if (!$pluginInfo) {
            return parent::assignCustomer($customer);
        } else {
            return $this->___callPlugins('assignCustomer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function assignCustomerWithAddressChange(\Magento\Customer\Api\Data\CustomerInterface $customer, \Magento\Quote\Model\Quote\Address $billingAddress = null, \Magento\Quote\Model\Quote\Address $shippingAddress = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'assignCustomerWithAddressChange');
        if (!$pluginInfo) {
            return parent::assignCustomerWithAddressChange($customer, $billingAddress, $shippingAddress);
        } else {
            return $this->___callPlugins('assignCustomerWithAddressChange', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomer(\Magento\Customer\Api\Data\CustomerInterface $customer = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomer');
        if (!$pluginInfo) {
            return parent::setCustomer($customer);
        } else {
            return $this->___callPlugins('setCustomer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomer()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomer');
        if (!$pluginInfo) {
            return parent::getCustomer();
        } else {
            return $this->___callPlugins('getCustomer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerAddressData(array $addresses)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerAddressData');
        if (!$pluginInfo) {
            return parent::setCustomerAddressData($addresses);
        } else {
            return $this->___callPlugins('setCustomerAddressData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addCustomerAddress(\Magento\Customer\Api\Data\AddressInterface $address)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addCustomerAddress');
        if (!$pluginInfo) {
            return parent::addCustomerAddress($address);
        } else {
            return $this->___callPlugins('addCustomerAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateCustomerData(\Magento\Customer\Api\Data\CustomerInterface $customer)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'updateCustomerData');
        if (!$pluginInfo) {
            return parent::updateCustomerData($customer);
        } else {
            return $this->___callPlugins('updateCustomerData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerGroupId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerGroupId');
        if (!$pluginInfo) {
            return parent::getCustomerGroupId();
        } else {
            return $this->___callPlugins('getCustomerGroupId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerTaxClassId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerTaxClassId');
        if (!$pluginInfo) {
            return parent::getCustomerTaxClassId();
        } else {
            return $this->___callPlugins('getCustomerTaxClassId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerTaxClassId($customerTaxClassId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerTaxClassId');
        if (!$pluginInfo) {
            return parent::setCustomerTaxClassId($customerTaxClassId);
        } else {
            return $this->___callPlugins('setCustomerTaxClassId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressesCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAddressesCollection');
        if (!$pluginInfo) {
            return parent::getAddressesCollection();
        } else {
            return $this->___callPlugins('getAddressesCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBillingAddress()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBillingAddress');
        if (!$pluginInfo) {
            return parent::getBillingAddress();
        } else {
            return $this->___callPlugins('getBillingAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAddress()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingAddress');
        if (!$pluginInfo) {
            return parent::getShippingAddress();
        } else {
            return $this->___callPlugins('getShippingAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllShippingAddresses()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllShippingAddresses');
        if (!$pluginInfo) {
            return parent::getAllShippingAddresses();
        } else {
            return $this->___callPlugins('getAllShippingAddresses', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllAddresses()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllAddresses');
        if (!$pluginInfo) {
            return parent::getAllAddresses();
        } else {
            return $this->___callPlugins('getAllAddresses', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressById($addressId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAddressById');
        if (!$pluginInfo) {
            return parent::getAddressById($addressId);
        } else {
            return $this->___callPlugins('getAddressById', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressByCustomerAddressId($addressId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAddressByCustomerAddressId');
        if (!$pluginInfo) {
            return parent::getAddressByCustomerAddressId($addressId);
        } else {
            return $this->___callPlugins('getAddressByCustomerAddressId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAddressByCustomerAddressId($addressId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingAddressByCustomerAddressId');
        if (!$pluginInfo) {
            return parent::getShippingAddressByCustomerAddressId($addressId);
        } else {
            return $this->___callPlugins('getShippingAddressByCustomerAddressId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAddress($addressId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeAddress');
        if (!$pluginInfo) {
            return parent::removeAddress($addressId);
        } else {
            return $this->___callPlugins('removeAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllAddresses()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeAllAddresses');
        if (!$pluginInfo) {
            return parent::removeAllAddresses();
        } else {
            return $this->___callPlugins('removeAllAddresses', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addAddress(\Magento\Quote\Api\Data\AddressInterface $address)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addAddress');
        if (!$pluginInfo) {
            return parent::addAddress($address);
        } else {
            return $this->___callPlugins('addAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBillingAddress(\Magento\Quote\Api\Data\AddressInterface $address = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBillingAddress');
        if (!$pluginInfo) {
            return parent::setBillingAddress($address);
        } else {
            return $this->___callPlugins('setBillingAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingAddress(\Magento\Quote\Api\Data\AddressInterface $address = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingAddress');
        if (!$pluginInfo) {
            return parent::setShippingAddress($address);
        } else {
            return $this->___callPlugins('setShippingAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addShippingAddress(\Magento\Quote\Api\Data\AddressInterface $address)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addShippingAddress');
        if (!$pluginInfo) {
            return parent::addShippingAddress($address);
        } else {
            return $this->___callPlugins('addShippingAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsCollection($useCache = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemsCollection');
        if (!$pluginInfo) {
            return parent::getItemsCollection($useCache);
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
    public function hasItemsWithDecimalQty()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasItemsWithDecimalQty');
        if (!$pluginInfo) {
            return parent::hasItemsWithDecimalQty();
        } else {
            return $this->___callPlugins('hasItemsWithDecimalQty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasProductId($productId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasProductId');
        if (!$pluginInfo) {
            return parent::hasProductId($productId);
        } else {
            return $this->___callPlugins('hasProductId', func_get_args(), $pluginInfo);
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
    public function deleteItem(\Magento\Quote\Model\Quote\Item $item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'deleteItem');
        if (!$pluginInfo) {
            return parent::deleteItem($item);
        } else {
            return $this->___callPlugins('deleteItem', func_get_args(), $pluginInfo);
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
    public function removeAllItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeAllItems');
        if (!$pluginInfo) {
            return parent::removeAllItems();
        } else {
            return $this->___callPlugins('removeAllItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(\Magento\Quote\Model\Quote\Item $item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addItem');
        if (!$pluginInfo) {
            return parent::addItem($item);
        } else {
            return $this->___callPlugins('addItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addProduct(\Magento\Catalog\Model\Product $product, $request = null, $processMode = 'full')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addProduct');
        if (!$pluginInfo) {
            return parent::addProduct($product, $request, $processMode);
        } else {
            return $this->___callPlugins('addProduct', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateItem($itemId, $buyRequest, $params = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'updateItem');
        if (!$pluginInfo) {
            return parent::updateItem($itemId, $buyRequest, $params);
        } else {
            return $this->___callPlugins('updateItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemByProduct($product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemByProduct');
        if (!$pluginInfo) {
            return parent::getItemByProduct($product);
        } else {
            return $this->___callPlugins('getItemByProduct', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsSummaryQty()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemsSummaryQty');
        if (!$pluginInfo) {
            return parent::getItemsSummaryQty();
        } else {
            return $this->___callPlugins('getItemsSummaryQty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemVirtualQty()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemVirtualQty');
        if (!$pluginInfo) {
            return parent::getItemVirtualQty();
        } else {
            return $this->___callPlugins('getItemVirtualQty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentsCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPaymentsCollection');
        if (!$pluginInfo) {
            return parent::getPaymentsCollection();
        } else {
            return $this->___callPlugins('getPaymentsCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPayment()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPayment');
        if (!$pluginInfo) {
            return parent::getPayment();
        } else {
            return $this->___callPlugins('getPayment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPayment(\Magento\Quote\Api\Data\PaymentInterface $payment)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPayment');
        if (!$pluginInfo) {
            return parent::setPayment($payment);
        } else {
            return $this->___callPlugins('setPayment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removePayment()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removePayment');
        if (!$pluginInfo) {
            return parent::removePayment();
        } else {
            return $this->___callPlugins('removePayment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collectTotals()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'collectTotals');
        if (!$pluginInfo) {
            return parent::collectTotals();
        } else {
            return $this->___callPlugins('collectTotals', func_get_args(), $pluginInfo);
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
    public function addMessage($message, $index = 'error')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addMessage');
        if (!$pluginInfo) {
            return parent::addMessage($message, $index);
        } else {
            return $this->___callPlugins('addMessage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMessages');
        if (!$pluginInfo) {
            return parent::getMessages();
        } else {
            return $this->___callPlugins('getMessages', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getErrors');
        if (!$pluginInfo) {
            return parent::getErrors();
        } else {
            return $this->___callPlugins('getErrors', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setHasError($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setHasError');
        if (!$pluginInfo) {
            return parent::setHasError($flag);
        } else {
            return $this->___callPlugins('setHasError', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addErrorInfo($type = 'error', $origin = null, $code = null, $message = null, $additionalData = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addErrorInfo');
        if (!$pluginInfo) {
            return parent::addErrorInfo($type, $origin, $code, $message, $additionalData);
        } else {
            return $this->___callPlugins('addErrorInfo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeErrorInfosByParams($type, $params)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeErrorInfosByParams');
        if (!$pluginInfo) {
            return parent::removeErrorInfosByParams($type, $params);
        } else {
            return $this->___callPlugins('removeErrorInfosByParams', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeMessageByText($type, $text)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'removeMessageByText');
        if (!$pluginInfo) {
            return parent::removeMessageByText($type, $text);
        } else {
            return $this->___callPlugins('removeMessageByText', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reserveOrderId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'reserveOrderId');
        if (!$pluginInfo) {
            return parent::reserveOrderId();
        } else {
            return $this->___callPlugins('reserveOrderId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateMinimumAmount($multishipping = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validateMinimumAmount');
        if (!$pluginInfo) {
            return parent::validateMinimumAmount($multishipping);
        } else {
            return $this->___callPlugins('validateMinimumAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isVirtual()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isVirtual');
        if (!$pluginInfo) {
            return parent::isVirtual();
        } else {
            return $this->___callPlugins('isVirtual', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIsVirtual()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIsVirtual');
        if (!$pluginInfo) {
            return parent::getIsVirtual();
        } else {
            return $this->___callPlugins('getIsVirtual', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasVirtualItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasVirtualItems');
        if (!$pluginInfo) {
            return parent::hasVirtualItems();
        } else {
            return $this->___callPlugins('hasVirtualItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function merge(\Magento\Quote\Model\Quote $quote)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'merge');
        if (!$pluginInfo) {
            return parent::merge($quote);
        } else {
            return $this->___callPlugins('merge', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addressCollectionWasSet()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addressCollectionWasSet');
        if (!$pluginInfo) {
            return parent::addressCollectionWasSet();
        } else {
            return $this->___callPlugins('addressCollectionWasSet', func_get_args(), $pluginInfo);
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
    public function paymentsCollectionWasSet()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'paymentsCollectionWasSet');
        if (!$pluginInfo) {
            return parent::paymentsCollectionWasSet();
        } else {
            return $this->___callPlugins('paymentsCollectionWasSet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function currentPaymentWasSet()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'currentPaymentWasSet');
        if (!$pluginInfo) {
            return parent::currentPaymentWasSet();
        } else {
            return $this->___callPlugins('currentPaymentWasSet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCheckoutMethod($originalMethod = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCheckoutMethod');
        if (!$pluginInfo) {
            return parent::getCheckoutMethod($originalMethod);
        } else {
            return $this->___callPlugins('getCheckoutMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAddressesItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingAddressesItems');
        if (!$pluginInfo) {
            return parent::getShippingAddressesItems();
        } else {
            return $this->___callPlugins('getShippingAddressesItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCheckoutMethod($checkoutMethod)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCheckoutMethod');
        if (!$pluginInfo) {
            return parent::setCheckoutMethod($checkoutMethod);
        } else {
            return $this->___callPlugins('setCheckoutMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preventSaving()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'preventSaving');
        if (!$pluginInfo) {
            return parent::preventSaving();
        } else {
            return $this->___callPlugins('preventSaving', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isPreventSaving()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isPreventSaving');
        if (!$pluginInfo) {
            return parent::isPreventSaving();
        } else {
            return $this->___callPlugins('isPreventSaving', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isMultipleShippingAddresses()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isMultipleShippingAddresses');
        if (!$pluginInfo) {
            return parent::isMultipleShippingAddresses();
        } else {
            return $this->___callPlugins('isMultipleShippingAddresses', func_get_args(), $pluginInfo);
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
    public function setExtensionAttributes(\Magento\Quote\Api\Data\CartExtensionInterface $extensionAttributes)
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
    public function toArray(array $keys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toArray');
        if (!$pluginInfo) {
            return parent::toArray($keys);
        } else {
            return $this->___callPlugins('toArray', func_get_args(), $pluginInfo);
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
