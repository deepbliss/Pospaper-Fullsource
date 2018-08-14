<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model;

use Aheadworks\RewardPoints\Model\SpendRateRepository;
use Aheadworks\RewardPoints\Model\ResourceModel\SpendRate as SpendRateResource;
use Aheadworks\RewardPoints\Api\Data\SpendRateInterface;
use Aheadworks\RewardPoints\Api\Data\SpendRateInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\SpendRateRepositoryTest
 */
class SpendRateRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SpendRateRepository
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|SpendRateResource
     */
    private $resourceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|EntityManager
     */
    private $entityManagerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|SpendRateInterfaceFactory
     */
    private $spendRateFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StoreManagerInterface
     */
    private $storeManagerMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->resourceMock = $this->getMockBuilder(SpendRateResource::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRateRowId'])
            ->getMock();

        $this->entityManagerMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['load', 'save', 'delete']
            )
            ->getMock();

        $this->spendRateFactoryMock = $this->getMockBuilder(SpendRateInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStore'])
            ->getMockForAbstractClass();

        $data = [
            'resource' => $this->resourceMock,
            'spendRateFactory' => $this->spendRateFactoryMock,
            'entityManager' => $this->entityManagerMock,
            'storeManager' => $this->storeManagerMock,
        ];

        $this->object = $objectManager->getObject(SpendRateRepository::class, $data);
    }

    /**
     * Test get method
     */
    public function testGetMethod()
    {
        $customerGroupId = 1;
        $lifetimeSalesAmount = 100;
        $websiteId = 1;

        $rateId = 3;

        $spendRateModelMock = $this->getMockBuilder(SpendRateInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();
        $spendRateModelMock->expects($this->once())
            ->method('getId')
            ->willReturn($rateId);

        $this->spendRateFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($spendRateModelMock);

        $this->resourceMock->expects($this->once())
            ->method('getRateRowId')
            ->with($customerGroupId, $lifetimeSalesAmount, $websiteId)
            ->willReturn($rateId);

        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($spendRateModelMock, $rateId)
            ->willReturn($spendRateModelMock);

        $this->object->get($customerGroupId, $lifetimeSalesAmount, $websiteId);
    }

    /**
     * Test get method for null website id param
     */
    public function testGetMethodNullWebsiteId()
    {
        $customerGroupId = 1;
        $lifetimeSalesAmount = 100;
        $websiteId = null;

        $rateId = 4;

        $spendRateModelMock = $this->getMockBuilder(SpendRateInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();
        $spendRateModelMock->expects($this->once())
            ->method('getId')
            ->willReturn($rateId);

        $storeMock = $this->getMockBuilder(StoreInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWebsiteId'])
            ->getMockForAbstractClass();
        $storeMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn(1);

        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);

        $this->spendRateFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($spendRateModelMock);

        $this->resourceMock->expects($this->once())
            ->method('getRateRowId')
            ->with($customerGroupId, $lifetimeSalesAmount, 1)
            ->willReturn($rateId);

        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($spendRateModelMock, $rateId)
            ->willReturn($spendRateModelMock);

        $this->object->get($customerGroupId, $lifetimeSalesAmount, $websiteId);
    }

    /**
     * Test getById method
     */
    public function testGetByIdMethod()
    {
        $rateId = 5;

        $spendRateModelMock = $this->getMockBuilder(SpendRateInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();
        $spendRateModelMock->expects($this->once())
            ->method('getId')
            ->willReturn($rateId);

        $this->spendRateFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($spendRateModelMock);

        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($spendRateModelMock, $rateId)
            ->willReturn($spendRateModelMock);

        $expected = $this->object->getById($rateId);

        $this->assertSame($expected, $spendRateModelMock);
        $this->assertSame($expected, $this->object->getById($rateId));
    }

    /**
     * Test save method
     */
    public function testSaveMethod()
    {
        $rateId = 5;

        $spendRateModelMock = $this->getMockBuilder(SpendRateInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();
        $spendRateModelMock->expects($this->once())
            ->method('getId')
            ->willReturn($rateId);

        $this->entityManagerMock->expects($this->once())
            ->method('save')
            ->with($spendRateModelMock)
            ->willReturn($spendRateModelMock);

        $expected = $this->object->save($spendRateModelMock);
        $this->assertSame($expected, $spendRateModelMock);
    }

    /**
     * Test delete method
     */
    public function testDeleteMethod()
    {
        $rateId = 5;

        $spendRateModelMock = $this->getMockBuilder(SpendRateInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();
        $spendRateModelMock->expects($this->once())
            ->method('getId')
            ->willReturn($rateId);

        $this->entityManagerMock->expects($this->once())
            ->method('delete')
            ->with($spendRateModelMock)
            ->willReturnSelf();

        $this->assertTrue($this->object->delete($spendRateModelMock));
    }

    /**
     * Test deleteById method
     */
    public function testDeleteByIdMethod()
    {
        $rateId = 5;

        $spendRateModelMock = $this->getMockBuilder(SpendRateInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();

        $spendRateModelMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturn($rateId);

        $this->spendRateFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($spendRateModelMock);

        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($spendRateModelMock, $rateId)
            ->willReturn($spendRateModelMock);

        $this->entityManagerMock->expects($this->once())
            ->method('delete')
            ->with($spendRateModelMock)
            ->willReturnSelf();

        $this->assertTrue($this->object->deleteById($rateId));
    }
}
