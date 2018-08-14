<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model\Config\Backend;

use Aheadworks\RewardPoints\Model\Config\Backend\EarnRate;
use Aheadworks\RewardPoints\Model\ResourceModel\EarnRate as EarnRateResource;
use Aheadworks\RewardPoints\Model\ResourceModel\EarnRate\CollectionFactory;
use Aheadworks\RewardPoints\Model\ResourceModel\EarnRate\Collection;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\Config\Backend\EarnRateTest
 */
class EarnRateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|EarnRateResource
     */
    private $earnRateResourceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CollectionFactory
     */
    private $earnRateCollectionFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Collection
     */
    private $earnRateCollectionMock;

    /**
     * @var EarnRate
     */
    private $object;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->earnRateResourceMock = $this->getMockBuilder(EarnRateResource::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['saveConfigValue']
            )
            ->getMock();

        $this->earnRateCollectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['create']
            )
            ->getMock();

        $this->earnRateCollectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['toConfigDataArray']
            )
            ->getMock();

        $data = [
            'earnRateResource' => $this->earnRateResourceMock,
            'earnRateCollectionFactory' => $this->earnRateCollectionFactoryMock,
        ];

        $this->object = $objectManager->getObject(EarnRate::class, $data);
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

        $this->assertEquals($expectedValue, $this->object->getEarnRateValue());
        $this->assertEquals(serialize($expectedValue), $this->object->getValue());
    }

    /**
     * Test afterSave method
     *
     * @dataProvider valueProvider
     */
    public function testAfterSave($expectedValue)
    {
        $this->object->setEarnRateValue($expectedValue);

        $this->earnRateResourceMock->expects($this->once())
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

        $this->earnRateCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->earnRateCollectionMock);

        $this->earnRateCollectionMock->expects($this->once())
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
