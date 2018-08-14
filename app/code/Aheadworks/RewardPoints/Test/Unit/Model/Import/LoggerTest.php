<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model\Import;

use Aheadworks\RewardPoints\Model\Import\Logger;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Zend\Log\Logger as ZendLogger;

/**
 * Class LoggerTest
 * Test for \Aheadworks\RewardPoints\Model\Import\Logger
 */
class LoggerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Logger|\PHPUnit_Framework_MockObject_MockObject
     */
    private $model;

    /**
     * @var ZendLogger|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loggerMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->loggerMock = $this->getMockBuilder(Logger::class)
            ->setMethods(['info'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->model = $objectManager->getObject(
            Logger::class,
            [
                'logger' => $this->loggerMock,
            ]
        );
    }

    /**
     * Testing of addMessage method
     */
    public function testAddMessage()
    {
        $message = __('Message');

        $this->loggerMock->expects($this->once())
            ->method('info')
            ->with($message)
            ->willReturnSelf();

        $this->model->addMessage($message);
    }
}
