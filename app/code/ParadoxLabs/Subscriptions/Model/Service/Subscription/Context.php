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

namespace ParadoxLabs\Subscriptions\Model\Service\Subscription;

/**
 * Context Class
 */
class Context
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Magento\Framework\DataObject\Copy
     */
    private $objectCopyService;

    /**
     * @var \ParadoxLabs\TokenBase\Api\CardRepositoryInterface
     */
    private $cardRepository;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var \Magento\Quote\Model\QuoteManagement
     */
    private $quoteManagement;

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
     * @var \Magento\Customer\Api\AddressRepositoryInterface
     */
    private $customerAddressRepository;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    private $eventManager;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    private $orderSender;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    private $helper;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\EmailSender
     */
    private $emailSender;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    private $emulator;

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
     * @var \Magento\Framework\App\ProductMetadata
     */
    private $productMetadata;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\QuoteManager
     */
    private $quoteManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\CurrencyManager
     */
    private $currencyManager;

    /**
     * Subscription service context constructor.
     *
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\DataObject\Copy $objectCopyService
     * @param \ParadoxLabs\TokenBase\Api\CardRepositoryInterface $cardRepository
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepository
     * @param \Magento\Quote\Model\QuoteManagement $quoteManagement *Proxy
     * @param \Magento\Quote\Api\Data\CartInterfaceFactory $quoteFactory
     * @param \Magento\Quote\Api\Data\AddressInterfaceFactory $quoteAddressFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Api\AddressRepositoryInterface $customerAddressRepository
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender *Proxy
     * @param \Psr\Log\LoggerInterface $logger
     * @param \ParadoxLabs\Subscriptions\Helper\Data $helper
     * @param \ParadoxLabs\Subscriptions\Model\Service\EmailSender $emailSender *Proxy
     * @param \Magento\Store\Model\App\Emulation $emulator
     * @param \ParadoxLabs\Subscriptions\Helper\Vault $vaultHelper
     * @param \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor
     * @param \Magento\Framework\App\ProductMetadata $productMetadata
     * @param \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager
     * @param \ParadoxLabs\Subscriptions\Model\Service\CurrencyManager $currencyManager
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\DataObject\Copy $objectCopyService,
        \ParadoxLabs\TokenBase\Api\CardRepositoryInterface $cardRepository,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Quote\Api\Data\CartInterfaceFactory $quoteFactory,
        \Magento\Quote\Api\Data\AddressInterfaceFactory $quoteAddressFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AddressRepositoryInterface $customerAddressRepository,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,
        \Psr\Log\LoggerInterface $logger,
        \ParadoxLabs\Subscriptions\Helper\Data $helper,
        \ParadoxLabs\Subscriptions\Model\Service\EmailSender $emailSender,
        \Magento\Store\Model\App\Emulation $emulator,
        \ParadoxLabs\Subscriptions\Helper\Vault $vaultHelper,
        \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor,
        \Magento\Framework\App\ProductMetadata $productMetadata,
        \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager,
        \ParadoxLabs\Subscriptions\Model\Service\CurrencyManager $currencyManager
    ) {
        $this->registry = $registry;
        $this->objectCopyService = $objectCopyService;
        $this->cardRepository = $cardRepository;
        $this->cartRepository = $cartRepository;
        $this->quoteManagement = $quoteManagement;
        $this->quoteFactory = $quoteFactory;
        $this->quoteAddressFactory = $quoteAddressFactory;
        $this->customerRepository = $customerRepository;
        $this->customerAddressRepository = $customerAddressRepository;
        $this->eventManager = $eventManager;
        $this->orderSender = $orderSender;
        $this->logger = $logger;
        $this->helper = $helper;
        $this->emailSender = $emailSender;
        $this->emulator = $emulator;
        $this->vaultHelper = $vaultHelper;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->dateProcessor = $dateProcessor;
        $this->productMetadata = $productMetadata;
        $this->quoteManager = $quoteManager;
        $this->currencyManager = $currencyManager;
    }

    /**
     * Get registry
     *
     * @return \Magento\Framework\Registry
     */
    public function getRegistry()
    {
        return $this->registry;
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
     * Get cardRepository
     *
     * @return \ParadoxLabs\TokenBase\Api\CardRepositoryInterface
     */
    public function getCardRepository()
    {
        return $this->cardRepository;
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
     * Get quoteManagement
     *
     * @return \Magento\Quote\Model\QuoteManagement
     */
    public function getQuoteManagement()
    {
        return $this->quoteManagement;
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
     * Get customerAddressRepository
     *
     * @return \Magento\Customer\Api\AddressRepositoryInterface
     */
    public function getCustomerAddressRepository()
    {
        return $this->customerAddressRepository;
    }

    /**
     * Get eventManager
     *
     * @return \Magento\Framework\Event\ManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * Get orderSender
     *
     * @return \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    public function getOrderSender()
    {
        return $this->orderSender;
    }

    /**
     * Get logger
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
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
     * Get emailSender
     *
     * @return \ParadoxLabs\Subscriptions\Model\Service\EmailSender
     */
    public function getEmailSender()
    {
        return $this->emailSender;
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
     * Get currencyManager
     *
     * @return \ParadoxLabs\Subscriptions\Model\Service\CurrencyManager
     */
    public function getCurrencyManager()
    {
        return $this->currencyManager;
    }
}
