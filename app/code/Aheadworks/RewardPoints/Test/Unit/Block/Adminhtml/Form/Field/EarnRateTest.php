<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Block\Adminhtml\Form\Field;

use Aheadworks\RewardPoints\Block\Adminhtml\Form\Field\EarnRate;
use Aheadworks\RewardPoints\Model\Source\Customer\Group as CustomerGroup;
use Magento\Framework\Data\Form\Element\Factory as ElementFactory;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Json\Helper\Data as JsonHelperData;
use Magento\Store\Model\System\Store;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Block\System\Config\Form\Field\EarnRateTest
 */
class EarnRateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var array
     */
    private $cellParameters = [
        'size'  => 'testSize',
        'style' => 'testStyle',
        'class' => 'testClass',
    ];

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ElementFactory
     */
    private $elementFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|AbstractElement
     */
    private $elementMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Store
     */
    private $systemStoreMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CustomerGroup
     */
    private $customerGroupMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JsonHelperData
     */
    private $jsonHelperDataMock;

    /**
     * @var EarnRate
     */
    private $object;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->elementFactoryMock = $this->getMockBuilder(ElementFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->elementMock = $this->getMockBuilder(AbstractElement::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'setForm',
                'setName',
                'setHtmlId',
                'setValues',
                'getName',
                'getHtmlId',
                'getValues',
                'getElementHtml',
                'getValue',
            ])
            ->getMock();

        $this->systemStoreMock = $this->getMockBuilder(Store::class)
            ->setMethods(['getWebsiteValuesForForm'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerGroupMock = $this->getMockBuilder(CustomerGroup::class)
            ->setMethods(['toOptionArray'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->jsonHelperDataMock = $this->getMockBuilder(JsonHelperData::class)
            ->setMethods(['jsonEncode'])
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'elementFactory' => $this->elementFactoryMock,
            'systemStore' => $this->systemStoreMock,
            'customerGroup' => $this->customerGroupMock,
            'jsonHelperData' => $this->jsonHelperDataMock,
            'data' => [
                'element' => $this->elementMock
            ],
        ];

        $this->object = $objectManager->getObject(EarnRate::class, $data);
    }

    /**
     * Test init of object
     */
    public function testConstructMethod()
    {
        $class = new \ReflectionClass(EarnRate::class);
        $method = $class->getMethod('_construct');
        $method->setAccessible(true);

        $method->invoke($this->object);

        $this->assertEquals(5, count($this->object->getColumns()));
        $this->assertEquals('earn_rate', $this->object->getHtmlId());
    }

    /**
     * Test template property
     */
    public function testTemplateProperty()
    {
        $class = new \ReflectionClass(EarnRate::class);
        $property = $class->getProperty('_template');
        $property->setAccessible(true);

        $this->assertEquals('Aheadworks_RewardPoints::form/field/rates.phtml', $property->getValue($this->object));
    }

    /**
     * Test renderCellTemplate method for website_id column
     */
    public function testRenderCellTemplateWebsiteIdColumn()
    {
        $columnName = 'website_id';
        $expectedResult = 'testWebsiteIdSelectHtml';

        $this->elementFactoryMock->expects($this->once())->method('create')->willReturn($this->elementMock);
        $this->elementMock->expects($this->once())->method('setForm')->willReturnSelf();
        $this->elementMock->expects($this->once())->method('setName')->willReturnSelf();
        $this->elementMock->expects($this->once())->method('setHtmlId')->willReturnSelf();
        $this->elementMock->expects($this->once())->method('setValues')->willReturnSelf();
        $this->elementMock->expects($this->once())->method('getElementHtml')->willReturn($expectedResult);

        $this->systemStoreMock->expects($this->once())->method('getWebsiteValuesForForm')->willReturn([]);

        $this->object->addColumn(
            $columnName,
            $this->cellParameters
        );

        $this->assertEquals(
            $expectedResult,
            $this->object->renderCellTemplate($columnName)
        );
    }

    /**
     * Test renderCellTemplate method for customer_group_id column
     */
    public function testRenderCellTemplateCustomerGroupIdColumn()
    {
        $columnName = 'customer_group_id';
        $expectedResult = 'testCustomerGroupIdSelectHtml';

        $this->elementFactoryMock->expects($this->once())->method('create')->willReturn($this->elementMock);
        $this->elementMock->expects($this->once())->method('setForm')->willReturnSelf();
        $this->elementMock->expects($this->once())->method('setName')->willReturnSelf();
        $this->elementMock->expects($this->once())->method('setHtmlId')->willReturnSelf();
        $this->elementMock->expects($this->once())->method('setValues')->willReturnSelf();
        $this->elementMock->expects($this->once())->method('getElementHtml')->willReturn($expectedResult);

        $this->customerGroupMock->expects($this->once())->method('toOptionArray')->willReturn([]);

        $this->object->addColumn(
            $columnName,
            $this->cellParameters
        );

        $this->assertEquals(
            $expectedResult,
            $this->object->renderCellTemplate($columnName)
        );
    }

    /**
     * Test renderCellTemplate method for other columns
     */
    public function testRenderCellTemplateOtherColumn()
    {
        $columnName = 'testCellName';

        $this->object->addColumn(
            $columnName,
            $this->cellParameters
        );

        $actual = $this->object->renderCellTemplate($columnName);
        foreach ($this->cellParameters as $parameter) {
            $this->assertContains($parameter, $actual, 'Parameter \'' . $parameter . '\' missing in render output.');
        }
    }

    /**
     * Test renderCellTemplate method for wrong column name
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Wrong column name specified.
     */
    public function testRenderCellTemplateWrongColumnName()
    {
        $columnName = 'testCellName';
        $wrongColumnName = 'wrongTestCellName';

        $this->object->addColumn($wrongColumnName, $this->cellParameters);

        $this->object->renderCellTemplate($columnName);
    }

    /**
     * Test getDefaultValueJson method
     */
    public function testGetDefaultValueJsonMethod()
    {
        $paramsForJsonHelper = [
            'website_id' => '',
            'customer_group_id' => '',
            'lifetime_sales_amount' => '',
            'base_amount' => '',
            'points' => '',
            'option_extra_attrs' => []
        ];
        $expectedValue = json_encode($paramsForJsonHelper);

        $this->jsonHelperDataMock->expects($this->once())
            ->method('jsonEncode')
            ->with($paramsForJsonHelper)
            ->willReturn($expectedValue);

        $this->assertJsonStringEqualsJsonString($expectedValue, $this->object->getDefaultValueJson());
    }

    /**
     * Test getTemplateValueJson method
     */
    public function testGetTemplateValueJsonMethod()
    {
        $elementValueArray = [
            'website_id' => 1,
            'customer_group_id' => 4,
            'lifetime_sales_amount' => 1000,
            'base_amount' => 10,
            'points' => 2,
        ];

        $elementValue = [];
        $elementValue[5] = $elementValueArray;

        $expectedValueArray = [
            5 => [
                'website_id' => 1,
                'customer_group_id' => 4,
                'lifetime_sales_amount' => 1000,
                'base_amount' => 10,
                'points' => 2,
                '_id' => 5,
                'column_values' => [
                    '5_website_id_earn_rate' => 1,
                    '5_customer_group_id_earn_rate' => 4,
                    '5_lifetime_sales_amount_earn_rate' => 1000,
                    '5_base_amount_earn_rate' => 10,
                    '5_points_earn_rate' => 2,
                ]
            ],
        ];

        $expectedValue = json_encode($elementValueArray);

        $this->elementMock->expects($this->exactly(3))
            ->method('getValue')
            ->willReturn($elementValue);

        $this->jsonHelperDataMock->expects($this->once())
            ->method('jsonEncode')
            ->with($expectedValueArray)
            ->willReturn($expectedValue);

        $this->assertJsonStringEqualsJsonString($expectedValue, $this->object->getTemplateValueJson());
    }
}
