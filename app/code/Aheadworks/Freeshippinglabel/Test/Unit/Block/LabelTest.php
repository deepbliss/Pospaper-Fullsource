<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Test\Unit\Block;

use Aheadworks\Freeshippinglabel\Model\Source\PageType;
use Aheadworks\Freeshippinglabel\Model\Source\Position;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Freeshippinglabel\Block\Label;
use Magento\Customer\Model\Session;
use Magento\Framework\Registry;
use Aheadworks\Freeshippinglabel\Model\Label as LabelModel;
use Aheadworks\Freeshippinglabel\Api\LabelRepositoryInterface;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Freeshippinglabel\Block\Label
 */
class LabelTest extends TestCase
{
    /**
     * @var Label
     */
    private $block;

    /**
     * @var LabelModel|\PHPUnit_Framework_MockObject_MockObject
     */
    private $labelModelMock;

    /**
     * @var Session|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customerSessionMock;

    /**
     * @var HttpRequest|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->labelModelMock = $this->createPartialMock(
            LabelModel::class,
            [
                'getIsEnabled',
                'getCustomerGroupIds',
                'getPageType',
                'getPosition',
                'getFontSize',
                'getMessage'
            ]
        );
        $labelRepositoryMock = $this->getMockBuilder(LabelRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->requestMock = $this->createPartialMock(
            HttpRequest::class,
            ['getFullActionName']
        );
        $registryMock = $this->createPartialMock(
            Registry::class,
            ['registry', 'register']
        );
        $this->customerSessionMock = $this->createPartialMock(
            Session::class,
            ['getCustomerGroupId']
        );
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'request' => $this->requestMock,
            ]
        );

        $this->block = $objectManager->getObject(
            Label::class,
            [
                'labelRepository' => $labelRepositoryMock,
                'registry' => $registryMock,
                'customerSession' => $this->customerSessionMock,
                'context' => $contextMock
            ]
        );
        $registryMock
            ->expects($this->once())
            ->method('registry')
            ->with('aw_fslabel_label')
            ->willReturn(null);
        $labelRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->labelModelMock);
        $this->block->getLabelModel();
    }

    /**
     * Testing of isLabelEnabled method
     */
    public function testIsLabelEnabled()
    {
        $customerGroupIds = [1];
        $position = Position::CONTENT_TOP;
        $this->block->setNameInLayout($position);
        $this->labelModelMock
            ->expects($this->once())
            ->method('getIsEnabled')
            ->willReturn(true);
        $this->labelModelMock
            ->expects($this->once())
            ->method('getCustomerGroupIds')
            ->willReturn($customerGroupIds);
        $this->customerSessionMock
            ->expects($this->once())
            ->method('getCustomerGroupId')
            ->willReturn(1);
        $this->labelModelMock
            ->expects($this->once())
            ->method('getPageType')
            ->willReturn(PageType::ALL_PAGES);
        $this->requestMock
            ->expects($this->once())
            ->method('getFullActionName')
            ->willReturn('cms_index_index');
        $this->labelModelMock
            ->expects($this->once())
            ->method('getPosition')
            ->willReturn($position);
        $this->labelModelMock
            ->expects($this->once())
            ->method('getMessage')
            ->willReturn('message');
        $this->assertTrue($this->block->isLabelEnabled());
    }

    /**
     * Testing of getPadding method
     */
    public function testGetPadding()
    {
        $fontSize = 16;
        $minPadding = 15;
        $this->labelModelMock
            ->expects($this->once())
            ->method('getFontSize')
            ->willReturn($fontSize);
        $this->assertEquals($minPadding, $this->block->getPadding());
    }
}
