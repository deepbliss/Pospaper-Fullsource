<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 */

namespace ParadoxLabs\Subscriptions\Block\Customer\View;

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
     * @var \ParadoxLabs\Subscriptions\Model\Source\Period
     */
    private $periodModel;

    /**
     * @var \ParadoxLabs\TokenBase\Api\CardRepositoryInterface
     */
    private $cardRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Vault
     */
    private $vaultHelper;

    /**
     * @var \ParadoxLabs\TokenBase\Helper\Address
     */
    private $addressHelper;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    private $config;

    /**
     * Context constructor.
     *
     * @param \Magento\Framework\Registry $registry
     * @param \ParadoxLabs\Subscriptions\Model\Source\Period $periodModel
     * @param \ParadoxLabs\TokenBase\Api\CardRepositoryInterface $cardRepository
     * @param \ParadoxLabs\Subscriptions\Helper\Vault $vaultHelper
     * @param \ParadoxLabs\TokenBase\Helper\Address $addressHelper
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \ParadoxLabs\Subscriptions\Model\Source\Period $periodModel,
        \ParadoxLabs\TokenBase\Api\CardRepositoryInterface $cardRepository,
        \ParadoxLabs\Subscriptions\Helper\Vault $vaultHelper,
        \ParadoxLabs\TokenBase\Helper\Address $addressHelper,
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->registry = $registry;
        $this->periodModel = $periodModel;
        $this->cardRepository = $cardRepository;
        $this->vaultHelper = $vaultHelper;
        $this->addressHelper = $addressHelper;
        $this->config = $config;
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
     * Get periodModel
     *
     * @return \ParadoxLabs\Subscriptions\Model\Source\Period
     */
    public function getPeriodModel()
    {
        return $this->periodModel;
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
     * Get vaultHelper
     *
     * @return \ParadoxLabs\Subscriptions\Helper\Vault
     */
    public function getVaultHelper()
    {
        return $this->vaultHelper;
    }

    /**
     * Get addressHelper
     *
     * @return \ParadoxLabs\TokenBase\Helper\Address
     */
    public function getAddressHelper()
    {
        return $this->addressHelper;
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
}
