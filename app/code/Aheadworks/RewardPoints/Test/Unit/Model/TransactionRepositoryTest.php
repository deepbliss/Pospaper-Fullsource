<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model;

use Aheadworks\RewardPoints\Model\TransactionRepository;
use Aheadworks\RewardPoints\Model\ResourceModel\Transaction as TransactionResource;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Aheadworks\RewardPoints\Api\Data\TransactionInterfaceFactory;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\TransactionRepositoryTest
 */
class TransactionRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TransactionRepository
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TransactionResource
     */
    private $resourceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|EntityManager
     */
    private $entityManagerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TransactionInterfaceFactory
     */
    private $modelFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TransactionInterface
     */
    private $dataModelMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->resourceMock = $this->getMockBuilder(TransactionResource::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'beginTransaction',
                    'commit',
                    'rollBack',
                    'save',
                ]
            )
            ->getMock();

        $this->entityManagerMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['load', 'save', 'delete']
            )
            ->getMock();

        $this->modelFactoryMock = $this->getMockBuilder(TransactionInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->dataModelMock = $this->getMockBuilder(TransactionInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTransactionId'])
            ->getMockForAbstractClass();

        $data = [
            'resource' => $this->resourceMock,
            'entityManager' => $this->entityManagerMock,
            'transactionFactory' => $this->modelFactoryMock,
        ];

        $this->object = $objectManager->getObject(TransactionRepository::class, $data);
    }

    /**
     * Test save method
     */
    public function testSaveMethod()
    {
        $this->entityManagerMock->expects($this->once())
            ->method('save')
            ->with($this->dataModelMock)
            ->willReturnSelf();

        $actual = $this->object->save($this->dataModelMock);

        $this->assertEquals($this->dataModelMock, $actual);
    }

    /**
     * Test save method throw exception
     *
     * @expectedException \Magento\Framework\Exception\CouldNotSaveException
     * @expectedExceptionMessage Unable save transaction
     */
    public function testSaveMethodThrowException()
    {
        $this->entityManagerMock->expects($this->once())
            ->method('save')
            ->with($this->dataModelMock)
            ->willThrowException(new \Exception('Unable save transaction'));

        $this->object->save($this->dataModelMock);
    }

    /**
     * Test getById method
     */
    public function testGetByIdMethod()
    {
        $this->dataModelMock->expects($this->exactly(2))
            ->method('getTransactionId')
            ->willReturn(1);

        $this->modelFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->dataModelMock);
        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($this->dataModelMock, 1)
            ->willReturnSelf();

        $actual = $this->object->getById(1);

        $this->assertEquals($actual, $this->object->getById(1));
    }

    /**
     * Test getById method throw exception
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage Requested transaction doesn't exist
     */
    public function testGetByIdMethodThrowException()
    {
        $this->dataModelMock->expects($this->once())
            ->method('getTransactionId')
            ->willReturn(null);

        $this->modelFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->dataModelMock);

        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($this->dataModelMock, 1)
            ->willReturnSelf();

        $actual = $this->object->getById(1);

        $this->assertEquals($actual, $this->object->getById(1));
    }
}
