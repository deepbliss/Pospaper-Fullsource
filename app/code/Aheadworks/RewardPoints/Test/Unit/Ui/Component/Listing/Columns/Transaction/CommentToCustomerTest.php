<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Ui\Component\Listing\Columns\Transaction;

use Aheadworks\RewardPoints\Model\Source\Transaction\EntityType;
use Aheadworks\RewardPoints\Model\Source\Transaction\Type;
use Aheadworks\RewardPoints\Ui\Component\Listing\Columns\Transaction\CommentToCustomer;
use Aheadworks\RewardPoints\Model\Comment\CommentPoolInterface;
use Aheadworks\RewardPoints\Model\Comment\CommentInterface;
use Magento\Framework\View\Element\UiComponent\Processor;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Ui\Component\Listing\Columns\Transaction\CommentToCustomerTest
 */
class CommentToCustomerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CommentToCustomer
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ContextInterface
     */
    private $contextMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CommentPoolInterface
     */
    private $commentPoolMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CommentInterface
     */
    private $commentMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->commentPoolMock = $this->getMockBuilder(CommentPoolInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'get',
                ]
            )
            ->getMockForAbstractClass();

        $this->commentMock = $this->getMockBuilder(CommentInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'renderComment',
                ]
            )
            ->getMockForAbstractClass();

        $this->contextMock = $this->getMockBuilder(ContextInterface::class)
            ->setMethods(
                [
                    'getProcessor',
                ]
            )
            ->getMockForAbstractClass();

        $processor = $this->getMockBuilder(Processor::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->contextMock->expects($this->any())
            ->method('getProcessor')
            ->willReturn($processor);

        $data = [
            'context' => $this->contextMock,
            'commentPool' => $this->commentPoolMock,
        ];

        $this->object = $objectManager->getObject(CommentToCustomer::class, $data);
    }

    /**
     * Test prepareDataSource with custom array
     */
    public function testPrepareDataSourceMethodTestArray()
    {
        $dataSource1 = ['test1' => 1, 'test2' => 2];
        $this->assertEquals($dataSource1, $this->object->prepareDataSource($dataSource1));

        $dataSource2 = [];
        $this->assertEquals($dataSource2, $this->object->prepareDataSource($dataSource2));
    }

    /**
     * Test prepareDataSource method
     *
     * @dataProvider prepareDataSourceDataProvider
     *
     * @param array $dataSource
     * @param string $expected
     * @param int $type
     */
    public function testPrepareDataSourceMethod(
        $dataSource,
        $expected,
        $type
    ) {
        $this->commentPoolMock->expects($this->once())
            ->method('get')
            ->with($type)
            ->willReturn($this->commentMock);

        $this->commentMock->expects($this->once())
            ->method('renderComment')
            ->willReturn($expected);

        $this->assertTrue(is_array($this->object->prepareDataSource($dataSource)));
    }

    /**
     * Data provider for testPrepareDataSourceMethod test
     */
    public function prepareDataSourceDataProvider()
    {
        return [
            [
                [
                    'data' => [
                        'items' => [
                            [
                                'comment_to_customer_placeholder' => 'Spent reward points on order %order_id',
                                'type' => Type::POINTS_REWARDED_FOR_ORDER,
                                'entities' => [
                                    EntityType::ORDER_ID => [
                                        'entity_id' => 1,
                                        'entity_label' => '000000001'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'Spent reward points on order #000000001',
                Type::POINTS_REWARDED_FOR_ORDER
            ],
            [
                [
                    'data' => [
                        'items' => [
                            [
                                'comment_to_customer_placeholder' => null,
                                'type' => Type::BALANCE_ADJUSTED_BY_ADMIN,
                                'entities' => []
                            ]
                        ]
                    ]
                ],
                'comment',
                Type::BALANCE_ADJUSTED_BY_ADMIN
            ]
        ];
    }
}
