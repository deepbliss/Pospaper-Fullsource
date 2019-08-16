<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Test\Unit\Controller\Adminhtml\Notification;

use Amasty\Base\Controller\Adminhtml\Notification\Frequency;
use Amasty\Base\Test\Unit\Traits;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class FrequencyTest
 *
 * @see Frequency
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @codingStandardsIgnoreFile
 */
class UnsubscribeTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    /**
     * @covers Frequency::execute
     * @dataProvider executeDataProvider
     */
    public function testExecute($action, $callError, $callIncrease, $callDecrease)
    {
        $messageManager = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);
        $redirect = $this->createMock(\Magento\Framework\App\Response\RedirectInterface::class);
        $resultRedirectFactory = $this->createPartialMock(
            \Magento\Framework\Controller\Result\RedirectFactory::class,
            ['create', 'setUrl']
        );
        $controller = $this->createPartialMock(
            Frequency::class,
            ['increaseFrequency', 'decreaseFrequency', 'getRequest', 'getParam']
        );

        $messageManager->expects($callError)->method('addErrorMessage');
        $controller->expects($callDecrease)->method('decreaseFrequency');
        $controller->expects($callIncrease)->method('increaseFrequency');
        $controller->expects($this->any())->method('getRequest')->willReturn($controller);
        $controller->expects($this->any())->method('getParam')->willReturn($action);
        $resultRedirectFactory->expects($this->any())->method('create')->willReturn($resultRedirectFactory);

        $this->setProperty($controller, 'messageManager' , $messageManager);
        $this->setProperty($controller, 'resultRedirectFactory' , $resultRedirectFactory);
        $this->setProperty($controller, '_redirect' , $redirect);
        $controller->execute();
    }

    /**
     * Data provider for execute test
     * @return array
     */
    public function executeDataProvider()
    {
        return [
            ['less', $this->never(), $this->once(), $this->never()],
            ['more', $this->never(), $this->never(), $this->once()],
            ['test', $this->once(), $this->never(), $this->never()],
        ];
    }

    /**
     * @covers Frequency::decreaseFrequency
     */
    public function testDecreaseFrequency()
    {
        $controller = $this->createPartialMock(Frequency::class, ['getCurrentValue', 'changeFrequency']);
        $messageManager = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);
        $frequency = $this->getObjectManager()->getObject(\Amasty\Base\Model\Source\Frequency::class);

        $controller->expects($this->any())->method('getCurrentValue')->willReturn(15);
        $controller->expects($this->once())->method('changeFrequency');

        $this->setProperty($controller, 'frequency' , $frequency, Frequency::class);
        $this->setProperty($controller, 'messageManager' , $messageManager);

        $this->invokeMethod($controller, 'decreaseFrequency');
    }

    /**
     * @covers Frequency::increaseFrequency
     */
    public function testIncreaseFrequency()
    {
        $controller = $this->createPartialMock(Frequency::class, ['getCurrentValue', 'changeFrequency']);
        $messageManager = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);
        $frequency = $this->getObjectManager()->getObject(\Amasty\Base\Model\Source\Frequency::class);

        $controller->expects($this->any())->method('getCurrentValue')->willReturn(15);
        $controller->expects($this->once())->method('changeFrequency');

        $this->setProperty($controller, 'frequency' , $frequency, Frequency::class);
        $this->setProperty($controller, 'messageManager' , $messageManager);

        $this->invokeMethod($controller, 'increaseFrequency');
    }
}
