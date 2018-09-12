<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author        MageModule, LLC admin@magemodule.com
 * @copyright     2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Test\Unit\Model\Data\Sanitizer;

class DateTest extends \Magento\Framework\TestFramework\Unit\BaseTestCase
{
    /**
     * @var \MageModule\Core\Model\Data\Sanitizer\Date
     */
    private $sanitizer;

    public function setUp()
    {
        parent::setUp();

        $this->sanitizer = $this->objectManager->getObject(
            \MageModule\Core\Model\Data\Sanitizer\Date::class,
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
                '2018-02-24'
            ],
            [
                '2/24/18 17:10:05',
                '2018-02-24'
            ],
            [
                '2/24/18 5:10PM',
                '2018-02-24'
            ],
            [
                '2/24/18 5:10AM',
                '2018-02-24'
            ],
            [
                '2-24-18 5:10PM',
                '2018-02-24'
            ],
            [
                '02/24/2018 17:10',
                '2018-02-24'
            ],
            [
                '2018/02/24 17:10',
                '2018-02-24'
            ],
            [
                '02/24/2018',
                '2018-02-24'
            ],
            [
                '2018-12-11',
                '2018-12-11'
            ],
            [
                '2018/24/02 17:10',
                null
            ]
        ];
    }

    /**
     * @param string $value
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

    public function testSanitizeInvalidDateString()
    {
        $datetime = '2018/24/02 17:10';
        $result   = $this->sanitizer->sanitize($datetime);
        $this->assertNull($result);
    }
}
