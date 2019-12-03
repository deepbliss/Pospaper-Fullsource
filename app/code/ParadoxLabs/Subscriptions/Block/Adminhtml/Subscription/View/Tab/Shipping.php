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

namespace ParadoxLabs\Subscriptions\Block\Adminhtml\Subscription\View\Tab;

use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Shipping tab
 *
 * TODO: We're using a template because address handling is haaard. Ultimately, this should be recreated as proper /
 * TODO: form fields as in \Magento\Sales\Block\Adminhtml\Order\Create\Shipping\Address
 */
class Shipping extends \Magento\Customer\Block\Address\Edit implements TabInterface
{
    /**
     * @var \Magento\Customer\Api\Data\AddressInterface
     */
    protected $shippingAddress;

    /**
     * @var \ParadoxLabs\TokenBase\Helper\Data
     */
    protected $tokenbaseHelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \ParadoxLabs\TokenBase\Helper\Address
     */
    protected $addressHelper;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\CurrencyManager
     */
    protected $currencyManager;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    protected $emulator;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Directory\Helper\Data $directoryHelper
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\App\Cache\Type\Config $configCacheType
     * @param \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession *Proxy
     * @param \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
     * @param \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer *Proxy
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magento\Framework\Registry $registry
     * @param \ParadoxLabs\TokenBase\Helper\Data $tokenbaseHelper
     * @param \ParadoxLabs\TokenBase\Helper\Address $addressHelper
     * @param \ParadoxLabs\Subscriptions\Model\Service\CurrencyManager $currencyManager
     * @param \Magento\Store\Model\App\Emulation $emulator
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Directory\Helper\Data $directoryHelper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\App\Cache\Type\Config $configCacheType,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\Registry $registry,
        \ParadoxLabs\TokenBase\Helper\Data $tokenbaseHelper,
        \ParadoxLabs\TokenBase\Helper\Address $addressHelper,
        \ParadoxLabs\Subscriptions\Model\Service\CurrencyManager $currencyManager,
        \Magento\Store\Model\App\Emulation $emulator,
        array $data = []
    ) {
        $this->tokenbaseHelper = $tokenbaseHelper;
        $this->registry = $registry;
        $this->addressHelper = $addressHelper;
        $this->currencyManager = $currencyManager;
        $this->emulator = $emulator;

        parent::__construct(
            $context,
            $directoryHelper,
            $jsonEncoder,
            $configCacheType,
            $regionCollectionFactory,
            $countryCollectionFactory,
            $customerSession,
            $addressRepository,
            $addressDataFactory,
            $currentCustomer,
            $dataObjectHelper,
            $data
        );
    }

    /**
     * Get current subscription model
     *
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface
     */
    public function getSubscription()
    {
        $subscription = $this->registry->registry('current_subscription');

        return $subscription;
    }

    /**
     * Return the associated address.
     *
     * @return \Magento\Customer\Api\Data\AddressInterface
     */
    public function getAddress()
    {
        if ($this->shippingAddress === null) {
            /** @var \Magento\Quote\Model\Quote $quote */
            $quote = $this->getSubscription()->getQuote();

            $this->shippingAddress = $quote->getShippingAddress()->exportCustomerAddress();
        }

        return $this->shippingAddress;
    }

    /**
     * Return the specified numbered street line.
     *
     * @param int $lineNumber
     * @return string
     */
    public function getStreetLine($lineNumber)
    {
        $street = $this->getAddress()->getStreet();

        return isset($street[$lineNumber - 1]) ? $street[$lineNumber - 1] : '';
    }

    /**
     * Retrieve the Customer Data using the customer Id from the customer session.
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    public function getCustomer()
    {
        return $this->tokenbaseHelper->getCurrentCustomer();
    }

    /**
     * Get HTML-formatted card address. This is silly, but it's how the core says to do it.
     *
     * @param \Magento\Customer\Api\Data\AddressInterface $address
     * @param string $format
     * @return string
     * @see \Magento\Customer\Model\Address\AbstractAddress::format()
     */
    public function getFormattedAddress(\Magento\Customer\Api\Data\AddressInterface $address, $format = 'html')
    {
        return $this->addressHelper->getFormattedAddress($address, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Shipping');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Shipping');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Get quote estimated shipping rates.
     *
     * @return array
     */
    public function getShippingMethods()
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->getSubscription()->getQuote();

        $this->emulator->startEnvironmentEmulation(
            $this->getSubscription()->getStoreId(),
            \Magento\Framework\App\Area::AREA_FRONTEND,
            true
        );

        $quote->collectTotals();
        $quote->getShippingAddress()->setCollectShippingRates(true)
                                    ->collectShippingRates();

        $rates = $quote->getShippingAddress()->getGroupedAllShippingRates();

        $this->emulator->stopEnvironmentEmulation();

        return $rates;
    }

    /**
     * Check whether the subscription's assigned shipping method is actually available.
     *
     * @return bool
     */
    public function isValidShippingMethod()
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->getSubscription()->getQuote();

        $method = $quote->getShippingAddress()->getShippingRateByCode(
            $quote->getShippingAddress()->getShippingMethod()
        );

        return $method !== false;
    }

    /**
     * Convert and format shipping rate price.
     *
     * @param float $cost
     * @return string
     */
    public function getShippingMethodPrice($cost)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->getSubscription()->getQuote();

        $price = $this->currencyManager->convertPriceCurrency(
            $cost,
            $quote->getBaseCurrencyCode(),
            $quote->getQuoteCurrencyCode()
        );

        $currency = $this->currencyManager->getCurrencyByCode(
            $quote->getQuoteCurrencyCode()
        );

        return $currency->formatTxt($price);
    }
}
