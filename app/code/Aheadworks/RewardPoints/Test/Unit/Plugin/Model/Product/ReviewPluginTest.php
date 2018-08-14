<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Plugin\Model\Product;

use Aheadworks\RewardPoints\Plugin\Model\Product\ReviewPlugin;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Review\Model\Review;
use Aheadworks\RewardPoints\Model\ResourceModel\Order as OrderResource;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Plugin\Model\Service\OrderServicePluginTest
 */
class ReviewPluginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ReviewPlugin
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsManagementMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Review
     */
    private $reviewMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Order
     */
    private $orderResourceMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->orderResourceMock = $this->getMockBuilder(OrderResource::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->reviewMock = $this->getMockBuilder(Review::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getCustomerId',
                    'isApproved',
                    'getOrigData',
                    'getStatusId',
                    'getEntityPkValue',
                    'dataHasChangedFor'
                ]
            )
            ->getMockForAbstractClass();

        $this->customerRewardPointsManagementMock = $this->getMockBuilder(
            CustomerRewardPointsManagementInterface::class
        )
            ->disableOriginalConstructor()
            ->setMethods(['addPointsForReviews'])
            ->getMockForAbstractClass();

        $data = [
            'customerRewardPointsService' => $this->customerRewardPointsManagementMock,
            'orderResource' => $this->orderResourceMock
        ];

        $this->object = $objectManager->getObject(ReviewPlugin::class, $data);
    }

    /**
     * Test beforeSave method for customer review after approving
     */
    public function testBeforeSaveAfterApproveMethod()
    {
        $customerId = 2;
        $isApproved = 1;

        $this->reviewMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->reviewMock->expects($this->once())
            ->method('dataHasChangedFor')
            ->with('status_id')
            ->willReturn(true);

        $this->reviewMock->expects($this->once())
            ->method('isApproved')
            ->willReturn($isApproved);

        $this->object->beforeSave($this->reviewMock);
    }

    /**
     * Test beforeSave method for guest review after approving
     */
    public function testBeforeSaveGuestReviewMethod()
    {
        $customerId = 0;
        $isApproved = 1;

        $this->reviewMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->reviewMock->expects($this->never())
            ->method('dataHasChangedFor')
            ->with('status_id')
            ->willReturn(true);

        $this->reviewMock->expects($this->never())
            ->method('isApproved')
            ->willReturn($isApproved);

        $this->object->beforeSave($this->reviewMock);
    }

    /**
     * Test beforeSave method for customer review after editing
     */
    public function testBeforeSaveEditReviewMethod()
    {
        $customerId = 2;
        $isApproved = 1;

        $this->reviewMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->reviewMock->expects($this->once())
            ->method('dataHasChangedFor')
            ->with('status_id')
            ->willReturn(false);

        $this->reviewMock->expects($this->never())
            ->method('isApproved')
            ->willReturn($isApproved);

        $this->object->beforeSave($this->reviewMock);
    }

    /**
     * Test afterSave method for customer review after editing
     */
    public function testAfterSaveNotApproveReviewMethod()
    {
        $customerId = 2;

        $this->reviewMock->expects($this->never())
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->customerRewardPointsManagementMock->expects($this->never())
            ->method('addPointsForReviews')
            ->with($customerId)
            ->willReturnSelf();

        $this->object->afterSave($this->reviewMock);
    }

    /**
     * Test afterSave method for customer review after approving
     */
    public function testAfterSaveApproveReviewMethod()
    {
        $customerId = 2;
        $productId = 1049;

        $property = new \ReflectionProperty(ReviewPlugin::class, "isApprovedReview");
        $property->setAccessible(true);
        $property->setValue($this->object, true);

        $this->reviewMock->expects($this->exactly(1))
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->reviewMock->expects($this->exactly(1))
            ->method('getEntityPkValue')
            ->willReturn($productId);

        $this->orderResourceMock->expects($this->once())
            ->method('isCustomersOwnerOfProductId')
            ->with($customerId, $productId)
            ->willReturnSelf();

        $this->customerRewardPointsManagementMock->expects($this->once())
            ->method('addPointsForReviews')
            ->with($customerId)
            ->willReturnSelf();

        $this->object->afterSave($this->reviewMock);
    }
}
