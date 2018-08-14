<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model\Service;

use Aheadworks\RewardPoints\Model\Service\RewardPointsCartService;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\Service$RewardPointsCartServiceTest
 */
class RewardPointsCartServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RewardPointsCartService
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsManagementMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CartRepositoryInterface
     */
    private $quoteRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CartInterface
     */
    private $quoteMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->customerRewardPointsManagementMock = $this->getMockBuilder(
            CustomerRewardPointsManagementInterface::class
        )
            ->disableOriginalConstructor()
            ->setMethods(['getCustomerRewardPointsBalance'])
            ->getMockForAbstractClass();

        $this->quoteRepositoryMock = $this->getMockBuilder(CartRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getActive', 'save'])
            ->getMockForAbstractClass();

        $this->quoteMock = $this->getMockBuilder(CartInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getAwUseRewardPoints',
                    'setAwUseRewardPoints',
                    'getItemsCount',
                    'getCustomerId',
                    'getShippingAddress',
                    'collectTotals',
                    'getStore'
                ]
            )->getMockForAbstractClass();

        $data = [
            'customerRewardPointsService' => $this->customerRewardPointsManagementMock,
            'quoteRepository' => $this->quoteRepositoryMock
        ];

        $this->object = $objectManager->getObject(RewardPointsCartService::class, $data);
    }

    /**
     * Test get method
     */
    public function testGetMethod()
    {
        $cartId = 5;
        $awUseRewardPoints = true;

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')
            ->with($cartId)
            ->willReturn($this->quoteMock);

        $this->quoteMock->expects($this->once())
            ->method('getItemsCount')
            ->willReturn(1);

        $this->quoteMock->expects($this->once())
            ->method('getAwUseRewardPoints')
            ->willReturn($awUseRewardPoints);

        $this->assertTrue($this->object->get($cartId));
    }

    /**
     * Test get method, throw exception
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage Cart 5 doesn't contain products
     */
    public function testGetMethodException()
    {
        $cartId = 5;
        $awUseRewardPoints = true;

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')
            ->with($cartId)
            ->willReturn($this->quoteMock);

        $this->quoteMock->expects($this->once())
            ->method('getItemsCount')
            ->willReturn(0);

        $this->quoteMock->expects($this->never())
            ->method('getAwUseRewardPoints')
            ->willReturn($awUseRewardPoints);

        $this->object->get($cartId);
    }

    /**
     * Test set method
     */
    public function testSetMethod()
    {
        $cartId = 10;
        $customerId = 4;

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')
            ->with($cartId)
            ->willReturn($this->quoteMock);

        $this->quoteMock->expects($this->once())
            ->method('getItemsCount')
            ->willReturn(2);
        $this->quoteMock->expects($this->exactly(3))
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->customerRewardPointsManagementMock->expects($this->once())
            ->method('getCustomerRewardPointsBalance')
            ->with($customerId)
            ->willReturn(12);

        $shippingAddressMock = $this->getMockBuilder(AddressInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['setCollectShippingRates'])
            ->getMockForAbstractClass();
        $shippingAddressMock->expects($this->once())
            ->method('setCollectShippingRates')
            ->with(true)
            ->willReturnSelf();

        $this->quoteMock->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($shippingAddressMock);
        $this->quoteMock->expects($this->once())
            ->method('setAwUseRewardPoints')
            ->with(true)
            ->willReturnSelf();
        $this->quoteMock->expects($this->once())
            ->method('collectTotals')
            ->willReturnSelf();
        $storeMock = $this->getMockBuilder(StoreInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWebsiteId'])
            ->getMockForAbstractClass();
        $storeMock->expects($this->exactly(2))
            ->method('getWebsiteId')
            ->willReturn(1);
        $this->quoteMock->expects($this->exactly(2))
            ->method('getStore')
            ->willReturn($storeMock);

        $this->quoteRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->quoteMock)
            ->willReturnSelf();
        $this->quoteMock->expects($this->once())
            ->method('getAwUseRewardPoints')
            ->willReturn(true);
        $this->assertTrue(is_array($this->object->set($cartId)));
    }

    /**
     * Test set method if quote not has items
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage Cart 10 doesn't contain products
     */
    public function testSetMethodNotQuoteItems()
    {
        $cartId = 10;

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')
            ->with($cartId)
            ->willReturn($this->quoteMock);

        $this->quoteMock->expects($this->once())
            ->method('getItemsCount')
            ->willReturn(0);

        $this->object->set($cartId);
    }

    /**
     * Test set method if customer id is null
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage No reward points to be used
     */
    public function testSetMethodNullCustomerId()
    {
        $cartId = 10;

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')
            ->with($cartId)
            ->willReturn($this->quoteMock);

        $this->quoteMock->expects($this->once())
            ->method('getItemsCount')
            ->willReturn(1);
        $this->quoteMock->expects($this->exactly(2))
            ->method('getCustomerId')
            ->willReturn(null);

        $storeMock = $this->getMockBuilder(StoreInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWebsiteId'])
            ->getMockForAbstractClass();
        $storeMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn(1);
        $this->quoteMock->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);

        $this->object->set($cartId);
    }

    /**
     * Test set method if customer has null reward points balance
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage No reward points to be used
     */
    public function testSetMethodNullCustomerRewardPointsBalance()
    {
        $cartId = 10;
        $customerId = 5;

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')
            ->with($cartId)
            ->willReturn($this->quoteMock);

        $this->quoteMock->expects($this->once())
            ->method('getItemsCount')
            ->willReturn(1);
        $this->quoteMock->expects($this->exactly(3))
            ->method('getCustomerId')
            ->willReturn($customerId);

        $storeMock = $this->getMockBuilder(StoreInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWebsiteId'])
            ->getMockForAbstractClass();
        $storeMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn(1);
        $this->quoteMock->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);

        $this->customerRewardPointsManagementMock->expects($this->once())
            ->method('getCustomerRewardPointsBalance')
            ->with($customerId)
            ->willReturn(0);

        $this->object->set($cartId);
    }

    /**
     * Test set method throw exception at save repository
     *
     * @expectedException \Magento\Framework\Exception\CouldNotSaveException
     * @expectedExceptionMessage Could not apply reward points
     */
    public function testSetMethodThrowExceptionAtSaveRepository()
    {
        $cartId = 10;
        $customerId = 4;

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')
            ->with($cartId)
            ->willReturn($this->quoteMock);

        $this->quoteMock->expects($this->once())
            ->method('getItemsCount')
            ->willReturn(2);
        $this->quoteMock->expects($this->exactly(3))
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->customerRewardPointsManagementMock->expects($this->once())
            ->method('getCustomerRewardPointsBalance')
            ->with($customerId)
            ->willReturn(12);

        $shippingAddressMock = $this->getMockBuilder(AddressInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['setCollectShippingRates'])
            ->getMockForAbstractClass();
        $shippingAddressMock->expects($this->once())
            ->method('setCollectShippingRates')
            ->with(true)
            ->willReturnSelf();

        $this->quoteMock->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($shippingAddressMock);
        $this->quoteMock->expects($this->once())
            ->method('setAwUseRewardPoints')
            ->with(true)
            ->willReturnSelf();
        $this->quoteMock->expects($this->once())
            ->method('collectTotals')
            ->willReturnSelf();

        $storeMock = $this->getMockBuilder(StoreInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWebsiteId'])
            ->getMockForAbstractClass();
        $storeMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn(1);
        $this->quoteMock->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);

        $this->quoteRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->quoteMock)
            ->willThrowException(new \Exception('Oh oh oh!!!'));

        $this->object->set($cartId);
    }

    /**
     * Test remove method
     */
    public function testRemoveMethod()
    {
        $cartId = 9;

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')
            ->with($cartId)
            ->willReturn($this->quoteMock);

        $this->quoteMock->expects($this->once())
            ->method('getItemsCount')
            ->willReturn(2);

        $shippingAddressMock = $this->getMockBuilder(AddressInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['setCollectShippingRates'])
            ->getMockForAbstractClass();
        $shippingAddressMock->expects($this->once())
            ->method('setCollectShippingRates')
            ->with(true)
            ->willReturnSelf();

        $this->quoteMock->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($shippingAddressMock);
        $this->quoteMock->expects($this->once())
            ->method('setAwUseRewardPoints')
            ->with(false)
            ->willReturnSelf();
        $this->quoteMock->expects($this->once())
            ->method('collectTotals')
            ->willReturnSelf();

        $this->quoteRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->quoteMock)
            ->willReturnSelf();

        $this->assertTrue($this->object->remove($cartId));
    }

    /**
     * Test remove method if quote not has items
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage Cart 21 doesn't contain products
     */
    public function testRemoveMethodNotQuoteItems()
    {
        $cartId = 21;

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')
            ->with($cartId)
            ->willReturn($this->quoteMock);

        $this->quoteMock->expects($this->once())
            ->method('getItemsCount')
            ->willReturn(0);

        $this->object->remove($cartId);
    }

    /**
     * Test remove method throw exception at save repository
     *
     * @expectedException \Magento\Framework\Exception\CouldNotDeleteException
     * @expectedExceptionMessage Could not remove reward points
     */
    public function testRemoveMethodThrowExceptionAtSaveRepository()
    {
        $cartId = 12;

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')
            ->with($cartId)
            ->willReturn($this->quoteMock);

        $this->quoteMock->expects($this->once())
            ->method('getItemsCount')
            ->willReturn(2);

        $shippingAddressMock = $this->getMockBuilder(AddressInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['setCollectShippingRates'])
            ->getMockForAbstractClass();
        $shippingAddressMock->expects($this->once())
            ->method('setCollectShippingRates')
            ->with(true)
            ->willReturnSelf();

        $this->quoteMock->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($shippingAddressMock);
        $this->quoteMock->expects($this->once())
            ->method('setAwUseRewardPoints')
            ->with(false)
            ->willReturnSelf();
        $this->quoteMock->expects($this->once())
            ->method('collectTotals')
            ->willReturnSelf();

        $this->quoteRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->quoteMock)
            ->willThrowException(new \Exception('Oh oh oh!!!'));

        $this->object->remove($cartId);
    }
}
