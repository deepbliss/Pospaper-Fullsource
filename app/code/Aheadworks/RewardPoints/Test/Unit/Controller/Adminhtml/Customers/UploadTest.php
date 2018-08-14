<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Controller\Adminhtml\Customers;

use Magento\Backend\App\Action\Context;
use Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Upload;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Json as ResultJson;
use Aheadworks\RewardPoints\Model\FileUploader;

/**
 * Test for \Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Upload
 */
class UploadTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Upload
     */
    private $controller;

    /**
     * @var FileUploader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $fileUploaderMock;

    /**
     * @var ResultFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resultFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->resultFactoryMock = $this->getMockBuilder(ResultFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->fileUploaderMock = $this->getMockBuilder(FileUploader::class)
            ->setMethods(['saveToTmpFolder'])
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'resultFactory' => $this->resultFactoryMock
            ]
        );

        $this->controller = $objectManager->getObject(
            Upload::class,
            [
                'context' => $contextMock,
                'fileUploader' => $this->fileUploaderMock
            ]
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $result = [
            'name' => '1.csv',
            'size' => 264,
            'path' => '/var/www/aheadworks/ecommerce/pub/media/aw_rewardpoints/imports',
            'file' => '1.csv',
            'url' => 'https://ecommerce.aheadworks.com/pub/media/aw_rewardpoints/imports/1.csv',
            'full_path' => '/var/www/aheadworks/ecommerce/pub/media/aw_rewardpoints/imports/1.csv',
        ];

        $this->fileUploaderMock->expects($this->once())
            ->method('saveToTmpFolder')
            ->with(Upload::FILE_ID)
            ->willReturn($result);
        $resultJsonMock = $this->getMockBuilder(ResultJson::class)
            ->setMethods(['setData'])
            ->disableOriginalConstructor()
            ->getMock();
        $resultJsonMock->expects($this->once())
            ->method('setData')
            ->with($result)
            ->willReturnSelf();
        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->with(ResultFactory::TYPE_JSON)
            ->willReturn($resultJsonMock);

        $this->assertSame($resultJsonMock, $this->controller->execute());
    }
}
