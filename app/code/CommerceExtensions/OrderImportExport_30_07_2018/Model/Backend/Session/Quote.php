<?php
namespace CommerceExtensions\OrderImportExport\Model\Backend\Session;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartManagementInterface;

class Quote extends \Magento\Backend\Model\Session\Quote {

/**
     * Quote model object
     *
     * @var \Magento\Quote\Model\Quote
     */
    protected $_quote;

    /**
     * Store model object
     *
     * @var \Magento\Store\Model\Store
     */
    protected $_store;

    /**
     * Order model object
     *
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Sales quote repository
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var GroupManagementInterface
     */
    protected $groupManagement;

    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    protected $quoteFactory;
	
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Session\SidResolverInterface $sidResolver,
        \Magento\Framework\Session\Config\ConfigInterface $sessionConfig,
        \Magento\Framework\Session\SaveHandlerInterface $saveHandler,
        \Magento\Framework\Session\ValidatorInterface $validator,
        \Magento\Framework\Session\StorageInterface $storage,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\App\State $appState,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        GroupManagementInterface $groupManagement,
        \Magento\Quote\Model\QuoteFactory $quoteFactory
    ) {
        $this->customerRepository = $customerRepository;
        $this->quoteRepository = $quoteRepository;
        $this->_orderFactory = $orderFactory;
        $this->_storeManager = $storeManager;
        $this->groupManagement = $groupManagement;
        $this->quoteFactory = $quoteFactory;
        parent::__construct(
            $request,
            $sidResolver,
            $sessionConfig,
            $saveHandler,
            $validator,
            $storage,
            $cookieManager,
            $cookieMetadataFactory,
            $appState,
			$customerRepository,
			$quoteRepository,
			$orderFactory,
			$storeManager,
			$groupManagement,
			$quoteFactory
        );
        if ($this->_storeManager->hasSingleStore()) {
            $this->setStoreId($this->_storeManager->getStore(true)->getId());
        }
    }
	
    public function unsetQuote()
    {
        if ($this->_quote !== null) {
            $this->_quote = null;
        }
        return $this->_quote;
    }
	
    public function getQuote()
    {
        if ($this->_quote === null) {
            $this->_quote = $this->quoteFactory->create();
            if ($this->getStoreId()) {
                if (!$this->getQuoteId()) {
                    $this->_quote->setCustomerGroupId($this->groupManagement->getDefaultGroup()->getId())
                        ->setIsActive(false)
                        ->setStoreId($this->getStoreId());
                    $this->quoteRepository->save($this->_quote);
                    $this->setQuoteId($this->_quote->getId());
                } else {
                    $this->_quote = $this->quoteRepository->get($this->getQuoteId(), [$this->getStoreId()]);
                    $this->_quote->setStoreId($this->getStoreId());
                }

                if ($this->getCustomerId() && $this->getCustomerId() != $this->_quote->getCustomerId()) {
                    $customer = $this->customerRepository->getById($this->getCustomerId());
                    $this->_quote->assignCustomer($customer);
                } else if ( $this->getCustomerId() == "") {
					//guest orders only
					$this->_quote->setStoreId($this->getStoreId())
							->setCustomerId(null)
							->setCustomerEmail($this->_quote->getBillingAddress()->getEmail())
							->setCustomerIsGuest(true)
							->setCustomerGroupId(\Magento\Customer\Model\GroupManagement::NOT_LOGGED_IN_ID);
                    $this->quoteRepository->save($this->_quote);
					$this->setQuoteId($this->_quote->getId());
				}
            }
            $this->_quote->setIgnoreOldQty(true);
            $this->_quote->setIsSuperMode(true);
			$this->_quote->getShippingAddress()->setShippingMethod('flatrate_flatrate');
			$this->_quote->setShippingMethod('flatrate_flatrate');
        }

        return $this->_quote;
    }
	
    /**
     * Retrieve store model object
     *
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        if ($this->_store === null) {
            $this->_store = $this->_storeManager->getStore($this->getStoreId());
            $currencyId = $this->getCurrencyId();
            if ($currencyId) {
                $this->_store->setCurrentCurrencyCode($currencyId);
            }
        }
        return $this->_store;
    }

    /**
     * Retrieve order model object
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        if ($this->_order === null) {
            $this->_order = $this->_orderFactory->create();
            if ($this->getOrderId()) {
                $this->_order->load($this->getOrderId());
            }
        }
        return $this->_order;
    }
}