<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Observer\GenerateSubscriptionsObserver;

/**
 * Context Class
 */
class Context
{
    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    private $helper;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\SubscriptionFactory
     */
    private $subscriptionFactory;

    /**
     * @var \Magento\Quote\Api\Data\CartInterfaceFactory
     */
    private $quoteFactory;

    /**
     * @var \Magento\Quote\Api\Data\AddressInterfaceFactory
     */
    private $quoteAddressFactory;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Magento\Framework\DataObject\Copy
     */
    private $objectCopyService;

    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Vault
     */
    private $vaultHelper;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface
     */
    private $subscriptionRepository;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $dateProcessor;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    private $emulator;

    /**
     * @var \Magento\Framework\App\ProductMetadata
     */
    private $productMetadata;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\QuoteManager
     */
    private $quoteManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\ItemManager
     */
    private $itemManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    private $config;

    /**
     * @var \Magento\Sales\Api\OrderCustomerManagementInterface
     */
    private $orderCustomerManager;

    /**
     * @var \ParadoxLabs\TokenBase\Helper\Data
     */
    private $tokenbaseHelper;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * GenerateSubscriptionsObserver context constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Helper\Data $helper
     * @param \ParadoxLabs\Subscriptions\Model\SubscriptionFactory $subscriptionFactory
     * @param \Magento\Quote\Api\Data\CartInterfaceFactory $quoteFactory
     * @param \Magento\Quote\Api\Data\AddressInterfaceFactory $quoteAddressFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\DataObject\Copy $objectCopyService
     * @param \ParadoxLabs\Subscriptions\Helper\Vault $vaultHelper
     * @param \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepository
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Store\Model\App\Emulation $emulator
     * @param \Magento\Framework\App\ProductMetadata $productMetadata
     * @param \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager
     * @param \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerManager
     * @param \ParadoxLabs\TokenBase\Helper\Data $tokenbaseHelper
     * @param \Magento\Customer\Model\Session $customerSession *Proxy
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Helper\Data $helper,
        \ParadoxLabs\Subscriptions\Model\SubscriptionFactory $subscriptionFactory,
        \Magento\Quote\Api\Data\CartInterfaceFactory $quoteFactory,
        \Magento\Quote\Api\Data\AddressInterfaceFactory $quoteAddressFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\DataObject\Copy $objectCopyService,
        \ParadoxLabs\Subscriptions\Helper\Vault $vaultHelper,
        \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Store\Model\App\Emulation $emulator,
        \Magento\Framework\App\ProductMetadata $productMetadata,
        \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager,
        \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager,
        \ParadoxLabs\Subscriptions\Model\Config $config,
        \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerManager,
        \ParadoxLabs\TokenBase\Helper\Data $tokenbaseHelper,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->helper = $helper;
        $this->subscriptionFactory = $subscriptionFactory;
        $this->quoteFactory = $quoteFactory;
        $this->quoteAddressFactory = $quoteAddressFactory;
        $this->customerRepository = $customerRepository;
        $this->objectCopyService = $objectCopyService;
        $this->vaultHelper = $vaultHelper;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->dateProcessor = $dateProcessor;
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->storeManager = $storeManager;
        $this->emulator = $emulator;
        $this->productMetadata = $productMetadata;
        $this->quoteManager = $quoteManager;
        $this->itemManager = $itemManager;
        $this->config = $config;
        $this->orderCustomerManager = $orderCustomerManager;
        $this->tokenbaseHelper = $tokenbaseHelper;
        $this->customerSession = $customerSession;
    }

    /**
     * Get helper
     *
     * @return \ParadoxLabs\Subscriptions\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * Get subscriptionFactory
     *
     * @return \ParadoxLabs\Subscriptions\Model\SubscriptionFactory
     */
    public function getSubscriptionFactory()
    {
        return $this->subscriptionFactory;
    }

    /**
     * Get quoteFactory
     *
     * @return \Magento\Quote\Api\Data\CartInterfaceFactory
     */
    public function getQuoteFactory()
    {
        return $this->quoteFactory;
    }

    /**
     * Get quoteAddressFactory
     *
     * @return \Magento\Quote\Api\Data\AddressInterfaceFactory
     */
    public function getQuoteAddressFactory()
    {
        return $this->quoteAddressFactory;
    }

    /**
     * Get customerRepository
     *
     * @return \Magento\Customer\Api\CustomerRepositoryInterface
     */
    public function getCustomerRepository()
    {
        return $this->customerRepository;
    }

    /**
     * Get objectCopyService
     *
     * @return \Magento\Framework\DataObject\Copy
     */
    public function getObjectCopyService()
    {
        return $this->objectCopyService;
    }

    /**
     * Get vaultHelper
     *
     * @return \ParadoxLabs\Subscriptions\Helper\Vault
     */
    public function getVaultHelper()
    {
        return $this->vaultHelper;
    }

    /**
     * Get subscriptionRepository
     *
     * @return \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface
     */
    public function getSubscriptionRepository()
    {
        return $this->subscriptionRepository;
    }

    /**
     * Get dateProcessor
     *
     * @return \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    public function getDateProcessor()
    {
        return $this->dateProcessor;
    }

    /**
     * Get cartRepository
     *
     * @return \Magento\Quote\Api\CartRepositoryInterface
     */
    public function getCartRepository()
    {
        return $this->cartRepository;
    }

    /**
     * Get orderRepository
     *
     * @return \Magento\Sales\Api\OrderRepositoryInterface
     */
    public function getOrderRepository()
    {
        return $this->orderRepository;
    }

    /**
     * Get storeManager
     *
     * @return \Magento\Store\Model\StoreManagerInterface
     */
    public function getStoreManager()
    {
        return $this->storeManager;
    }

    /**
     * Get emulator
     *
     * @return \Magento\Store\Model\App\Emulation
     */
    public function getEmulator()
    {
        return $this->emulator;
    }

    /**
     * Get productMetadata
     *
     * @return \Magento\Framework\App\ProductMetadata
     */
    public function getProductMetadata()
    {
        return $this->productMetadata;
    }

    /**
     * Get quoteManager
     *
     * @return \ParadoxLabs\Subscriptions\Model\Service\QuoteManager
     */
    public function getQuoteManager()
    {
        return $this->quoteManager;
    }

    /**
     * Get itemManager
     *
     * @return \ParadoxLabs\Subscriptions\Model\Service\ItemManager
     */
    public function getItemManager()
    {
        return $this->itemManager;
    }

    /**
     * Get config
     *
     * @return \ParadoxLabs\Subscriptions\Model\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get orderCustomerManager
     *
     * @return \Magento\Sales\Api\OrderCustomerManagementInterface
     */
    public function getOrderCustomerManager()
    {
        return $this->orderCustomerManager;
    }

    /**
     * Get tokenbaseHelper
     *
     * @return \ParadoxLabs\TokenBase\Helper\Data
     */
    public function getTokenbaseHelper()
    {
        return $this->tokenbaseHelper;
    }

    /**
     * Get customerSession
     *
     * @return \Magento\Customer\Model\Session
     */
    public function getCustomerSession()
    {
        return $this->customerSession;
    }
}
