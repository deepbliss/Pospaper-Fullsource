<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model;

use Aheadworks\RewardPoints\Model\ProductShareRepository;
use Aheadworks\RewardPoints\Model\ResourceModel\ProductShare as ProductShareResource;
use Aheadworks\RewardPoints\Api\Data\ProductShareInterface;
use Aheadworks\RewardPoints\Api\Data\ProductShareInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\ProductShareRepositoryTest
 */
class ProductShareRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ProductShareRepository
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ProductShareResource
     */
    private $resourceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|EntityManager
     */
    private $entityManagerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ProductShareInterfaceFactory
     */
    private $productShareFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StoreManagerInterface
     */
    private $storeManagerMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->resourceMock = $this->getMockBuilder(ProductShareResource::class)
            ->disableOriginalConstructor()
            ->setMethods(['getShareRowId'])
            ->getMock();

        $this->entityManagerMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['load', 'save', 'delete']
            )
            ->getMock();

        $this->productShareFactoryMock = $this->getMockBuilder(ProductShareInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStore'])
            ->getMockForAbstractClass();

        $data = [
            'resource' => $this->resourceMock,
            'productShareFactory' => $this->productShareFactoryMock,
            'entityManager' => $this->entityManagerMock,
            'storeManager' => $this->storeManagerMock,
        ];

        $this->object = $objectManager->getObject(ProductShareRepository::class, $data);
    }

    /**
     * Test get method
     */
    public function testGetMethod()
    {
        $customerId = 2;
        $productId = 30;
        $network = 'facebook';

        $shareId = 3;

        $productShareModelMock = $this->getMockBuilder(ProductShareInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();
        $productShareModelMock->expects($this->once())
            ->method('getId')
            ->willReturn($shareId);

        $this->productShareFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($productShareModelMock);

        $this->resourceMock->expects($this->once())
            ->method('getShareRowId')
            ->with($customerId, $productId, $network)
            ->willReturn($shareId);

        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($productShareModelMock, $shareId)
            ->willReturn($productShareModelMock);

        $this->object->get($customerId, $productId, $network);
    }

    /**
     * Test getById method
     */
    public function testGetByIdMethod()
    {
        $shareId = 3;

        $productShareModelMock = $this->getMockBuilder(ProductShareInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();
        $productShareModelMock->expects($this->once())
            ->method('getId')
            ->willReturn($shareId);

        $this->productShareFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($productShareModelMock);

        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($productShareModelMock, $shareId)
            ->willReturn($productShareModelMock);

        $expected = $this->object->getById($shareId);

        $this->assertSame($expected, $productShareModelMock);
        $this->assertSame($expected, $this->object->getById($shareId));
    }

    /**
     * Test save method
     */
    public function testSaveMethod()
    {
        $shareId = 3;

        $productShareModelMock = $this->getMockBuilder(ProductShareInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();
        $productShareModelMock->expects($this->once())
            ->method('getId')
            ->willReturn($shareId);

        $this->entityManagerMock->expects($this->once())
            ->method('save')
            ->with($productShareModelMock)
            ->willReturn($productShareModelMock);

        $expected = $this->object->save($productShareModelMock);

        $this->assertSame($expected, $productShareModelMock);
    }

    /**
     * Test delete method
     */
    public function testDeleteMethod()
    {
        $shareId = 3;

        $productShareModelMock = $this->getMockBuilder(ProductShareInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();
        $productShareModelMock->expects($this->once())
            ->method('getId')
            ->willReturn($shareId);

        $this->entityManagerMock->expects($this->once())
            ->method('delete')
            ->with($productShareModelMock)
            ->willReturnSelf();

        $this->assertTrue($this->object->delete($productShareModelMock));
    }

    /**
     * Test deleteById method
     */
    public function testDeleteByIdMethod()
    {
        $shareId = 3;

        $productShareModelMock = $this->getMockBuilder(ProductShareInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();

        $productShareModelMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturn($shareId);

        $this->productShareFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($productShareModelMock);

        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($productShareModelMock, $shareId)
            ->willReturn($productShareModelMock);

        $this->entityManagerMock->expects($this->once())
            ->method('delete')
            ->with($productShareModelMock)
            ->willReturnSelf();

        $this->assertTrue($this->object->deleteById($shareId));
    }
}
