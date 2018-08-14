<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model\Source;

use Aheadworks\RewardPoints\Model\Comment\CommentPool;
use Aheadworks\RewardPoints\Model\Source\CommentToCustomer;
use Aheadworks\RewardPoints\Model\Comment\CommentDefault;
use Aheadworks\RewardPoints\Model\Comment\CommentForOrder;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\Source\CommentToCustomerTest
 */
class CommentToCustomerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CommentToCustomer
     */
    private $object;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CommentPool
     */
    private $commentPoolMock;

    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->commentPoolMock = $this->getMockBuilder(CommentPool::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getAllComments',
            ])
            ->getMockForAbstractClass();

        $data = [
            'commentPool' => $this->commentPoolMock,
        ];

        $this->object = $this->objectManager->getObject(CommentToCustomer::class, $data);
    }

    /**
     * Test toOptionArray method
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testToOptionArrayMethod()
    {
        $commentForPurchaseMock = $this->getMockBuilder(CommentForOrder::class)
            ->setMethods(['getLabel', 'getComment'])
            ->setConstructorArgs(
                [
                    'comment' => 'reward_for_order',
                    'label' => 'Reward points for order',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();
        $commentForPurchaseMock->expects($this->once())
            ->method('getLabel')
            ->willReturn('Reward points for order');
        $commentForPurchaseMock->expects($this->once())
            ->method('getComment')
            ->willReturn('reward_for_order');

        $commentForRegistrationMock = $this->getMockBuilder(CommentDefault::class)
            ->setMethods(['getLabel', 'getComment'])
            ->setConstructorArgs(
                [
                    'comment' => 'reward_for_registration',
                    'label' => 'Reward points for registration',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();
        $commentForRegistrationMock->expects($this->once())
            ->method('getLabel')
            ->willReturn('Reward points for registration');
        $commentForRegistrationMock->expects($this->once())
            ->method('getComment')
            ->willReturn('reward_for_registration');

        $commentForReview = $this->getMockBuilder(CommentDefault::class)
            ->setMethods(['getLabel', 'getComment'])
            ->setConstructorArgs(
                [
                    'comment' => 'reward_for_review',
                    'label' => 'Reward points for review',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();
        $commentForReview->expects($this->once())
            ->method('getLabel')
            ->willReturn('Reward points for review');
        $commentForReview->expects($this->once())
            ->method('getComment')
            ->willReturn('reward_for_review');

        $commentForNewsletterSignup = $this->getMockBuilder(CommentDefault::class)
            ->setMethods(['getLabel', 'getComment'])
            ->setConstructorArgs(
                [
                    'comment' => 'reward_for_newsletter_signup',
                    'label' => 'Reward points for newsletter signup',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();
        $commentForNewsletterSignup->expects($this->once())
            ->method('getLabel')
            ->willReturn('Reward points for newsletter signup');
        $commentForNewsletterSignup->expects($this->once())
            ->method('getComment')
            ->willReturn('reward_for_newsletter_signup');

        $commentSpendOnCheckout = $this->getMockBuilder(CommentForOrder::class)
            ->setMethods(['getLabel', 'getComment'])
            ->setConstructorArgs(
                [
                    'comment' => 'spent_for_order',
                    'label' => 'Spent reward points on order',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();
        $commentSpendOnCheckout->expects($this->once())
            ->method('getLabel')
            ->willReturn('Spent reward points on order');
        $commentSpendOnCheckout->expects($this->once())
            ->method('getComment')
            ->willReturn('spent_for_order');

        $commentExpired = $this->getMockBuilder(CommentDefault::class)
            ->setMethods(['getLabel', 'getComment'])
            ->setConstructorArgs(
                [
                    'comment' => 'expired_points',
                    'label' => 'Expired reward points',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();
        $commentExpired->expects($this->once())
            ->method('getLabel')
            ->willReturn('Expired reward points');
        $commentExpired->expects($this->once())
            ->method('getComment')
            ->willReturn('expired_points');

        $allComments = [
            'comment_for_purchases' => $commentForPurchaseMock,
            'comment_for_registration' => $commentForRegistrationMock,
            'comment_for_review' => $commentForReview,
            'comment_for_newsletter_signup' => $commentForNewsletterSignup,
            'comment_spend_on_checkout' => $commentSpendOnCheckout,
            'comment_expired' => $commentExpired,
        ];

        $expectedValue = [
            [
                'value' => 'reward_for_order',
                'label' => 'Reward points for order',
            ],
            [
                'value' => 'reward_for_registration',
                'label' => 'Reward points for registration',
            ],
            [
                'value' => 'reward_for_review',
                'label' => 'Reward points for review',
            ],
            [
                'value' => 'reward_for_newsletter_signup',
                'label' => 'Reward points for newsletter signup',
            ],
            [
                'value' => 'spent_for_order',
                'label' => 'Spent reward points on order',
            ],
            [
                'value' => 'expired_points',
                'label' => 'Expired reward points',
            ],
        ];

        $this->commentPoolMock->expects($this->once())
            ->method('getAllComments')
            ->willReturn($allComments);

        $this->assertEquals($expectedValue, $this->object->toOptionArray());
    }
}
