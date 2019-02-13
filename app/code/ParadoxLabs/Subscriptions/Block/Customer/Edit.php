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
 * Edit Class
 */
class Edit extends View
{
    /**
     * @var \ParadoxLabs\TokenBase\Helper\Data
     */
    protected $tokenbaseHelper;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\Payment
     */
    protected $paymentService;

    /**
     * Edit constructor.
     *
     * @param Template\Context $context
     * @param View\Context $viewContext
     * @param \ParadoxLabs\TokenBase\Helper\Data $tokenbaseHelper
     * @param \ParadoxLabs\Subscriptions\Model\Service\Payment $paymentService
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \ParadoxLabs\Subscriptions\Block\Customer\View\Context $viewContext,
        \ParadoxLabs\TokenBase\Helper\Data $tokenbaseHelper,
        \ParadoxLabs\Subscriptions\Model\Service\Payment $paymentService,
        array $data
    ) {
        parent::__construct(
            $context,
            $viewContext,
            $data
        );

        $this->tokenbaseHelper = $tokenbaseHelper;
        $this->paymentService = $paymentService;
    }

    /**
     * Get subscription save URL.
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->_urlBuilder->getUrl('*/*/editPost', ['id' => $this->getSubscription()->getId()]);
    }

    /**
     * Get subscription view URL.
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->_urlBuilder->getUrl('*/*/view', ['id' => $this->getSubscription()->getId()]);
    }

    /**
     * Get active customer cards.
     *
     * @return \Magento\Vault\Api\Data\PaymentTokenInterface[]
     */
    public function getCustomerCards()
    {
        $cards = $this->vaultHelper->getActiveCustomerCards();

        // Remove any unavailable methods.
        foreach ($cards as $k => $card) {
            $methodIsAvailable = $this->paymentService->isMethodAvailable(
                $card->getPaymentMethodCode(),
                $this->getSubscription()->getQuote()
            );

            if ($methodIsAvailable === false) {
                unset($cards[ $k ]);
            }
        }

        // Make sure quote card is included, even if inactive.
        $activeCard = $this->vaultHelper->getQuoteCard(
            $this->getSubscription()->getQuote()
        );

        if ($activeCard instanceof \Magento\Vault\Api\Data\PaymentTokenInterface) {
            $found = false;
            foreach ($cards as $card) {
                if ($card->getPublicHash() === $activeCard->getPublicHash()) {
                    $found = true;
                    break;
                }
            }

            if ($found !== true) {
                array_unshift($cards, $activeCard);
            }
        }

        return $cards;
    }

    /**
     * Get TokenBase helper class
     *
     * @return \ParadoxLabs\TokenBase\Helper\Data
     */
    public function getTokenbaseHelper()
    {
        return $this->tokenbaseHelper;
    }
}
