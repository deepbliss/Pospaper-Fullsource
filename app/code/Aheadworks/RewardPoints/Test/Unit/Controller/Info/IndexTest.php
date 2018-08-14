<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Controller\Info\Index;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Controller\ResultFactory;
use Aheadworks\RewardPoints\Controller\Info\Index;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Result;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Page\Title;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Controller\Info\Index$IndexTest
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class IndexTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Cms\Controller\Index\Index
     */
    private $object;

    /**
     * @var \Magento\Backend\App\Action\Context
     */
    private $context;

    /**
     * @var \Magento\Framework\App\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * @var \Magento\Backend\Model\Session|\PHPUnit_Framework_MockObject_MockObject
     */
    private $sessionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Page
     */
    private $resultPageMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ResultFactory
     */
    private $resultFactoryMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(RequestHttp::class)
            ->disableOriginalConstructor()->getMock();

        $this->resultFactoryMock = $this->getMockBuilder(ResultFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->resultPageMock = $this->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getConfig',
                    'getLayout'
                ]
            )
            ->getMock();

        $this->context = $objectManager->getObject(
            Context::class,
            [
                'request' => $this->requestMock,
                'session' => $this->sessionMock,
                'resultFactory' => $this->resultFactoryMock,
            ]
        );

        $this->object = $objectManager->getObject(
            Index::class,
            [
                'context' => $this->context,
                'session' => $this->sessionMock
            ]
        );
    }

    /**
     * @covers Aheadworks\RewardPoints\Controller\Info\Index::execute
     */
    public function testExecute()
    {
        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultPageMock);

        $layoutMock = $this->getMockBuilder(Result::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBlock'])
            ->getMock();

        $blockMock = $this->getMockBuilder(AbstractBlock::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'setActive',
                    'setRefererUrl'
                ]
            )
            ->getMock();

        $layoutMock->expects($this->exactly(2))
            ->method('getBlock')
            ->willReturn($blockMock);

        $configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTitle'])
            ->getMock();

        $this->resultPageMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($configMock);

        $titleMock = $this->getMockBuilder(Title::class)
            ->disableOriginalConstructor()
            ->setMethods(['set'])
            ->getMock();

        $configMock->expects($this->once())
            ->method('getTitle')
            ->willReturn($titleMock);

        $this->resultPageMock->expects($this->exactly(2))
            ->method('getLayout')
            ->willReturn($layoutMock);

        $this->assertInstanceOf(
            Page::class,
            $this->object->execute()
        );
    }
}
