<?php

namespace MageModule\Core\Test\Unit\Model\Data\Sanitizer;

class DateTimeTest extends \Magento\Framework\TestFramework\Unit\BaseTestCase
{
    /**
     * @var \MageModule\Core\Model\Data\Sanitizer\DateTime
     */
    private $sanitizer;

    public function setUp()
    {
        parent::setUp();

        $this->sanitizer = $this->objectManager->getObject(
            \MageModule\Core\Model\Data\Sanitizer\DateTime::class,
            ['dateTime' => $this->objectManager->getObject(\Magento\Framework\Stdlib\DateTime::class)]
        );
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [
                '2/24/18 17:10',
                '2018-02-24 17:10:00'
            ],
            [
                '2/24/18 17:10:05',
                '2018-02-24 17:10:05'
            ],
            [
                '2/24/18 5:10PM',
                '2018-02-24 17:10:00'
            ],
            [
                '2/24/18 5:10AM',
                '2018-02-24 05:10:00'
            ],
            [
                '2-24-18 5:10PM',
                '2018-02-24 17:10:00'
            ],
            [
                '02/24/2018 17:10',
                '2018-02-24 17:10:00'
            ],
            [
                '2018/02/24 17:10',
                '2018-02-24 17:10:00'
            ],
            [
                '2018/24/02 17:10',
                null
            ],
            [
                '02/24/2018',
                '2018-02-24 00:00:00'
            ],
            [
                '2018-12-11',
                '2018-12-11 00:00:00'
            ]
        ];
    }

    /**
     * @param string                     $value
     * @param string|int|float|bool|null $expected
     *
     * @dataProvider  dataProvider
     */
    public function testSanitize($value, $expected)
    {
        $result = $this->sanitizer->sanitize($value);
        if ($expected === null) {
            $this->assertNull($result);
        } else {
            $this->assertEquals($expected, $result);
        }
    }
}
