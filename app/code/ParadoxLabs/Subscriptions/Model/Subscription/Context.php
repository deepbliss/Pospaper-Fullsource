<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 */

namespace ParadoxLabs\Subscriptions\Model\Subscription;

/**
 * Subscription Context Class
 */
class Context
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\LogFactory
     */
    private $logFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Status
     */
    private $statusSource;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Period
     */
    private $periodSource;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    private $emulator;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $dateProcessor;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\RelatedObjectManager
     */
    private $relatedObjectManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\DateCalculator
     */
    private $dateCalculator;

    /**
     * Context constructor.
     * @param \ParadoxLabs\Subscriptions\Model\LogFactory $logFactory
     * @param \ParadoxLabs\Subscriptions\Model\Source\Status $statusSource
     * @param \ParadoxLabs\Subscriptions\Model\Source\Period $periodSource
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepository *Proxy
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Store\Model\App\Emulation $emulator
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor
     * @param \ParadoxLabs\Subscriptions\Model\Service\RelatedObjectManager $relatedObjectManager
     * @param \ParadoxLabs\Subscriptions\Model\Service\DateCalculator $dateCalculator
     */
    public function __construct(
        \Magento\Store\Model\App\Emulation $emulator,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        \ParadoxLabs\Subscriptions\Model\LogFactory $logFactory,
        \ParadoxLabs\Subscriptions\Model\Source\Status $statusSource,
        \ParadoxLabs\Subscriptions\Model\Source\Period $periodSource,
        \ParadoxLabs\Subscriptions\Model\Service\RelatedObjectManager $relatedObjectManager,
        \ParadoxLabs\Subscriptions\Model\Service\DateCalculator $dateCalculator
    ) {
        $this->emulator = $emulator;
        $this->storeManager = $storeManager;
        $this->dateProcessor = $dateProcessor;
        $this->cartRepository = $cartRepository;
        $this->logFactory = $logFactory;
        $this->statusSource = $statusSource;
        $this->periodSource = $periodSource;
        $this->relatedObjectManager = $relatedObjectManager;
        $this->dateCalculator = $dateCalculator;
    }

    /**
     * Get logFactory
     *
     * @return \ParadoxLabs\Subscriptions\Model\LogFactory
     */
    public function getLogFactory()
    {
        return $this->logFactory;
    }

    /**
     * Get statusSource
     *
     * @return \ParadoxLabs\Subscriptions\Model\Source\Status
     */
    public function getStatusSource()
    {
        return $this->statusSource;
    }

    /**
     * Get periodSource
     *
     * @return \ParadoxLabs\Subscriptions\Model\Source\Period
     */
    public function getPeriodSource()
    {
        return $this->periodSource;
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
     * Get dateProcessor
     *
     * @return \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    public function getDateProcessor()
    {
        return $this->dateProcessor;
    }

    /**
     * Get relatedObjectManager
     *
     * @return \ParadoxLabs\Subscriptions\Model\Service\RelatedObjectManager
     */
    public function getRelatedObjectManager()
    {
        return $this->relatedObjectManager;
    }

    /**
     * Get dateCalculator
     *
     * @return \ParadoxLabs\Subscriptions\Model\Service\DateCalculator
     */
    public function getDateCalculator()
    {
        return $this->dateCalculator;
    }
}
