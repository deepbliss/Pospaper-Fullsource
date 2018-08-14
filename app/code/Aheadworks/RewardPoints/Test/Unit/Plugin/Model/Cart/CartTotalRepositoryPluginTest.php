<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Unit\Test\Plugin\Model\Cart;

use Aheadworks\RewardPoints\Plugin\Model\Cart\CartTotalRepositoryPlugin;
use Magento\Quote\Api\Data\TotalsExtensionFactory;
use Magento\Quote\Api\Data\TotalsExtensionInterface;
use Magento\Quote\Api\Data\TotalsInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Cart\CartTotalRepository as TotalRepository;
use Magento\Quote\Model\Quote;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Quote\Model\Quote\Address;

/**
 * Class Aheadworks\RewardPoints\Unit\Test\Plugin\Model\Cart\CartTotalRepositoryPluginTest
 */
class CartTotalRepositoryPluginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CartTotalRepositoryPlugin
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CartRepositoryInterface
     */
    private $quoteRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CartRepositoryInterface
     */
    private $totalsExtensionFactoryMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->quoteRepositoryMock = $this->getMockBuilder(CartRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getActive'])
            ->getMockForAbstractClass();

        $this->totalsExtensionFactoryMock = $this->getMockBuilder(TotalsExtensionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $data = [
            'quoteRepository' => $this->quoteRepositoryMock,
            'totalsExtensionFactory' => $this->totalsExtensionFactoryMock,
        ];

        $this->object = $objectManager->getObject(CartTotalRepositoryPlugin::class, $data);
    }

    /**
     * Test aroundGet method
     */
    public function testAroundGetMethod()
    {
        $cartId = 11;
        $billingAddressData = [
            'aw_reward_points_shipping_amount' => 5,
            'base_aw_reward_points_shipping_amount' => 5,
            'aw_reward_points_shipping' => 5
        ];
        $totalRepositoryMock = $this->getMockBuilder(TotalRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $quoteMock = $this->getMockBuilder(Quote::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getAwUseRewardPoints',
                    'getAwRewardPointsAmount',
                    'getBaseAwRewardPointsAmount',
                    'getAwRewardPoints',
                    'getAwRewardPointsDescription',
                    'isVirtual',
                    'getBillingAddress'
                ]
            )->getMock();
        $quoteMock->expects($this->once())
            ->method('isVirtual')
            ->willReturn(true);
        $billingAddressMock = $this->getMockBuilder(Address::class)
            ->setMethods(['getData'])
            ->disableOriginalConstructor()
            ->getMock();
        $billingAddressMock->expects($this->once())
            ->method('getData')
            ->willReturn($billingAddressData);
        $quoteMock->expects($this->once())
            ->method('getBillingAddress')
            ->willReturn($billingAddressMock);
        $quoteMock->expects($this->once())
            ->method('getAwRewardPointsAmount')
            ->willReturn(11);
        $quoteMock->expects($this->once())
            ->method('getBaseAwRewardPointsAmount')
            ->willReturn(11);
        $quoteMock->expects($this->once())
            ->method('getAwRewardPoints')
            ->willReturn(110);
        $quoteMock->expects($this->once())
            ->method('getAwRewardPointsDescription')
            ->willReturn('110 Reward Points');

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')
            ->willReturn($quoteMock);

        $totalsMock = $this->getMockBuilder(TotalsInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getExtensionAttributes',
                    'setExtensionAttributes',
                ]
            )
            ->getMockForAbstractClass();

        $totalsMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn(null);

        $extensionAttributesMock = $this->getMockBuilder(TotalsExtensionInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'setAwRewardPointsAmount',
                    'setBaseAwRewardPointsAmount',
                    'setAwRewardPoints',
                    'setAwRewardPointsDescription',
                    'setAwRewardPointsShippingAmount',
                    'setBaseAwRewardPointsShippingAmount',
                    'setAwRewardPointsShipping'
                ]
            )
            ->getMockForAbstractClass();

        $extensionAttributesMock->expects($this->once())
            ->method('setAwRewardPointsAmount')
            ->with(11)
            ->willReturnSelf();
        $extensionAttributesMock->expects($this->once())
            ->method('setBaseAwRewardPointsAmount')
            ->with(11)
            ->willReturnSelf();
        $extensionAttributesMock->expects($this->once())
            ->method('setAwRewardPoints')
            ->with(110)
            ->willReturnSelf();
        $extensionAttributesMock->expects($this->once())
            ->method('setAwRewardPointsDescription')
            ->with('110 Reward Points')
            ->willReturnSelf();
        $extensionAttributesMock->expects($this->exactly(2))
            ->method('setAwRewardPointsShippingAmount')
            ->willReturnSelf();
        $extensionAttributesMock->expects($this->exactly(2))
            ->method('setBaseAwRewardPointsShippingAmount')
            ->willReturnSelf();
        $extensionAttributesMock->expects($this->exactly(2))
            ->method('setAwRewardPointsShipping')
            ->willReturnSelf();

        $this->totalsExtensionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($extensionAttributesMock);

        $totalsMock->expects($this->once())
            ->method('setExtensionAttributes')
            ->with($extensionAttributesMock)
            ->willReturnSelf();

        $this->object->aroundGet(
            $totalRepositoryMock,
            function ($cartId) use ($totalsMock) {
                return $totalsMock;
            },
            $cartId
        );
    }
}
