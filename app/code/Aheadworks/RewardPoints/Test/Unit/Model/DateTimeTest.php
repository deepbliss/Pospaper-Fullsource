<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model;

use Aheadworks\RewardPoints\Model\DateTime as AwDateTime;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\DateTimeTest
 */
class DateTimeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var AwDateTime
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TimezoneInterface
     */
    private $timezoneMock;

    /**
     * @var \DateTime
     */
    private $dateTimeMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->timezoneMock = $this->getMockBuilder(TimezoneInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['date'])
            ->getMockForAbstractClass();

        $this->dateTimeMock = $this->getMockBuilder(\DateTime::class)
            ->disableOriginalConstructor()
            ->setMethods(['format'])
            ->getMock();

        $data = [
            'timezone' => $this->timezoneMock,
        ];
        $this->object = $objectManager->getObject(AwDateTime::class, $data);
    }

    /**
     * Test isTodayDate for null arguments
     */
    public function testIsTodayDateMethodForNullArguments()
    {
        $this->assertFalse($this->object->isTodayDate(null));
        $this->assertFalse($this->object->isTodayDate(false));
        $this->assertFalse($this->object->isTodayDate(''));
        $this->assertFalse($this->object->isTodayDate(0));
        $this->assertFalse($this->object->isTodayDate(0.00));
    }

    /**
     * Test isTodayDate method
     *
     * @dataProvider dataProviderIsTodayTest
     * @param unknown $dateNow
     * @param unknown $date
     * @param unknown $expected
     */
    public function testIsTodayDateMethod($dateNow, $date, $expected)
    {
        $dateMock1 =  $this->getMockBuilder(\DateTime::class)
            ->disableOriginalConstructor()
            ->setMethods(['format'])
            ->getMock();

        $dateMock1->expects($this->once())
            ->method('format')
            ->with(StdlibDateTime::DATE_PHP_FORMAT)
            ->willReturn($dateNow);

        $dateMock2 =  $this->getMockBuilder(\DateTime::class)
            ->disableOriginalConstructor()
            ->setMethods(['format'])
            ->getMock();

        $dateMock2->expects($this->once())
            ->method('format')
            ->with(StdlibDateTime::DATE_PHP_FORMAT)
            ->willReturn($date);

        $this->timezoneMock->expects($this->exactly(2))
            ->method('date')
            ->withConsecutive(
                [null],
                [strtotime($date)]
            )
            ->willReturnOnConsecutiveCalls(
                $dateMock1,
                $dateMock2
            );

        $this->assertEquals($expected, $this->object->isTodayDate($date));
    }

    /**
     * Test getTodayDate with time false
     */
    public function testgetTodayDateMethodWithTimeFalse()
    {
        $dateNow = '2016-10-27';

        $dateMock =  $this->getMockBuilder(\DateTime::class)
            ->disableOriginalConstructor()
            ->setMethods(['format'])
            ->getMock();

        $dateMock->expects($this->once())
            ->method('format')
            ->with(StdlibDateTime::DATE_PHP_FORMAT)
            ->willReturn($dateNow);

        $this->timezoneMock->expects($this->once())
            ->method('date')
            ->with(null)
            ->willReturn($dateMock);

        $this->assertEquals($dateNow, $this->object->getTodayDate(false));
    }

    /**
     * Test getTodayDate with time true
     */
    public function testgetTodayDateMethodWithTimeTrue()
    {
        $dateNow = '2016-10-27 11:27:56 AM';

        $dateMock =  $this->getMockBuilder(\DateTime::class)
            ->disableOriginalConstructor()
            ->setMethods(['format'])
            ->getMock();

        $dateMock->expects($this->once())
            ->method('format')
            ->with(StdlibDateTime::DATETIME_PHP_FORMAT)
            ->willReturn($dateNow);

        $this->timezoneMock->expects($this->once())
            ->method('date')
            ->with(null)
            ->willReturn($dateMock);

        $this->assertEquals($dateNow, $this->object->getTodayDate(true));
    }

    /**
     *  Test getExpirationDate method
     */
    public function testGetExpirationDateMethod()
    {
        $expireInDays = 30;

        $date = '2016-11-27';

        $dateMock =  $this->getMockBuilder(\DateTime::class)
            ->disableOriginalConstructor()
            ->setMethods(['add', 'format'])
            ->getMock();

        $dateMock->expects($this->once())
            ->method('add')
            ->with(new \DateInterval('P' . $expireInDays . 'D'))
            ->willReturnSelf();

        $dateMock->expects($this->once())
            ->method('format')
            ->with('Y-m-d H:i:00')
            ->willReturn($date);

        $this->timezoneMock->expects($this->once())
            ->method('date')
            ->with(null)
            ->willReturn($dateMock);

        $this->assertEquals($date, $this->object->getExpirationDate($expireInDays));
    }

    /**
     * Retirieve data provider for testIsTodayDate test
     *
     * @return array
     */
    public function dataProviderIsTodayTest()
    {
        return [
            ['2016-10-27', '2016-10-27', true],
            ['2016-10-27', '2016-10-28', false],
        ];
    }
}
