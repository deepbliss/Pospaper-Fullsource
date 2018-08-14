<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Information\Messages;

/**
 * Class Newsletter
 *
 * @package Aheadworks\RewardPoints\Block\Information\Messages
 */
class Newsletter extends AbstractMessages
{
    /**
     * {@inheritdoc}
     */
    public function canShow()
    {
        $isAwardedForNewsletter = $this->pointsSummaryService->isAwardedForNewsletterSignup($this->getCustomerId());

        return $this->config->getFrontendIsDisplayInvitationToNewsletter() && $this->getEarnPoints()
            && (($this->isCustomerLoggedIn() && !$isAwardedForNewsletter) || !$this->isCustomerLoggedIn());
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        $earnPoints = $this->getEarnPoints();
        return __(
            'Subscribe to Newsletter to earn <strong>%1 points%2</strong>. <a href="%3">Learn more</a>.',
            $earnPoints,
            $this->getEarnMoneyByPoints($earnPoints),
            $this->getFrontendExplainerPageLink()
        );
    }

    /**
     * Retrieve how much points will be earned
     *
     * @return int
     */
    public function getEarnPoints()
    {
        return $this->config->getAwardedPointsForNewsletterSignup();
    }
}
