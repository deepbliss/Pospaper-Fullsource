<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Information\Messages;

/**
 * Class Register
 *
 * @package Aheadworks\RewardPoints\Block\Information\Messages
 */
class Register extends AbstractMessages
{
    /**
     * {@inheritdoc}
     */
    public function canShow()
    {
        return $this->config->getFrontendIsDisplayInvitationToRegister() && $this->getEarnPoints()
            && !$this->isCustomerLoggedIn();
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        $earnPoints = $this->getEarnPoints();
        return __(
            'Register now to earn <strong>%1 points%2</strong>. <a href="%3">Learn more</a>.',
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
        return $this->config->getAwardedPointsForRegistration();
    }
}
