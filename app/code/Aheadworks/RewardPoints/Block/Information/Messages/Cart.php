<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Information\Messages;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Cart
 *
 * @package Aheadworks\RewardPoints\Block\Information\Messages
 */
class Cart extends AbstractMessages
{
    /**
     * {@inheritdoc}
     */
    public function canShow()
    {
        $customerRewardPointsEarnRate = $this->customerRewardPointsService
            ->isCustomerRewardPointsEarnRate($this->getCustomerId());
        $customerRewardPointsEarnRateByGroup = $this->customerRewardPointsService
            ->isCustomerRewardPointsEarnRateByGroup($this->getCustomerId());

        return $customerRewardPointsEarnRateByGroup && $customerRewardPointsEarnRate && $this->getEarnPoints() > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        $earnPoints = $this->getEarnPoints();
        $message = __(
            'Checkout now to earn <strong>%1 points%2</strong> for your order.',
            $earnPoints,
            $this->getEarnMoneyByPoints($earnPoints),
            $this->getFrontendExplainerPageLink()
        );

        if (!$this->isCustomerLoggedIn()) {
            $message .= ' ' . __(
                'This amount can vary after logging in. <a href="%1">Learn more</a>.',
                $this->getFrontendExplainerPageLink()
            );
        }

        return $message;
    }

    /**
     * Retrieve how much points will be earned
     *
     * @return int
     */
    public function getEarnPoints()
    {
        try {
            $cartTotal = $this->cartTotalRepository->get($this->checkoutSession->getQuote()->getId());
        } catch (NoSuchEntityException $e) {
            return 0;
        }
        return $this->earningCalculator->calculation($cartTotal, $this->getCustomerId());
    }
}
