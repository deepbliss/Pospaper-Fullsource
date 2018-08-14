<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Test\Unit\Model;

use Aheadworks\Freeshippinglabel\Model\Label;
use Aheadworks\Freeshippinglabel\Model\Source\ContentType;
use Magento\Checkout\Model\Session;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Freeshippinglabel\Model\ResourceModel\Label as LabelResource;
use Magento\Quote\Model\Quote;
use Magento\Store\Api\StoreResolverInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Freeshippinglabel\Model\Label
 */
class LabelTest extends TestCase
{
    /**
     * @var Label
     */
    private $model;

    /**
     * @var LabelResource|\PHPUnit_Framework_MockObject_MockObject
     */
    private $labelResourceMock;

    /**
     * @var Session|\PHPUnit_Framework_MockObject_MockObject
     */
    private $checkoutSessionMock;

    /**
     * @var PriceCurrencyInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $priceCurrencyMock;

    /**
     * @var StoreResolverInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeResolverMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $this->labelResourceMock = $this->getMockBuilder(LabelResource::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIdFieldName', 'getMessageTemplate'])
            ->getMock();
        $this->checkoutSessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['getQuote'])
            ->getMock();
        $this->priceCurrencyMock = $this->getMockBuilder(PriceCurrencyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeResolverMock = $this->getMockBuilder(StoreResolverInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCurrentStoreId'])
            ->getMock();
        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject(
            Label::class,
            [
                'resource' => $this->labelResourceMock,
                'checkoutSession' => $this->checkoutSessionMock,
                'storeResolver' => $this->storeResolverMock,
                'priceCurrency' => $this->priceCurrencyMock
            ]
        );
        $this->model->setId(1);
    }

    /**
     * Testing of getMessage method
     *
     * @param int $goal
     * @param int $grandTotal
     * @param string $messageType
     * @param string $messageTmpl
     * @param string $message
     *
     * @dataProvider getMessageDataProvider
     */
    public function testGetMessage($goal, $grandTotal, $messageType, $messageTmpl, $message)
    {
        $storeId = 1;
        $currencySymbol = '$';

        $this->model->setGoal($goal);
        $quoteMock = $this->getMockBuilder(Quote::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItemsCount', 'getGrandTotal'])
            ->getMock();
        $this->checkoutSessionMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($grandTotal > 0 ? $quoteMock : null);
        if ($grandTotal > 0) {
            $quoteMock->expects($this->once())
                ->method('getItemsCount')
                ->willReturn(1);
            $quoteMock->expects($this->any())
                ->method('getGrandTotal')
                ->willReturn($grandTotal);
        }
        $this->priceCurrencyMock
            ->expects($this->once())
            ->method('convertAndRound')
            ->willReturn($goal);
        $this->priceCurrencyMock
            ->expects($this->once())
            ->method('getCurrencySymbol')
            ->willReturn($currencySymbol);
        $this->storeResolverMock
            ->expects($this->once())
            ->method('getCurrentStoreId')
            ->willReturn($storeId);
        $this->labelResourceMock
            ->expects($this->once())
            ->method('getMessageTemplate')
            ->with(1, $messageType, $storeId)
            ->willReturn($messageTmpl);

        $this->assertEquals($message, $this->model->getMessage());
    }

    /**
     * @return array
     */
    public function getMessageDataProvider()
    {
        return [
            [
                100,
                0,
                ContentType::EMPTY_CART,
                'Free shipping on orders over {{ruleGoal}}',
                'Free shipping on orders over <span class="goal">$100</span>'
            ],
            [
                100,
                70,
                ContentType::NOT_EMPTY_CART,
                '{{ruleGoalLeft}} left for free shipping',
                '<span class="goal">$30</span> left for free shipping'
            ],
            [
                100,
                101,
                ContentType::GOAL_REACHED,
                'Great! your order will be delivered for free!',
                'Great! your order will be delivered for free!'
            ],
        ];
    }
}
