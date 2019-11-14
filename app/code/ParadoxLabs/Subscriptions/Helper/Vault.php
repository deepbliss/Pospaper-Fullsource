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

namespace ParadoxLabs\Subscriptions\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Vault\Api\PaymentTokenRepositoryInterface;
use ParadoxLabs\TokenBase\Api\CardRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Vault helper - operations to abstract Vault vs. TokenBase cards as much as practical.
 *
 * This should be in TokenBase, but doing so would break 2.0 compatibility of that module as well.
 */
class Vault extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \ParadoxLabs\TokenBase\Helper\Data
     */
    protected $tokenbaseHelper;

    /**
     * @var CardRepositoryInterface
     */
    protected $cardRepository;

    /**
     * @var PaymentTokenRepositoryInterface
     */
    protected $tokenRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\Payment
     */
    protected $paymentService;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\OfflinePayment\CardFactory
     */
    protected $offlineCardFactory;

    /**
     * Vault constructor
     *
     * @param Context $context
     * @param \ParadoxLabs\TokenBase\Helper\Data $tokenbaseHelper
     * @param CardRepositoryInterface $cardRepository
     * @param PaymentTokenRepositoryInterface $tokenRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \ParadoxLabs\Subscriptions\Model\Service\Payment $paymentService
     * @param \ParadoxLabs\Subscriptions\Model\OfflinePayment\CardFactory $offlineCardFactory
     */
    public function __construct(
        Context $context,
        \ParadoxLabs\TokenBase\Helper\Data $tokenbaseHelper,
        \ParadoxLabs\TokenBase\Api\CardRepositoryInterface $cardRepository,
        \Magento\Vault\Api\PaymentTokenRepositoryInterface $tokenRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \ParadoxLabs\Subscriptions\Model\Service\Payment $paymentService,
        \ParadoxLabs\Subscriptions\Model\OfflinePayment\CardFactory $offlineCardFactory
    ) {
        parent::__construct($context);

        $this->tokenbaseHelper = $tokenbaseHelper;
        $this->cardRepository = $cardRepository;
        $this->tokenRepository = $tokenRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->paymentService = $paymentService;
        $this->offlineCardFactory = $offlineCardFactory;
    }

    /**
     * Get text label for the given card.
     *
     * @param \Magento\Vault\Api\Data\PaymentTokenInterface $card
     * @return string
     */
    public function getCardLabel(\Magento\Vault\Api\Data\PaymentTokenInterface $card)
    {
        // Our methods handle this implicitly.
        if ($card instanceof \ParadoxLabs\TokenBase\Api\Data\CardInterface) {
            return $card->getLabel();
        }

        // Vault cards do not.
        return __(
            '%1 XXXX-%2',
            $this->getCardType($card),
            $this->getCardLast4($card)
        );
    }

    /**
     * Get CC type for the given card.
     *
     * @param \Magento\Vault\Api\Data\PaymentTokenInterface $card
     * @return string
     */
    public function getCardType(\Magento\Vault\Api\Data\PaymentTokenInterface $card)
    {
        if ($card instanceof \ParadoxLabs\TokenBase\Api\Data\CardInterface) {
            return $card->getAdditional('cc_type');
        }

        // For Vault cards, grab the CC details. We can only assume they'll follow conventions.
        $details = $card->getTokenDetails();

        if (is_string($details)) {
            $details = json_decode($details, 1);
        }

        $type = $card->getType();
        if (isset($details['cc_type'])) {
            $type = $details['cc_type'];
        } elseif (isset($details['type'])) {
            $type = $details['type'];
        }

        return $type;
    }

    /**
     * Get CC last-4 digits for the given card.
     *
     * @param \Magento\Vault\Api\Data\PaymentTokenInterface $card
     * @return string
     */
    public function getCardLast4(\Magento\Vault\Api\Data\PaymentTokenInterface $card)
    {
        if ($card instanceof \ParadoxLabs\TokenBase\Api\Data\CardInterface) {
            return $card->getAdditional('cc_last4');
        }

        // For Vault cards, grab the CC details. We can only assume they'll follow conventions.
        $details = $card->getTokenDetails();

        if (is_string($details)) {
            $details = json_decode($details, 1);
        }

        $ccLast4 = '';
        if (isset($details['cc_last_4'])) {
            $ccLast4 = $details['cc_last_4'];
        } elseif (isset($details['maskedCC'])) {
            $ccLast4 = $details['maskedCC'];
        }

        return $ccLast4;
    }

    /**
     * Get expires date for the given card.
     *
     * @param \Magento\Vault\Api\Data\PaymentTokenInterface $card
     * @return string
     */
    public function getCardExpires(\Magento\Vault\Api\Data\PaymentTokenInterface $card)
    {
        // Our methods handle this implicitly.
        if ($card instanceof \ParadoxLabs\TokenBase\Api\Data\CardInterface) {
            return $card->getExpires();
        }

        // Vault stores expires date as first of the next month. Roll that back for the customer.
        $expires = strtotime($card->getExpiresAt()) - 1;

        return date('c', $expires);
    }

    /**
     * Get active customer cards.
     *
     * @param int $customerId
     * @return \Magento\Vault\Api\Data\PaymentTokenInterface[]
     */
    public function getActiveCustomerCards($customerId = null)
    {
        $cards = [];

        /** @var \ParadoxLabs\TokenBase\Model\Card $card */
        foreach ($this->tokenbaseHelper->getActiveCustomerCardsByMethod() as $card) {
            $cards[] = $card->getTypeInstance();
        }

        /**
         * Add any Vault cards
         */
        if ($customerId === null) {
            $customerId = $this->tokenbaseHelper->getCurrentCustomer()->getId();
        }

        if ($customerId > 0) {
            $tokenCriteria = $this->searchCriteriaBuilder->addFilter('customer_id', $customerId)
                                                         ->addFilter('is_active', 1)
                                                         ->addFilter('is_visible', 1)
                                                         ->create();

            $tokens = $this->tokenRepository->getList($tokenCriteria)->getItems();

            if (!empty($tokens)) {
                /** @var \Magento\Vault\Api\Data\PaymentTokenInterface $token */
                foreach ($tokens as $token) {
                    $cards[] = $token;
                }
            }
        }

        /**
         * Add any offline methods
         */
        $offlineMethods = $this->paymentService->getOfflineMethods();
        foreach ($offlineMethods as $method) {
            $cards[] = $this->offlineCardFactory->createMethodCard($method);
        }

        return $cards;
    }

    /**
     * Get the card for the given quote (TokenBase or Vault).
     *
     * Note: This assumes the quote has an already-stored card associated.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return \Magento\Vault\Api\Data\PaymentTokenInterface
     * @throws NoSuchEntityException
     */
    public function getQuoteCard(\Magento\Quote\Api\Data\CartInterface $quote)
    {
        /** @var \Magento\Quote\Model\Quote $quote */

        $card = null;

        if ($this->paymentService->isTokenBaseMethod($quote->getPayment()->getMethod())) {
            /** @var \ParadoxLabs\TokenBase\Model\Card $card */
            $card = $this->cardRepository->getById($quote->getPayment()->getData('tokenbase_id'));
            $card = $card->getTypeInstance();
        } elseif ($this->paymentService->isOfflineMethod($quote->getPayment()->getMethod())) {
            $method = $this->tokenbaseHelper->getMethodInstance($quote->getPayment()->getMethod());
            $card = $this->offlineCardFactory->createMethodCard($method);
        } else {
            // token_metadata was used in 2.1.0-2.1.2. In 2.1.3 the values were moved to the top level.
            $tokenMeta = $quote->getPayment()->getAdditionalInformation('token_metadata')
                ?: $quote->getPayment()->getAdditionalInformation();

            if (is_array($tokenMeta) && isset($tokenMeta['public_hash'])) {
                $tokenCriteria = $this->searchCriteriaBuilder->addFilter('public_hash', $tokenMeta['public_hash'])
                                                             ->setPageSize(1)
                                                             ->create();

                $tokens = $this->tokenRepository->getList($tokenCriteria)->getItems();

                if (!empty($tokens)) {
                    $card = array_shift($tokens);
                }
            }
        }

        if ($card instanceof \ParadoxLabs\TokenBase\Api\Data\CardInterface) {
            $card->setInfoInstance($quote->getPayment());
        }

        /**
         * Verify we got something, and return.
         */
        if (!($card instanceof \Magento\Vault\Api\Data\PaymentTokenInterface)) {
            throw new NoSuchEntityException(__('Could not load card for quote with id "%1".', $quote->getId()));
        }

        return $card;
    }

    /**
     * Determine whether the given quote is Vault or not.
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return bool
     */
    public function isQuoteVaultPayment(\Magento\Quote\Api\Data\CartInterface $quote)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        if ($this->paymentService->isVaultMethod($quote->getPayment()->getMethod())) {
            return true;
        }

        return false;
    }

    /**
     * Load an arbitrary card by hash.
     *
     * @param string $publicHash
     * @return \Magento\Vault\Api\Data\PaymentTokenInterface
     * @throws NoSuchEntityException
     */
    public function getCardByHash($publicHash)
    {
        $card = null;

        /**
         * Try to load TokenBase first (if it fits). Our hashes will always be 40 chars. Vault is typically 64.
         */
        if (strlen($publicHash) === 40) {
            try {
                /** @var \ParadoxLabs\TokenBase\Model\Card $temp */
                $temp = $this->cardRepository->getById($publicHash);
                $card = $temp->getTypeInstance();
            } catch (NoSuchEntityException $e) {
                // Ignore card-not-found exception.
            }
        }

        /**
         * If we don't have a card yet, is it an offline method?
         */
        if (!($card instanceof \Magento\Vault\Api\Data\PaymentTokenInterface)
            && $this->paymentService->isOfflineMethod($publicHash)) {
            $method = $this->tokenbaseHelper->getMethodInstance($publicHash);
            $card = $this->offlineCardFactory->createMethodCard($method);
        }

        /**
         * If we don't have a card yet, try the Vault.
         */
        if (!($card instanceof \Magento\Vault\Api\Data\PaymentTokenInterface)) {
            $criteria = $this->searchCriteriaBuilder->addFilter('public_hash', $publicHash)
                                                    ->setPageSize(1)
                                                    ->create();

            $tokens = $this->tokenRepository->getList($criteria)->getItems();

            if (!empty($tokens)) {
                $card = array_shift($tokens);
            }
        }

        /**
         * Verify we got something, and return.
         */
        if (!($card instanceof \Magento\Vault\Api\Data\PaymentTokenInterface)) {
            throw new NoSuchEntityException(__('Could not load card with hash "%1".', $publicHash));
        }

        return $card;
    }

    /**
     * Fix card customer ID: If it's null, save the proper ID.
     *
     * @param \Magento\Vault\Api\Data\PaymentTokenInterface $card
     * @param int $customerId
     * @return \Magento\Vault\Api\Data\PaymentTokenInterface
     */
    public function fixCardCustomerId(\Magento\Vault\Api\Data\PaymentTokenInterface $card, $customerId)
    {
        if (($card instanceof \ParadoxLabs\TokenBase\Api\Data\CardInterface) === false
            && $card->getCustomerId() === null
            && $customerId !== null) {
            $card->setCustomerId($customerId);
            $this->tokenRepository->save($card);
        }

        return $card;
    }
}
