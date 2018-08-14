<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Controller\Adminhtml\Transactions;

use Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\InlineEdit;
use Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\PostDataProcessor;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Aheadworks\RewardPoints\Api\TransactionRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json as ResultJson;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Controller\Adminhtml\Transactions\Index\InlineEditTest
 */
class InlineEditTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var InlineEdit
     */
    private $object;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RequestHttp
     */
    private $requestMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|PostDataProcessor
     */
    private $dataProcessorMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TransactionRepositoryInterface
     */
    private $transactionRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TransactionInterface
     */
    private $transactionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JsonFactory
     */
    private $jsonFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ResultJson
     */
    private $resultJsonMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|DataObjectHelper
     */
    private $dataObjectHelperMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->requestMock = $this->getMockBuilder(RequestHttp::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataProcessorMock = $this->getMockBuilder(PostDataProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->transactionRepositoryMock = $this->getMockBuilder(TransactionRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->transactionMock = $this->getMockBuilder(TransactionInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getData',
                    'setData',
                ]
            )
            ->getMockForAbstractClass();

        $this->jsonFactoryMock = $this->getMockBuilder(JsonFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->resultJsonMock = $this->getMockBuilder(ResultJson::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData'])
            ->getMock();

        $this->dataObjectHelperMock = $this->getMockBuilder(DataObjectHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(['populateWithArray'])
            ->getMock();

        $this->context = $objectManager->getObject(
            Context::class,
            [
                'request' => $this->requestMock,
            ]
        );

        $data = [
            'context' => $this->context,
            'dataProcessor' => $this->dataProcessorMock,
            'transactionRepository' => $this->transactionRepositoryMock,
            'jsonFactory' => $this->jsonFactoryMock,
            'dataObjectHelper' => $this->dataObjectHelperMock,
        ];

        $this->object = $objectManager->getObject(InlineEdit::class, $data);
    }

    /**
     * Test execute method if not isAjax params
     */
    public function testExecuteMethodIsNotAjax()
    {
        $this->jsonFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultJsonMock);

        $this->requestMock->expects($this->exactly(2))
            ->method('getParam')
            ->withConsecutive(
                ['items', []],
                ['isAjax']
            )
            ->willReturnOnConsecutiveCalls([], null);

        $this->resultJsonMock->expects($this->once())
            ->method('setData')
            ->with(['messages' => ['Please correct the data sent.'], 'error' => true])
            ->willReturnSelf();

        $this->assertSame($this->resultJsonMock, $this->object->execute());
    }

    /**
     * Test execute method
     */
    public function testExecuteMethod()
    {
        $items = [
            5 => ['expiration_date' => '09/15/2016'],
        ];
        $balance = 10;

        $this->jsonFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultJsonMock);

        $this->requestMock->expects($this->exactly(2))
            ->method('getParam')
            ->withConsecutive(
                ['items', []],
                ['isAjax']
            )
            ->willReturnOnConsecutiveCalls($items, true);

        $this->transactionRepositoryMock->expects($this->once())
            ->method('getById')
            ->with(5)
            ->willReturn($this->transactionMock);

        $this->dataProcessorMock->expects($this->once())
            ->method('filter')
            ->with(['expiration_date' => '09/15/2016', 'balance' => $balance])
            ->willReturn(['expiration_date' => '2016-09-15']);
        $this->dataProcessorMock->expects($this->once())
            ->method('validate')
            ->with(['expiration_date' => '2016-09-15'])
            ->willReturn(true);
        $this->dataProcessorMock->expects($this->once())
            ->method('validateRequireEntry')
            ->with(['expiration_date' => '2016-09-15'])
            ->willReturn(true);

        $this->dataObjectHelperMock->expects($this->once())
            ->method('populateWithArray')
            ->with(
                $this->transactionMock,
                ['expiration_date' => '2016-09-15'],
                TransactionInterface::class
            )
            ->willReturnSelf();

        $this->transactionMock->expects($this->once())
            ->method('getBalance')
            ->willReturn($balance);
        $this->transactionRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->transactionMock)
            ->willReturnSelf();

        $this->resultJsonMock->expects($this->once())
            ->method('setData')
            ->with(['messages' => [], 'error' => false])
            ->willReturnSelf();

        $this->assertSame($this->resultJsonMock, $this->object->execute());
    }
}
