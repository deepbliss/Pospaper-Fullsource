<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Test\Unit\Model;

use Aheadworks\Freeshippinglabel\Api\Data\LabelInterface;
use Aheadworks\Freeshippinglabel\Model\Label;
use Aheadworks\Freeshippinglabel\Model\LabelRepository;
use Aheadworks\Freeshippinglabel\Api\Data\LabelInterfaceFactory;
use Magento\Framework\EntityManager\EntityManager;
use Aheadworks\Freeshippinglabel\Model\LabelFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Freeshippinglabel\Model\LabelRepository
 */
class LabelRepositoryTest extends TestCase
{
    /**
     * @var LabelRepository
     */
    private $model;

    /**
     * @var EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $entityManagerMock;

    /**
     * @var LabelFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $labelFactoryMock;

    /**
     * @var LabelInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $labelDataFactoryMock;

    /**
     * @var array
     */
    private $labelData = [ 'id' => 1];

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->entityManagerMock = $this->createPartialMock(
            EntityManager::class,
            ['load', 'delete', 'save']
        );
        $this->labelFactoryMock = $this->createPartialMock(
            LabelFactory::class,
            ['create']
        );
        $this->labelDataFactoryMock = $this->createPartialMock(
            LabelInterfaceFactory::class,
            ['create']
        );
        $this->model = $objectManager->getObject(
            LabelRepository::class,
            [
                'entityManager' => $this->entityManagerMock,
                'labelFactory' => $this->labelFactoryMock,
                'labelDataFactory' => $this->labelDataFactoryMock,
            ]
        );
    }

    /**
     * Testing of save method
     */
    public function testSave()
    {
        $labelMock = $this->createPartialMock(
            Label::class,
            ['getId', 'getData']
        );
        $labelMock->expects($this->once())
            ->method('getId')
            ->willReturn($this->labelData['id']);
        $labelMock->expects($this->once())
            ->method('getData')
            ->willReturn($this->labelData);
        $labelModelMock = $this->createPartialMock(
            Label::class,
            ['addData']
        );
        $this->labelFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($labelModelMock);
        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($labelModelMock, $this->labelData['id']);
        $labelModelMock->expects($this->once())
            ->method('addData');
        $this->entityManagerMock->expects($this->once())
            ->method('save')
            ->with($labelModelMock);
        $this->assertSame($labelMock, $this->model->save($labelMock));
    }

    /**
     * Testing of get method
     */
    public function testGet()
    {
        $labelModelMock = $this->createPartialMock(
            Label::class,
            ['getId']
        );
        $this->labelDataFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($labelModelMock);
        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($labelModelMock, $this->labelData['id']);
        $labelModelMock->expects($this->once())
            ->method('getId')
            ->willReturn($this->labelData['id']);
        $this->assertSame($labelModelMock, $this->model->get($this->labelData['id']));
    }
}
