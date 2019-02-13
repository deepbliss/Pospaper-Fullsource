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

namespace ParadoxLabs\Subscriptions\Plugin\Quote\Model\QuoteRepository\Plugin\AccessChangeQuoteControl;

use Magento\Authorization\Model\UserContextInterface;

/**
 * AccessChangeQuoteControl Class
 */
class Plugin
{
    /**
     * @var UserContextInterface
     */
    private $userContext;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\QuoteManager
     */
    private $quoteManager;

    /**
     * @param UserContextInterface $userContext
     * @param \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager
     */
    public function __construct(
        UserContextInterface $userContext,
        \ParadoxLabs\Subscriptions\Model\Service\QuoteManager $quoteManager
    ) {
        $this->userContext = $userContext;
        $this->quoteManager = $quoteManager;
    }

    /**
     * @param \Magento\Quote\Model\QuoteRepository\Plugin\AccessChangeQuoteControl $subject
     * @param \Closure $proceed
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepository
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return void
     * @throws \Magento\Framework\Exception\StateException
     */
    public function aroundBeforeSave(
        \Magento\Quote\Model\QuoteRepository\Plugin\AccessChangeQuoteControl $subject,
        \Closure $proceed,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        \Magento\Quote\Api\Data\CartInterface $quote
    ) {
        try {
            $proceed($cartRepository, $quote);
        } catch (\Magento\Framework\Exception\StateException $e) {
            /**
             * If AccessChangeQuoteControl fails (meaning the quote being saved is for a different user than the one
             * taking the action), bypass it if this is a guest ordering a subscription. Because that's totally okay.
             */

            // Are we a guest?
            // Is this quote a subscription?
            if ($this->userContext->getUserType() !== UserContextInterface::USER_TYPE_GUEST
                || $this->quoteManager->quoteContainsSubscription($quote) === false) {
                // No? Rethrow the exception.
                throw $e;
            }
        }
    }
}
