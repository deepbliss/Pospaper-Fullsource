<?php

/**
 * Copyright © 2015 CommerceExtensions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace CommerceExtensions\OrderImportExport\Model\Data\Import;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Model\Metadata\Form as CustomerForm;
use Magento\Quote\Model\Quote\Item;

/**
 *  Order Create @ Magento\Sales\Model\AdminOrder
 */
 
class Ordercreate extends \Magento\Framework\DataObject {
	
	protected $_session;
    protected $_needCollect;
    protected $_customer;
    protected $_customerAddressForm;
    protected $_customerForm;
    protected $_errors = array();
    protected $_quote;
	
	/*
    public function __construct(
		\CommerceExtensions\OrderImportExport\Model\Data\Import\Sessionquote $quoteSession
    ) {
		 $this->_session = $quoteSession;
    }
	*/
	public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Sales\Model\Config $salesConfig,
        \CommerceExtensions\OrderImportExport\Model\Backend\Session\Quote $quoteSession, //\CommerceExtensions\OrderImportExport\Model\Data\Import\Sessionquote $quoteSession, //
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\DataObject\Copy $objectCopyService,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Sales\Model\AdminOrder\Product\Quote\Initializer $quoteInitializer,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressFactory,
        \Magento\Customer\Model\Metadata\FormFactory $metadataFormFactory,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Model\AdminOrder\EmailSender $emailSender,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Quote\Model\Quote\Item\Updater $quoteItemUpdater,
        \Magento\Framework\DataObject\Factory $objectFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory,
        \Magento\Customer\Model\Customer\Mapper $customerMapper,
        \Magento\Quote\Api\CartManagementInterface $quoteManagement,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Sales\Api\OrderManagementInterface $orderManagement,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        array $data = []
    ) {
        $this->_objectManager = $objectManager;
        $this->_eventManager = $eventManager;
        $this->_coreRegistry = $coreRegistry;
        $this->_salesConfig = $salesConfig;
        $this->_session = $quoteSession;
        $this->_logger = $logger;
        $this->_objectCopyService = $objectCopyService;
        $this->quoteInitializer = $quoteInitializer;
        $this->messageManager = $messageManager;
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
        $this->addressFactory = $addressFactory;
        $this->_metadataFormFactory = $metadataFormFactory;
        $this->customerFactory = $customerFactory;
        $this->groupRepository = $groupRepository;
        $this->_scopeConfig = $scopeConfig;
        $this->emailSender = $emailSender;
        $this->stockRegistry = $stockRegistry;
        $this->quoteItemUpdater = $quoteItemUpdater;
        $this->objectFactory = $objectFactory;
        $this->quoteRepository = $quoteRepository;
        $this->accountManagement = $accountManagement;
        $this->customerMapper = $customerMapper;
        $this->quoteManagement = $quoteManagement;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->orderManagement = $orderManagement;
        $this->quoteFactory = $quoteFactory;
        parent::__construct($data);
    }
	
	public function initRuleData()
    {
        $this->_coreRegistry->register(
            'rule_data',
            new \Magento\Framework\DataObject(
                [
                    'store_id' => $this->_session->getStore()->getId(),
                    'website_id' => $this->_session->getStore()->getWebsiteId(),
                    'customer_group_id' => $this->getCustomerGroupId()
                ]
            )
        );

        return $this;
    }

    /**
     * Set collect totals flag for quote
     *
     * @param   bool $flag
     * @return $this
     */
    public function setRecollect($flag)
    {
        $this->_needCollect = $flag;
        return $this;
    }
	
	/**
     * Quote saving
     *
     * @return $this
     */
    public function saveQuote()
    {
        if (!$this->getQuote()->getId()) {
            return $this;
        }

        if ($this->_needCollect) {
            $this->getQuote()->collectTotals();
        }

        $this->quoteRepository->save($this->getQuote());
        return $this;
    }

    /**
     * Retrieve session model object of quote
     *
     * @return \CommerceExtensions\OrderImportExport\Model\Backend\Session\Quotee
     */
    public function getSession()
    {
        return $this->_session;
    }

    /**
     * Retrieve quote object model
     *
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuote()
    {
        if (!$this->_quote) {
            $this->_quote = $this->getSession()->getQuote();
        }

        return $this->_quote;
    }
   

    /**
     * Retrieve current customer group ID.
     *
     * @return int
     */
    public function getCustomerGroupId()
    {
        $groupId = $this->getQuote()->getCustomerGroupId();
        if (!$groupId) {
            $groupId = $this->getSession()->getCustomerGroupId();
        }

        return $groupId;
    }

    /**
     * Add product to current order quote
     * $product can be either product id or product model
     * $config can be either buyRequest config, or just qty
     *
     * @param int|\Magento\Catalog\Model\Product $product
     * @param array|float|int|\Magento\Framework\DataObject $config
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addProduct($product, $config = 1)
    {
        if (!is_array($config) && !$config instanceof \Magento\Framework\DataObject) {
            $config = ['qty' => $config];
        }
        $config = new \Magento\Framework\DataObject($config);
		
        if (!$product instanceof \Magento\Catalog\Model\Product) {
            $productId = $product;
            $product = $this->_objectManager->create(
                'Magento\Catalog\Model\Product'
            )->setStore(
                $this->getSession()->getStore()
            )->setStoreId(
                $this->getSession()->getStoreId()
            )->load(
                $product
            );
            if (!$product->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('We could not add a product to cart by the ID "%1".', $productId)
                );
            }
        }

        $item = $this->quoteInitializer->init($this->getQuote(), $product, $config);

        if (is_string($item)) {
            throw new \Magento\Framework\Exception\LocalizedException(__($item));
        }
        $item->checkData();
        $this->setRecollect(true);

        return $this;
    }
	
    public function addProducts(array $products)
    {
		/*
         foreach ($products as $productId => $config) {
            $config['qty'] = isset($config['qty']) ? (double)$config['qty'] : 1;
            try {
                $this->addProduct($productId, $config);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                return $e;
            }
        }
        return $this;
		*/
		
        foreach ($products as $key => $config) { 
            $config['qty'] = isset($config['qty']) ? (double)$config['qty'] : 1;

            if(isset($config['productID'])){
                $productID = $config['productID'];
                unset($config['productID']);
            } else {
                $productID = $key;
            }
            
            try {
                $this->addProduct($productID, $config);//End Organic Bloom Addition
				
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
           		#throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
                $this->messageManager->addError($e->getMessage());
            }
            catch (\Exception $e){
                throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
                return $e;
            }
        }
        return $this;
    }
    /**
     * Update quantity of order quote items
     *
     * @param array $items
     * @return $this
     * @throws \Exception|\Magento\Framework\Exception\LocalizedException
     */
    public function updateQuoteItems($items)
    {
        if (!is_array($items)) {
            return $this;
        }

        try {
            foreach ($items as $itemId => $info) {
                if (!empty($info['configured'])) {
                    $item = $this->getQuote()->updateItem($itemId, $this->objectFactory->create($info));
                    $info['qty'] = (double)$item->getQty();
                } else {
                    $item = $this->getQuote()->getItemById($itemId);
                    if (!$item) {
                        continue;
                    }
                    $info['qty'] = (double)$info['qty'];
                }
                $this->quoteItemUpdater->update($item, $info);
                if ($item && !empty($info['action'])) {
                    $this->moveQuoteItem($item, $info['action'], $item->getQty());
                }
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->recollectCart();
            throw $e;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
        $this->recollectCart();

        return $this;
    }

    /**
     * Parse additional options and sync them with product options
     *
     * @param \Magento\Quote\Model\Quote\Item $item
     * @param string $additionalOptions
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _parseOptions(\Magento\Quote\Model\Quote\Item $item, $additionalOptions)
    {
        $productOptions = $this->_objectManager->get(
            'Magento\Catalog\Model\Product\Option\Type\DefaultType'
        )->setProduct(
            $item->getProduct()
        )->getProductOptions();

        $newOptions = [];
        $newAdditionalOptions = [];

        foreach (explode("\n", $additionalOptions) as $_additionalOption) {
            if (strlen(trim($_additionalOption))) {
                try {
                    if (strpos($_additionalOption, ':') === false) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('There is an error in one of the option rows.'));
                    }
                    list($label, $value) = explode(':', $_additionalOption, 2);
                } catch (\Exception $e) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('There is an error in one of the option rows.'));
                }
                $label = trim($label);
                $value = trim($value);
                if (empty($value)) {
                    continue;
                }

                if (array_key_exists($label, $productOptions)) {
                    $optionId = $productOptions[$label]['option_id'];
                    $option = $item->getProduct()->getOptionById($optionId);

                    $group = $this->_objectManager->get(
                        'Magento\Catalog\Model\Product\Option'
                    )->groupFactory(
                        $option->getType()
                    )->setOption(
                        $option
                    )->setProduct(
                        $item->getProduct()
                    );

                    $parsedValue = $group->parseOptionValue($value, $productOptions[$label]['values']);

                    if ($parsedValue !== null) {
                        $newOptions[$optionId] = $parsedValue;
                    } else {
                        $newAdditionalOptions[] = ['label' => $label, 'value' => $value];
                    }
                } else {
                    $newAdditionalOptions[] = ['label' => $label, 'value' => $value];
                }
            }
        }

        return ['options' => $newOptions, 'additional_options' => $newAdditionalOptions];
    }

    /**
     * Assign options to item
     *
     * @param \Magento\Quote\Model\Quote\Item $item
     * @param array $options
     * @return $this
     */
    protected function _assignOptionsToItem(\Magento\Quote\Model\Quote\Item $item, $options)
    {
        $optionIds = $item->getOptionByCode('option_ids');
        if ($optionIds) {
            foreach (explode(',', $optionIds->getValue()) as $optionId) {
                $item->removeOption('option_' . $optionId);
            }
            $item->removeOption('option_ids');
        }
        if ($item->getOptionByCode('additional_options')) {
            $item->removeOption('additional_options');
        }
        $item->save();
        if (!empty($options['options'])) {
            $item->addOption(
                new \Magento\Framework\DataObject(
                    [
                        'product' => $item->getProduct(),
                        'code' => 'option_ids',
                        'value' => implode(',', array_keys($options['options']))
                    ]
                )
            );

            foreach ($options['options'] as $optionId => $optionValue) {
                $item->addOption(
                    new \Magento\Framework\DataObject(
                        [
                            'product' => $item->getProduct(),
                            'code' => 'option_' . $optionId,
                            'value' => $optionValue
                        ]
                    )
                );
            }
        }
        if (!empty($options['additional_options'])) {
            $item->addOption(
                new \Magento\Framework\DataObject(
                    [
                        'product' => $item->getProduct(),
                        'code' => 'additional_options',
                        'value' => serialize($options['additional_options'])
                    ]
                )
            );
        }

        return $this;
    }

    /**
     * Prepare options array for info buy request
     *
     * @param \Magento\Quote\Model\Quote\Item $item
     * @return array
     */
    protected function _prepareOptionsForRequest($item)
    {
        $newInfoOptions = [];
        $optionIds = $item->getOptionByCode('option_ids');
        if ($optionIds) {
            foreach (explode(',', $optionIds->getValue()) as $optionId) {
                $option = $item->getProduct()->getOptionById($optionId);
                $optionValue = $item->getOptionByCode('option_' . $optionId)->getValue();

                $group = $this->_objectManager->get(
                    'Magento\Catalog\Model\Product\Option'
                )->groupFactory(
                    $option->getType()
                )->setOption(
                    $option
                )->setQuoteItem(
                    $item
                );

                $newInfoOptions[$optionId] = $group->prepareOptionValueForRequest($optionValue);
            }
        }

        return $newInfoOptions;
    }

    /**
     * Return valid price
     *
     * @param float|int $price
     * @return float|int
     */
    protected function _parseCustomPrice($price)
    {
        $price = $this->_objectManager->get('Magento\Framework\Locale\FormatInterface')->getNumber($price);
        $price = $price > 0 ? $price : 0;

        return $price;
    }

    /**
     * Retrieve oreder quote shipping address
     *
     * @return \Magento\Quote\Model\Quote\Address
     */
    public function getShippingAddress()
    {
        return $this->getQuote()->getShippingAddress();
    }

    /**
     * Return Customer (Checkout) Form instance
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @return CustomerForm
     */
    protected function _createCustomerForm(\Magento\Customer\Api\Data\CustomerInterface $customer)
    {
        $customerForm = $this->_metadataFormFactory->create(
            \Magento\Customer\Api\CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            'adminhtml_checkout',
            $this->customerMapper->toFlatArray($customer),
            false,
            CustomerForm::DONT_IGNORE_INVISIBLE
        );

        return $customerForm;
    }

    /**
     * Set and validate Quote address
     * All errors added to _errors
     *
     * @param \Magento\Quote\Model\Quote\Address $address
     * @param array $data
     * @return $this
     */
    protected function _setQuoteAddress(\Magento\Quote\Model\Quote\Address $address, array $data)
    {
        $isAjax = !$this->getIsValidate();

        // Region is a Data Object, so it is represented by an array. validateData() doesn't understand arrays, so we
        // need to merge region data with address data. This is going to be removed when we switch to use address Data
        // Object instead of the address model.
        // Note: if we use getRegion() here it will pull region from db using the region_id
        $data = isset($data['region']) && is_array($data['region']) ? array_merge($data, $data['region']) : $data;

        $addressForm = $this->_metadataFormFactory->create(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            'adminhtml_customer_address',
            $data,
            $isAjax,
            CustomerForm::DONT_IGNORE_INVISIBLE,
            []
        );

        // prepare request
        // save original request structure for files
        if ($address->getAddressType() == \Magento\Quote\Model\Quote\Address::TYPE_SHIPPING) {
            $requestData = ['order' => ['shipping_address' => $data]];
            $requestScope = 'order/shipping_address';
        } else {
            $requestData = ['order' => ['billing_address' => $data]];
            $requestScope = 'order/billing_address';
        }
        $request = $addressForm->prepareRequest($requestData);
        $addressData = $addressForm->extractData($request, $requestScope);
        if ($this->getIsValidate()) {
            $errors = $addressForm->validateData($addressData);
            if ($errors !== true) {
                if ($address->getAddressType() == \Magento\Quote\Model\Quote\Address::TYPE_SHIPPING) {
                    $typeName = __('Shipping Address: ');
                } else {
                    $typeName = __('Billing Address: ');
                }
                foreach ($errors as $error) {
                    $this->_errors[] = $typeName . $error;
                }
                $address->setData($addressForm->restoreData($addressData));
            } else {
                $address->setData($addressForm->compactData($addressData));
            }
        } else {
            $address->addData($addressForm->restoreData($addressData));
        }

        return $this;
    }

    /**
     * Set shipping address into quote
     *
     * @param \Magento\Quote\Model\Quote\Address|array $address
     * @return $this
     */
    public function setShippingAddress($address)
    {
        if (is_array($address)) {
            $shippingAddress = $this->_objectManager->create(
                'Magento\Quote\Model\Quote\Address'
            )->setData(
                $address
            )->setAddressType(
                \Magento\Quote\Model\Quote\Address::TYPE_SHIPPING
            );
            if (!$this->getQuote()->isVirtual()) {
                $this->_setQuoteAddress($shippingAddress, $address);
            }
            /**
             * save_in_address_book is not a valid attribute and is filtered out by _setQuoteAddress,
             * that is why it should be added after _setQuoteAddress call
             */
            $saveInAddressBook = (int)(!empty($address['save_in_address_book']));
            $shippingAddress->setData('save_in_address_book', $saveInAddressBook);
        }
        if ($address instanceof \Magento\Quote\Model\Quote\Address) {
            $shippingAddress = $address;
        }

        $this->setRecollect(true);
        $this->getQuote()->setShippingAddress($shippingAddress);

        return $this;
    }

    /**
     * Set shipping anddress to be same as billing
     *
     * @param bool $flag If true - don't save in address book and actually copy data across billing and shipping
     *                   addresses
     * @return $this
     */
    public function setShippingAsBilling($flag)
    {
        if ($flag) {
            $tmpAddress = clone $this->getBillingAddress();
            $tmpAddress->unsAddressId()->unsAddressType();
            $data = $tmpAddress->getData();
            $data['save_in_address_book'] = 0;
            // Do not duplicate address (billing address will do saving too)
            $this->getShippingAddress()->addData($data);
        }
        $this->getShippingAddress()->setSameAsBilling($flag);
        $this->setRecollect(true);
        return $this;
    }

    /**
     * Retrieve quote billing address
     *
     * @return \Magento\Quote\Model\Quote\Address
     */
    public function getBillingAddress()
    {
        return $this->getQuote()->getBillingAddress();
    }

    /**
     * Set billing address into quote
     *
     * @param array $address
     * @return $this
     */
    public function setBillingAddress($address)
    {
        if (is_array($address)) {
            $billingAddress = $this->_objectManager->create(
                'Magento\Quote\Model\Quote\Address'
            )->setData(
                $address
            )->setAddressType(
                \Magento\Quote\Model\Quote\Address::TYPE_BILLING
            );
            $this->_setQuoteAddress($billingAddress, $address);
            /**
             * save_in_address_book is not a valid attribute and is filtered out by _setQuoteAddress,
             * that is why it should be added after _setQuoteAddress call
             */
            $saveInAddressBook = (int)(!empty($address['save_in_address_book']));
            $billingAddress->setData('save_in_address_book', $saveInAddressBook);

            if (!$this->getQuote()->isVirtual() && $this->getShippingAddress()->getSameAsBilling()) {
                $shippingAddress = clone $billingAddress;
                $shippingAddress->setSameAsBilling(true);
                $shippingAddress->setSaveInAddressBook(false);
                $address['save_in_address_book'] = 0;
                $this->setShippingAddress($address);
            }

            $this->getQuote()->setBillingAddress($billingAddress);
        }

        return $this;
    }

    /**
     * Set shipping method
     *
     * @param string $method
     * @return $this
     */
    public function setShippingMethod($method)
    {
        $this->getShippingAddress()->setShippingMethod($method);
        $this->setRecollect(true);

        return $this;
    }

    /**
     * Empty shipping method and clear shipping rates
     *
     * @return $this
     */
    public function resetShippingMethod()
    {
        $this->getShippingAddress()->setShippingMethod(false);
        $this->getShippingAddress()->removeAllShippingRates();

        return $this;
    }

    /**
     * Collect shipping data for quote shipping address
     *
     * @return $this
     */
    public function collectShippingRates()
    {
        $this->getQuote()->getShippingAddress()->setCollectShippingRates(true);
        $this->collectRates();

        return $this;
    }
	/**
     * Calculate totals
     *
     * @return void
     */
    public function collectRates()
    {
        $this->getQuote()->collectTotals();
    }

    /**
     * Set payment method into quote
     *
     * @param string $method
     * @return $this
     */
    public function setPaymentMethod($method)
    {
        $this->getQuote()->getPayment()->setMethod($method);
        return $this;
    }

    /**
     * Set payment data into quote
     *
     * @param array $data
     * @return $this
     */
    public function setPaymentData($data)
    {
        if (!isset($data['method'])) {
            $data['method'] = $this->getQuote()->getPayment()->getMethod();
        }
        if ($data['method'] == 'purchaseorder' || $data['method'] == 'banktransfer' || $data['method'] == 'checkmo') {
		    $myotherdata = $this->getQuote()->getPayment();
            $this->paymentImportData($myotherdata, $data);
        } else {
			// 'additional_information' => array(serialize($data['method']))
			$data = array(
			   'method' => 'imported_placeholder',
			   'additional_information' => array("assign_payment_method" => $data['method'], "assign_transactions" => $data['method_additional_data'])
			);
			$myotherdata = $this->getQuote()->getPayment();
			#$myotherdata->setTitle($data['method']);
			$this->paymentImportData($myotherdata,$data);
        }
        $this->getQuote()->getPayment()->importData($data);
		
        return $this;
    
	}
	
	public function paymentImportData(&$payment, array $data)
    {
        #$data = new Varien_Object($data);
        $data = new \Magento\Framework\DataObject($data);

        $payment->setMethod($data->getMethod());
		#$payment->setAdditionalInformation($data->getAdditionalInformation());
        #print_r($payment->getData());
        $method = $payment->getMethodInstance();
        #$method->setTitle('insert your title here');
        #$method->setTitle('tes3t');
        #$method->setTitle($data['additional_information']);
		#echo "DONE";
        #print_r($method->getData());
		#echo "DONE2";
		#exit;
        $method->assignData($data);

        return $payment;
    }
    /**
     * Add coupon code to the quote
     *
     * @param string $code
     * @return $this
     */
    public function applyCoupon($code)
    {
        $code = trim((string)$code);
        $this->getQuote()->setCouponCode($code);
        $this->setRecollect(true);

        return $this;
    }

    /**
     * Add account data to quote
     *
     * @param array $accountData
     * @return $this
     */
    public function setAccountData($accountData)
    {
        $customer = $this->getQuote()->getCustomer();
        $form = $this->_createCustomerForm($customer);

        // emulate request
        $request = $form->prepareRequest($accountData);
        $data = $form->extractData($request);
        $data = $form->restoreData($data);
        $customer = $this->customerFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $customer,
            $data,
            '\Magento\Customer\Api\Data\CustomerInterface'
        );
        $this->getQuote()->updateCustomerData($customer);
        $data = [];

        $customerData = $this->customerMapper->toFlatArray($customer);
        foreach ($form->getAttributes() as $attribute) {
            $code = sprintf('customer_%s', $attribute->getAttributeCode());
            $data[$code] = isset($customerData[$attribute->getAttributeCode()])
                ? $customerData[$attribute->getAttributeCode()]
                : null;
        }

        if (isset($data['customer_group_id'])) {
            $customerGroup = $this->groupRepository->getById($data['customer_group_id']);
            $data['customer_tax_class_id'] = $customerGroup->getTaxClassId();
            $this->setRecollect(true);
        }

        $this->getQuote()->addData($data);

        return $this;
    }

    /**
     * Parse data retrieved from request
     *
     * @param   array $data
     * @return  $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function importPostData($data)
    {
        if (is_array($data)) {
            $this->addData($data);
        } else {
            return $this;
        }

        if (isset($data['account'])) {
            $this->setAccountData($data['account']);
        }

        if (isset($data['comment'])) {
            $this->getQuote()->addData($data['comment']);
            if (empty($data['comment']['customer_note_notify'])) {
                $this->getQuote()->setCustomerNoteNotify(false);
            } else {
                $this->getQuote()->setCustomerNoteNotify(true);
            }
        }

        if (isset($data['billing_address'])) {
            $this->setBillingAddress($data['billing_address']);
        }

        if (isset($data['shipping_address'])) {
            $this->setShippingAddress($data['shipping_address']);
        }

        if (isset($data['shipping_method'])) {
            $this->setShippingMethod($data['shipping_method']);
        }

        //if (isset($data['payment_method'])) {
            //$this->setPaymentMethod($data['payment_method']);
        //}

        if (isset($data['coupon']['code'])) {
            $this->applyCoupon($data['coupon']['code']);
        }

        return $this;
    }
    /**
     * Check whether we need to create new customer (for another website) during order creation
     *
     * @param \Magento\Store\Model\Store $store
     * @return bool
     */
    protected function _customerIsInStore($store)
    {
        $customerId = (int)$this->getSession()->getCustomerId();
        $customer = $this->customerRepository->getById($customerId);

        return $customer->getWebsiteId() == $store->getWebsiteId()
            || $this->accountManagement->isCustomerInStore($customer->getWebsiteId(), $store->getId());
    }

    /**
     * Set and validate Customer data. Return the updated Data Object merged with the account data
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    protected function _validateCustomerData(\Magento\Customer\Api\Data\CustomerInterface $customer)
    {
        $form = $this->_createCustomerForm($customer);
        // emulate request
        $request = $form->prepareRequest(['order' => $this->getData()]);
        $data = $form->extractData($request, 'order/account');
        $validationResults = $this->accountManagement->validate($customer);
        if (!$validationResults->isValid()) {
            $errors = $validationResults->getMessages();
            if (is_array($errors)) {
                foreach ($errors as $error) {
                    $this->_errors[] = $error;
                }
            }
        }
        $data = $form->restoreData($data);
        foreach ($data as $key => $value) {
            if (!is_null($value)) {
                unset($data[$key]);
            }
        }

        $this->dataObjectHelper->populateWithArray(
            $customer,
            $data,
            '\Magento\Customer\Api\Data\CustomerInterface'
        );
        return $customer;
    }

    /**
     * Prepare customer data for order creation.
     *
     * Create customer if not created using data from customer form.
     * Create customer billing/shipping address if necessary using data from customer address forms.
     * Set customer data to quote.
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function _prepareCustomer()
    {
        if ($this->getQuote()->getCustomerIsGuest()) {
            return $this;
        }
        /** @var $store \Magento\Store\Model\Store */
        $store = $this->getSession()->getStore();
        $customer = $this->getQuote()->getCustomer();
        if ($customer->getId() && !$this->_customerIsInStore($store)) {
            /** Create a new customer record if it is not available in the specified store */
            /** Unset customer ID to ensure that new customer will be created */
            $customer->setId(null)
                ->setStoreId($store->getId())
                ->setWebsiteId($store->getWebsiteId())
                ->setCreatedAt(null);
            $customer = $this->_validateCustomerData($customer);
        } else if (!$customer->getId()) {
            /** Create new customer */
            $customerBillingAddressDataObject = $this->getBillingAddress()->exportCustomerAddress();
            $customer->setSuffix($customerBillingAddressDataObject->getSuffix())
                ->setFirstname($customerBillingAddressDataObject->getFirstname())
                ->setLastname($customerBillingAddressDataObject->getLastname())
                ->setMiddlename($customerBillingAddressDataObject->getMiddlename())
                ->setPrefix($customerBillingAddressDataObject->getPrefix())
                ->setStoreId($store->getId())
                ->setEmail($this->_getNewCustomerEmail());
            $customer = $this->_validateCustomerData($customer);
        }
        $this->getQuote()->setCustomer($customer);

        if ($this->getBillingAddress()->getSaveInAddressBook()) {
            $this->_prepareCustomerAddress($this->getQuote()->getCustomer(), $this->getBillingAddress());
        }
        if (!$this->getQuote()->isVirtual() && $this->getShippingAddress()->getSaveInAddressBook()) {
            $this->_prepareCustomerAddress($this->getQuote()->getCustomer(), $this->getShippingAddress());
        }
        $this->getQuote()->updateCustomerData($this->getQuote()->getCustomer());

        $customer = $this->getQuote()->getCustomer();
        $origAddresses = $customer->getAddresses(); // save original addresses
        $customer->setAddresses([]);
        $customerData = $this->customerMapper->toFlatArray($customer);
        $customer->setAddresses($origAddresses); // restore original addresses
        foreach ($this->_createCustomerForm($customer)->getUserAttributes() as $attribute) {
            if (isset($customerData[$attribute->getAttributeCode()])) {
                $quoteCode = sprintf('customer_%s', $attribute->getAttributeCode());
                $this->getQuote()->setData($quoteCode, $customerData[$attribute->getAttributeCode()]);
            }
        }

        return $this;
    }

    /**
     * Create customer address and save it in the quote so that it can be used to persist later.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param \Magento\Quote\Model\Quote\Address $quoteCustomerAddress
     * @return void
     * @throws \InvalidArgumentException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _prepareCustomerAddress($customer, $quoteCustomerAddress)
    {
        // Possible that customerId is null for new customers
        $quoteCustomerAddress->setCustomerId($customer->getId());
        $customerAddress = $quoteCustomerAddress->exportCustomerAddress();
        $quoteAddressId = $quoteCustomerAddress->getCustomerAddressId();
        $addressType = $quoteCustomerAddress->getAddressType();
        if ($quoteAddressId) {
            /** Update existing address */
            $existingAddressDataObject = $this->addressRepository->getById($quoteAddressId);
            /** Update customer address data */
            $this->dataObjectHelper->mergeDataObjects(
                get_class($existingAddressDataObject),
                $existingAddressDataObject,
                $customerAddress
            );
            $customerAddress = $existingAddressDataObject;
        } elseif ($addressType == \Magento\Quote\Model\Quote\Address::ADDRESS_TYPE_SHIPPING) {
            try {
                $billingAddressDataObject = $this->accountManagement->getDefaultBillingAddress($customer->getId());
            } catch (\Exception $e) {
                /** Billing address does not exist. */
            }
            $isShippingAsBilling = $quoteCustomerAddress->getSameAsBilling();
            if (isset($billingAddressDataObject) && $isShippingAsBilling) {
                /** Set existing billing address as default shipping */
                $customerAddress = $billingAddressDataObject;
                $customerAddress->setIsDefaultShipping(true);
            }
        }

        switch ($addressType) {
            case \Magento\Quote\Model\Quote\Address::ADDRESS_TYPE_BILLING:
                if (is_null($customer->getDefaultBilling())) {
                    $customerAddress->setIsDefaultBilling(true);
                }
                break;
            case \Magento\Quote\Model\Quote\Address::ADDRESS_TYPE_SHIPPING:
                if (is_null($customer->getDefaultShipping())) {
                    $customerAddress->setIsDefaultShipping(true);
                }
                break;
            default:
                throw new \InvalidArgumentException('Customer address type is invalid.');
        }
        $this->getQuote()->setCustomer($customer);
        $this->getQuote()->addCustomerAddress($customerAddress);
    }

    /**
     * Prepare item otions
     *
     * @return $this
     */
    protected function _prepareQuoteItems()
    {
        foreach ($this->getQuote()->getAllItems() as $item) {
            $options = [];
            $productOptions = $item->getProduct()->getTypeInstance()->getOrderOptions($item->getProduct());
            if ($productOptions) {
                $productOptions['info_buyRequest']['options'] = $this->_prepareOptionsForRequest($item);
                $options = $productOptions;
            }
            $addOptions = $item->getOptionByCode('additional_options');
            if ($addOptions) {
                $options['additional_options'] = unserialize($addOptions->getValue());
            }
            $item->setProductOrderOptions($options);
        }
        return $this;
    }
	/**
     * Create new order
     *
     * @return \Magento\Sales\Model\Order
     */
    public function createOrder()
    {
        $this->_prepareCustomer();
        $this->_validate();
        $quote = $this->getQuote();
        $this->_prepareQuoteItems();

        $orderData = [];
        if ($this->getSession()->getOrder()->getId()) {
            $oldOrder = $this->getSession()->getOrder();
            $originalId = $oldOrder->getOriginalIncrementId();
            if (!$originalId) {
                $originalId = $oldOrder->getIncrementId();
            }
            $orderData = [
                'original_increment_id' => $originalId,
                'relation_parent_id' => $oldOrder->getId(),
                'relation_parent_real_id' => $oldOrder->getIncrementId(),
                'edit_increment' => $oldOrder->getEditIncrement() + 1,
                'increment_id' => $originalId . '-' . ($oldOrder->getEditIncrement() + 1)
            ];
            $quote->setReservedOrderId($orderData['increment_id']);
        }
        $order = $this->quoteManagement->submit($quote, $orderData);

        if ($this->getSession()->getOrder()->getId()) {
            $oldOrder = $this->getSession()->getOrder();
            $oldOrder->setRelationChildId($order->getId());
            $oldOrder->setRelationChildRealId($order->getIncrementId());
            $this->orderManagement->cancel($oldOrder->getEntityId());
            $order->save();
        }
        if ($this->getSendConfirmation()) {
            $this->emailSender->send($order);
        }
		
        $this->_quote = null;
        $this->getSession()->unsetQuote();

        $this->_eventManager->dispatch('checkout_submit_all_after', ['order' => $order, 'quote' => $quote]);

        return $order;
    }
	
    /**
     * Validate quote data before order creation
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _validate()
    {
        $customerId = $this->getSession()->getCustomerId();
        if (is_null($customerId)) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Please select a customer'));
        }

        if (!$this->getSession()->getStore()->getId()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Please select a store'));
        }
        $items = $this->getQuote()->getAllItems();

        if (count($items) == 0) {
            $this->_errors[] = __('Please specify order items.');
        }

        foreach ($items as $item) {
            $messages = $item->getMessage(false);
            if ($item->getHasError() && is_array($messages) && !empty($messages)) {
                $this->_errors = array_merge($this->_errors, $messages);
            }
        }

        if (!$this->getQuote()->isVirtual()) {
            if (!$this->getQuote()->getShippingAddress()->getShippingMethod()) {
                $this->_errors[] = __('Please specify a shipping method.');
            }
        }

        if (!$this->getQuote()->getPayment()->getMethod()) {
            $this->_errors[] = __('Please specify a payment method.');
        } else {
            $method = $this->getQuote()->getPayment()->getMethodInstance();
            if (!$method->isAvailable($this->getQuote())) {
                $this->_errors[] = __('This payment method is not available.');
            } else {
                try {
                    $method->validate();
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->_errors[] = $e->getMessage();
                }
            }
        }
        if (!empty($this->_errors)) {
            foreach ($this->_errors as $error) {
                $this->messageManager->addError($error);
            }
            throw new \Magento\Framework\Exception\LocalizedException(__('Validation is failed.'));
        }

        return $this;
    }

    /**
     * Retrieve or generate new customer email.
     *
     * @return string
     */
    protected function _getNewCustomerEmail()
    {
        $email = $this->getData('account/email');
        if (empty($email)) {
            $host = $this->_scopeConfig->getValue(
                self::XML_PATH_DEFAULT_EMAIL_DOMAIN,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $account = time();
            $email = $account . '@' . $host;
            $account = $this->getData('account');
            $account['email'] = $email;
            $this->setData('account', $account);
        }

        return $email;
    }
	
}