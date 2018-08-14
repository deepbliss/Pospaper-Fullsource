<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model\Config\Backend;

use Aheadworks\RewardPoints\Model\Config\Backend\SpendRate;
use Aheadworks\RewardPoints\Model\ResourceModel\SpendRate as SpendRateResource;
use Aheadworks\RewardPoints\Model\ResourceModel\SpendRate\CollectionFactory;
use Aheadworks\RewardPoints\Model\ResourceModel\SpendRate\Collection;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\Config\Backend\SpendRateTest
 */
class SpendRateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|SpendRateResource
     */
    private $spendRateResourceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CollectionFactory
     */
    private $spendRateCollectionFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Collection
     */
    private $spendRateCollectionMock;

    /**
     * @var SpendRate
     */
    private $object;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->spendRateResourceMock = $this->getMockBuilder(SpendRateResource::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['saveConfigValue']
            )
            ->getMock();

        $this->spendRateCollectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['create']
            )
            ->getMock();

        $this->spendRateCollectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['toConfigDataArray']
            )
            ->getMock();

        $data = [
            'spendRateResource' => $this->spendRateResourceMock,
            'spendRateCollectionFactory' => $this->spendRateCollectionFactoryMock
        ];

        $this->object = $objectManager->getObject(SpendRate::class, $data);
    }

    /**
     * Test beforeSave method
     *
     * @dataProvider valueProvider
     */
    public function testBeforeSave($expectedValue)
    {
        $this->object->setValue($expectedValue);
        $this->object->beforeSave();

        $this->assertEquals($expectedValue, $this->object->getSpendRateValue());
        $this->assertEquals(serialize($expectedValue), $this->object->getValue());
    }

    /**
     * Test afterSave method
     *
     * @dataProvider valueProvider
     */
    public function testAfterSave($expectedValue)
    {
        $this->object->setSpendRateValue($expectedValue);

        $this->spendRateResourceMock->expects($this->once())
            ->method('saveConfigValue')
            ->with($expectedValue)
            ->willReturnSelf();

        $this->object->afterSave();
    }

    /**
     * Test afterLoad method
     */
    public function testAfterLoad()
    {
        $expectedResult = [[1, 2, 3, 4], [5, 6, 7, 8]];

        $this->spendRateCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->spendRateCollectionMock);

        $this->spendRateCollectionMock->expects($this->once())
            ->method('toConfigDataArray')
            ->willReturn($expectedResult);

        $this->object->afterLoad();

        $this->assertEquals($expectedResult, $this->object->getValue());
    }

    /**
     * Test data provider
     *
     * @return array
     */
    public function valueProvider()
    {
        return [
            ['setTestConfigValue'],
            [15],
            [null],
            [new \stdClass(11)],
            [[1,2,3,4]]
        ];
    }
}
