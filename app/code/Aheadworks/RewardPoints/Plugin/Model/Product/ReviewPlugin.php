<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Model\Product;

use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Review\Model\Review;
use Aheadworks\RewardPoints\Model\ResourceModel\Order as OrderResource;

/**
 * Class Aheadworks\RewardPoints\Plugin\Model\Product\ReviewPlugin
 */
class ReviewPlugin
{
    /**
     * @var CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsService;

    /**
     * @var boolean
     */
    private $isApprovedReview = false;

    /**
     * @var boolean
     */
    private $isDisapprovedReview = false;

    /**
     * @var OrderResource
     */
    private $orderResource;

    /**
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     * @param OrderResource $orderResource
     */
    public function __construct(
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        OrderResource $orderResource
    ) {
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->orderResource = $orderResource;
    }

    /**
     * Set $this->isApprovedReview or $this->isDisapprovedReview flags for afterSave method
     *
     * @param Review $reviewObjectNew
     * @return array
     */
    public function beforeSave(Review $reviewObjectNew)
    {
        if ($reviewObjectNew->getCustomerId()) {
            if ($reviewObjectNew->dataHasChangedFor('status_id')) {
                if ($reviewObjectNew->isApproved()) {
                    $this->isApprovedReview = true;
                } else {
                    $this->isDisapprovedReview = true;
                }
            }
        }

        return [];
    }

    /**
     * If review was approved add Awards Points for Review
     *
     * @param Review $review
     * @return Review
     */
    public function afterSave(Review $review)
    {
        if ($this->isApprovedReview) {
            $customerId = $review->getCustomerId();
            if ($customerId) {
                $this->customerRewardPointsService->addPointsForReviews(
                    $customerId,
                    $this->orderResource->isCustomersOwnerOfProductId(
                        $customerId,
                        $review->getEntityPkValue()
                    )
                );
            }
        }

        return $review;
    }
}
