<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Model\Newsletter;

use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Newsletter\Model\Subscriber;

/**
 * Class Aheadworks\RewardPoints\Plugin\Model\Newsletter\SubscriberPlugin
 */
class SubscriberPlugin
{
    /**
     * @var CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsService;

    /**
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     */
    public function __construct(
        CustomerRewardPointsManagementInterface $customerRewardPointsService
    ) {
        $this->customerRewardPointsService = $customerRewardPointsService;
    }

    /**
     * Add Reward Points for the first Newsletter signup
     *
     * @param Subscriber $subscriber
     * @return void
     */
    public function afterSave(Subscriber $subscriber)
    {
        $customerId = $subscriber->getCustomerId();
        if ($customerId && $subscriber->isSubscribed()) {
            $this->customerRewardPointsService->addPointsForNewsletterSignup($customerId);
        }
    }
}
