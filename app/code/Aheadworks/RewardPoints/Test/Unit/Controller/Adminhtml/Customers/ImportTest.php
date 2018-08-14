<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Controller\Adminhtml\Customers;

use Magento\Backend\App\Action\Context;
use Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Import;
use Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Upload;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Json as ResultJson;
use Magento\Framework\File\Csv;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Aheadworks\RewardPoints\Model\Import\PointsSummary as ImportPointsSummary;
use Magento\Framework\App\RequestInterface;

/**
 * Test for \Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Import
 */
class ImportTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Import
     */
    private $controller;

    /**
     * @var ResultFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resultFactoryMock;

    /**
     * @var Csv|\PHPUnit_Framework_MockObject_MockObject
     */
    private $csvProcessorMock;

    /**
     * @var CustomerRewardPointsManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customerRewardPointsServiceMock;

    /**
     * @var ImportPointsSummary|\PHPUnit_Framework_MockObject_MockObject
     */
    private $importPointsSummaryMock;

    /**
     * @var RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

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
        $this->requestMock = $this->getMockBuilder(RequestInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPostValue'])
            ->getMockForAbstractClass();
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'resultFactory' => $this->resultFactoryMock,
                '_request' => $this->requestMock,
            ]
        );

        $this->csvProcessorMock = $this->getMockBuilder(Csv::class)
            ->setMethods(['create', 'getData'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerRewardPointsServiceMock = $this->getMockBuilder(CustomerRewardPointsManagementInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['importPointsSummary'])
            ->getMockForAbstractClass();
        $this->importPointsSummaryMock = $this->getMockBuilder(ImportPointsSummary::class)
            ->setMethods(['create', 'getUrlToLogFile'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = $objectManager->getObject(
            Import::class,
            [
                'context' => $contextMock,
                'csvProcessor' => $this->csvProcessorMock,
                'customerRewardPointsService' => $this->customerRewardPointsServiceMock,
                'importPointsSummary' => $this->importPointsSummaryMock,
            ]
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $fullPathToFile = '/var/www/aheadworks/ecommerce/pub/media/aw_rewardpoints/imports/1.csv';
        $data = [
            'form_key' => 'mJbSc7L1GTOZutF8',
            Upload::FILE_ID => [
                [
                    'name' => '1.csv',
                    'size' => 264,
                    'path' => '/var/www/aheadworks/ecommerce/pub/media/aw_rewardpoints/imports',
                    'file' => '1.csv',
                    'url' => 'https://ecommerce.aheadworks.com/pub/media/aw_rewardpoints/imports/1.csv',
                    'full_path' => $fullPathToFile,
                ],
            ],
        ];

        $importRawData = [
            [
                'Customer Email',
                'Current customer balance',
                'Website',
                'Balance Update Notifications (status)',
                'Points Expiration Notification (status)',
            ],
            [
                'awtest201504@gmail.com',
                0,
                'Main Website',
                'Unsubscribed',
                'Subscribed'
            ],
            [
                'awtest201509@gmail.com',
                240,
                'Main Website',
                'Subscribed',
                'Unsubscribed'
            ]
        ];
        $importedRecords = ['1', '2'];
        $urlToLogFile = 'https://ecommerce.aheadworks.com/' . 'var/log/aw_rp_points_summary_import.log';

        $result = [
            'messages' =>
                'Import successfully completed! 2 of 2 records have been imported. See details in log file: ' .
                $urlToLogFile,
        ];

        $this->requestMock->expects($this->once())
            ->method('getPostValue')
            ->willReturn($data);

        $this->csvProcessorMock
            ->expects($this->once())
            ->method('getData')
            ->with($fullPathToFile)
            ->willReturn($importRawData);

        $this->customerRewardPointsServiceMock->expects($this->once())
            ->method('importPointsSummary')
            ->with($importRawData)
            ->willReturn($importedRecords);

        $this->importPointsSummaryMock->expects($this->once())
            ->method('getUrlToLogFile')
            ->willReturn($urlToLogFile);

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
