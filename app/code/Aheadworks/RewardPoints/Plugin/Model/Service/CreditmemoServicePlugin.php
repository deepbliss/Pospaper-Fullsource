<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Model\Service;

use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Sales\Model\Service\CreditmemoService;
use Magento\Sales\Api\Data\CreditmemoInterface;

/**
 * Class CreditmemoServicePlugin
 *
 * @package Aheadworks\RewardPoints\Plugin\Model\Service
 */
class CreditmemoServicePlugin
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
     * Refund Reward Points to customer on credit memo
     *
     * @param  CreditmemoService $subject
     * @param  CreditmemoInterface $result
     * @return CreditmemoInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterRefund(CreditmemoService $subject, CreditmemoInterface $result)
    {
        $this->customerRewardPointsService->refundToRewardPoints($result->getEntityId());
        $this->customerRewardPointsService->reimbursedSpentRewardPoints($result->getEntityId());
        $this->customerRewardPointsService->cancelEarnedPointsRefundOrder($result->getEntityId());

        return $result;
    }
}
