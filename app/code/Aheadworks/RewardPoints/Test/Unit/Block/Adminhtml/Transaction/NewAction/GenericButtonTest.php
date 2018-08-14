<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Block\Adminhtml\Transaction\NewAction;

use Aheadworks\RewardPoints\Block\Adminhtml\Transaction\NewAction\GenericButton;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Block\Adminhtml\Transaction\NewAction\GenericButtonTest
 */
class GenericButtonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Context
     */
    private $contextMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|UrlInterface
     */
    private $urlBuilderMock;

    /**
     * @var GenericButton
     */
    private $object;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->prepareContext();

        $this->urlBuilderMock = $this->getMockBuilder(UrlInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUrl'])
            ->getMockForAbstractClass();

        $data = [
            'context' => $this->contextMock,
        ];

        $this->object = $objectManager->getObject(GenericButton::class, $data);
    }

    /**
     * Test getUrl method
     *
     * @dataProvider dataProviderGetUrl
     * @param string $route
     * @param array $params
     * @param string $expects
     */
    public function testGetUrlMethod($route, $params, $expects)
    {
        $this->contextMock->expects($this->once())
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilderMock);

        $this->urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with($route, $params)
            ->willReturn($expects);

        $this->assertEquals($expects, $this->object->getUrl($route, $params));
    }

    /**
     * Data provider for testGetUrlMethod
     *
     * @return array
     */
    public function dataProviderGetUrl()
    {
        return [
            [
                'sales/order/info',
                ['order_id' => 11, 'store' => 1],
                'sales/order/info/order_id/11/store/1'
            ]
        ];
    }

    /**
     * Prepare context mock
     */
    private function prepareContext()
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getUrlBuilder'
                ]
            )
            ->getMock();
    }
}
