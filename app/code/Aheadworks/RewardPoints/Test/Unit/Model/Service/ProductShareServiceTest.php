<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model\Service;

use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Aheadworks\RewardPoints\Api\ProductShareRepositoryInterface;
use Aheadworks\RewardPoints\Api\Data\ProductShareInterfaceFactory;
use Aheadworks\RewardPoints\Api\Data\ProductShareInterface;
use Aheadworks\RewardPoints\Model\Service\ProductShareService;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\Service\ProductShareServiceTest
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductShareServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ProductShareService
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsServiceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ProductRepositoryInterface
     */
    private $productRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ProductInterface
     */
    private $productMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CustomerRepositoryInterface
     */
    private $customerRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CustomerInterface
     */
    private $customerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ProductShareRepositoryInterface
     */
    private $productShareRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ProductShareInterfaceFactory
     */
    private $productShareFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ProductShareInterface
     */
    private $productShareMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StoreManagerInterface
     */
    private $storeManagerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StoreInterface
     */
    private $storeMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->productShareMock = $this->getMockBuilder(ProductShareInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'setCustomerId',
                    'setProductId',
                    'setNetwork',
                    'setWebsiteId'
                ]
            )
            ->getMockForAbstractClass();

        $this->customerRewardPointsServiceMock = $this->getMockBuilder(CustomerRewardPointsManagementInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['addPointsForShares'])
            ->getMockForAbstractClass();

        $this->productShareRepositoryMock = $this->getMockBuilder(ProductShareRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'create', 'get'])
            ->getMockForAbstractClass();

        $this->productShareFactoryMock = $this->getMockBuilder(ProductShareInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStore'])
            ->getMockForAbstractClass();

        $this->storeMock = $this->getMockBuilder(StoreInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getWebsiteId'])
            ->getMockForAbstractClass();

        $this->productRepositoryMock = $this->getMockBuilder(ProductRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getById'])
            ->getMockForAbstractClass();

        $this->productMock = $this->getMockBuilder(ProductInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();

        $this->customerRepositoryMock = $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getById'])
            ->getMockForAbstractClass();

        $this->customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();

        $data = [
            'customerRewardPointsService' => $this->customerRewardPointsServiceMock,
            'productRepository' => $this->productRepositoryMock,
            'customerRepository' => $this->customerRepositoryMock,
            'productShareRepository' => $this->productShareRepositoryMock,
            'productShareFactory' => $this->productShareFactoryMock,
            'storeManager' => $this->storeManagerMock
        ];

        $this->object = $objectManager->getObject(ProductShareService::class, $data);
    }

    /**
     * Test add method
     */
    public function testAddMethod()
    {
        $customerId = 4;
        $productId = 8;
        $network = 'twitter';
        $websiteId = 1;

        $this->expectedCustomerMock($customerId);
        $this->customerRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($this->customerMock);

        $this->expectedProductMock($productId);
        $this->productRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($productId)
            ->willReturn($this->productMock);

        $this->expectedStoreMock($websiteId);
        $this->expectedproductShareMock(
            $customerId,
            $productId,
            $network,
            $websiteId
        );
        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($this->storeMock);

        $this->productShareFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->productShareMock);
        $this->productShareRepositoryMock->expects($this->once())
            ->method('get')
            ->with($customerId, $productId, $network)
            ->willReturn($this->productShareMock);
        $this->productShareRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->productShareMock)
            ->willReturn(true);

        $this->customerRewardPointsServiceMock->expects($this->once())
            ->method('addPointsForShares')
            ->with($customerId, $productId, $network)
            ->willReturn(null);

        $this->assertTrue($this->object->add(
            $customerId,
            $productId,
            $network
        ));
    }

    /**
     * Private method for expected product model
     *
     * @param int $productId
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductInterface
     */
    private function expectedProductMock($productId)
    {
        $this->productMock->expects($this->once())
            ->method('getId')
            ->willReturn($productId);

        return $this->productMock;
    }

    /**
     * Private method for expected customer model
     *
     * @param int $customerId
     * @return \PHPUnit_Framework_MockObject_MockObject|CustomerInterface
     */
    private function expectedCustomerMock($customerId)
    {
        $this->customerMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        return $this->customerMock;
    }

    /**
     * Private method for expected productShare model
     *
     * @param int $customerId
     * @param int $productId
     * @param string $network
     * @param int $websiteId
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductShareInterface
     */
    private function expectedProductShareMock(
        $customerId,
        $productId,
        $network,
        $websiteId
    ) {
        $this->productShareMock->expects($this->once())
            ->method('setCustomerId')
            ->with($customerId)
            ->willReturnSelf();
        $this->productShareMock->expects($this->once())
            ->method('setProductId')
            ->with($productId)
            ->willReturnSelf();
        $this->productShareMock->expects($this->once())
            ->method('setNetwork')
            ->with($network)
            ->willReturnSelf();
        $this->productShareMock->expects($this->once())
            ->method('setWebsiteId')
            ->with($websiteId)
            ->willReturnSelf();

        return $this->productShareMock;
    }

    /**
     * Private method for expected store model
     *
     * @param int $websiteId
     * @return \PHPUnit_Framework_MockObject_MockObject|StoreInterface
     */
    private function expectedStoreMock($websiteId)
    {
        $this->storeMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        return $this->storeMock;
    }
}
