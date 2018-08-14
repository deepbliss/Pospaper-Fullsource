<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Plugin\Model\Newsletter;

use Aheadworks\RewardPoints\Plugin\Model\Newsletter\SubscriberPlugin;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Newsletter\Model\Subscriber;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Plugin\Model\Newsletter\SubscriberPluginTest
 */
class SubscriberPluginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SubscriberPlugin
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsManagementMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Subscriber
     */
    private $subscriberMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->subscriberMock = $this->getMockBuilder(Subscriber::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getCustomerId',
                    'isSubscribed',
                ]
            )
            ->getMockForAbstractClass();

        $this->customerRewardPointsManagementMock = $this->getMockBuilder(
            CustomerRewardPointsManagementInterface::class
        )
            ->disableOriginalConstructor()
            ->setMethods(['addPointsForNewsletterSignup'])
            ->getMockForAbstractClass();

        $data = [
            'customerRewardPointsService' => $this->customerRewardPointsManagementMock,
        ];

        $this->object = $objectManager->getObject(SubscriberPlugin::class, $data);
    }

    /**
     * Test afterSave method
     */
    public function testAfterSaveMethod()
    {
        $customerId = 3;

        $this->customerRewardPointsManagementMock->expects($this->once())
            ->method('addPointsForNewsletterSignup')
            ->with($customerId)
            ->willReturn(true);

        $this->subscriberMock->expects($this->exactly(1))
            ->method('getCustomerId')
            ->willReturn($customerId);
        $this->subscriberMock->expects($this->once())
            ->method('isSubscribed')
            ->willReturn(true);

        $this->object->afterSave($this->subscriberMock);
    }

    /**
     * Test afterSave method for null customer id
     */
    public function testAfterRegisterMethodNullCustomer()
    {
        $customerId = null;

        $this->customerRewardPointsManagementMock->expects($this->never())
            ->method('addPointsForNewsletterSignup')
            ->willReturnSelf();

        $this->subscriberMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->object->afterSave($this->subscriberMock);
    }
}
