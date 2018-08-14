<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Test\Unit\Controller\Label;

use Aheadworks\Freeshippinglabel\Controller\Label\Render;
use Aheadworks\Freeshippinglabel\Api\LabelRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect as ResultRedirect;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Freeshippinglabel\Model\Label as LabelModel;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Freeshippinglabel\Controller\Label\Render
 */
class RenderTest extends TestCase
{
    /**
     * @var Render
     */
    private $controller;

    /**
     * @var LabelRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $labelRepositoryMock;

    /**
     * @var RedirectFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resultRedirectFactoryMock;

    /**
     * @var RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * @var ResponseInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $responseMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->labelRepositoryMock = $this->getMockBuilder(LabelRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->resultRedirectFactoryMock = $this->createPartialMock(RedirectFactory::class, ['create']);
        $this->requestMock = $this->getMockBuilder(RequestInterface::class)
            ->setMethods(['isAjax'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->responseMock = $this->getMockBuilder(ResponseInterface::class)
            ->setMethods(['appendBody'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'resultRedirectFactory' => $this->resultRedirectFactoryMock,
                'request' => $this->requestMock,
                'response' => $this->responseMock
            ]
        );
        $this->controller = $objectManager->getObject(
            Render::class,
            [
                'labelRepository' => $this->labelRepositoryMock,
                'context' => $contextMock
            ]
        );
    }

    /**
     * Testing of execute method for normal case
     */
    public function testNormalExecute()
    {
        $message = 'message';
        $this->requestMock->expects($this->once())
            ->method('isAjax')
            ->willReturn(true);
        $labelMock = $this->createPartialMock(LabelModel::class, ['getMessage']);
        $this->labelRepositoryMock->expects($this->once())
            ->method('get')
            ->willReturn($labelMock);
        $labelMock->expects($this->once())
            ->method('getMessage')
            ->willReturn($message);
        $this->responseMock->expects($this->once())
            ->method('appendBody')
            ->with(json_encode(['labelContent' => $message]));
        $this->controller->execute();
    }

    /**
     * Testing of execute method for failure case
     */
    public function testFailureExecute()
    {
        $this->requestMock->expects($this->once())
            ->method('isAjax')
            ->willReturn(false);
        $resultRedirectMock = $this->createPartialMock(Redirect::class, ['setRefererOrBaseUrl']);
        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultRedirectMock);
        $resultRedirectMock->expects($this->once())
            ->method('setRefererOrBaseUrl')
            ->willReturnSelf();
        $this->assertEquals($resultRedirectMock, $this->controller->execute());
    }
}
