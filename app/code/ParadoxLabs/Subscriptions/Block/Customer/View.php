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

namespace ParadoxLabs\Subscriptions\Block\Customer;

use Magento\Framework\View\Element\Template;

/**
 * View Class
 */
class View extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Period
     */
    protected $periodModel;

    /**
     * @var \ParadoxLabs\TokenBase\Api\CardRepositoryInterface
     */
    protected $cardRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Vault
     */
    protected $vaultHelper;

    /**
     * @var \ParadoxLabs\TokenBase\Helper\Address
     */
    protected $addressHelper;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * View constructor.
     *
     * @param Template\Context $context
     * @param View\Context $viewContext
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \ParadoxLabs\Subscriptions\Block\Customer\View\Context $viewContext,
        array $data
    ) {
        parent::__construct($context, $data);

        $this->registry = $viewContext->getRegistry();
        $this->periodModel = $viewContext->getPeriodModel();
        $this->cardRepository = $viewContext->getCardRepository();
        $this->vaultHelper = $viewContext->getVaultHelper();
        $this->addressHelper = $viewContext->getAddressHelper();
        $this->config = $viewContext->getConfig();
    }

    /**
     * Get current subscription model.
     *
     * @return \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface
     */
    public function getSubscription()
    {
        /** @var \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription */
        $subscription = $this->registry->registry('current_subscription');

        return $subscription;
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
     * Get frequency label (Runs every ___) for the current subscription.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getSubscriptionFrequencyLabel()
    {
        $count = $this->getSubscription()->getFrequencyCount();
        $unit  = $this->getSubscription()->getFrequencyUnit();

        if ($count > 1) {
            $unitLabel = $this->periodModel->getOptionTextPlural($unit);
        } else {
            $unitLabel = $this->periodModel->getOptionText($unit);
        }

        return __('%1 %2', $count, $unitLabel);
    }

    /**
     * Get the active card for the current subscription.
     *
     * @return \Magento\Vault\Api\Data\PaymentTokenInterface|null
     */
    public function getCard()
    {
        try {
            return $this->vaultHelper->getQuoteCard($this->getSubscription()->getQuote());
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Get subscription edit URL.
     *
     * @return string
     */
    public function getEditUrl()
    {
        return $this->_urlBuilder->getUrl('*/*/edit', ['id' => $this->getSubscription()->getId()]);
    }

    /**
     * Get text label for the given card.
     *
     * @param \Magento\Vault\Api\Data\PaymentTokenInterface $card
     * @return string
     */
    public function getCardLabel(\Magento\Vault\Api\Data\PaymentTokenInterface $card)
    {
        return $this->vaultHelper->getCardLabel($card);
    }

    /**
     * Get text label for the given card.
     *
     * @param \Magento\Vault\Api\Data\PaymentTokenInterface $card
     * @return string
     */
    public function getCardExpires(\Magento\Vault\Api\Data\PaymentTokenInterface $card)
    {
        return $this->vaultHelper->getCardExpires($card);
    }

    /**
     * Get 'installments' label from configuration.
     *
     * @return string
     */
    public function getInstallmentLabel()
    {
        return $this->config->getInstallmentLabel();
    }
}
