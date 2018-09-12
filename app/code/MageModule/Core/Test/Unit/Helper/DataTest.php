<?php
/**
 * Copyright (c) 2018 MageModule: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author        MageModule admin@magemodule.com
 * @copyright     2018 MageModule
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Test\Unit\Helper;

class DataTest extends \Magento\Framework\TestFramework\Unit\BaseTestCase
{
    /**
     * @var \MageModule\Core\Helper\Data
     */
    private $helper;

    public function setUp()
    {
        parent::setUp();
        $this->helper = $this->objectManager->getObject(\MageModule\Core\Helper\Data::class);
    }

    /**
     * @return array
     */
    public function providerTestArrayFunctions()
    {
        return [
            [
                [
                    0 => '',
                    1 => '',
                    2 => 'some string',
                    3 => [
                        0 => 'some string'
                    ],
                    4 => new \Magento\Framework\DataObject()
                ]
            ]
        ];
    }

    /**
     * @dataProvider providerTestArrayFunctions
     *
     * @param array $array
     */
    public function testNullifyEmpty(array $array)
    {
        $this->helper->nullifyEmpty($array);
        $this->assertNull($array[0]);
        $this->assertNull($array[1]);
        $this->assertNotNull($array[2]);
        $this->assertNotNull($array[3]);
        $this->assertNotNull($array[4]);
    }

    /**
     * @dataProvider providerTestArrayFunctions
     *
     * @param array $array
     */
    public function testRemoveObjects(array $array)
    {
        $this->assertArrayHasKey(4, $array);
        $this->helper->removeObjects($array);
        $this->assertArrayNotHasKey(4, $array);
    }

    /**
     * @dataProvider providerTestArrayFunctions
     *
     * @param array $array
     */
    public function testRemoveArrays(array $array)
    {
        $this->assertArrayHasKey(0, $array[3]);
        $this->helper->removeArrays($array);
        $this->assertArrayNotHasKey(3, $array);
    }

    /**
     * @return array
     */
    public function getCsvDataSampleArray()
    {
        return [
            0 => [
                'entity_id'    => '1',
                'state'        => 'complete',
                'status'       => 'complete',
                'coupon_code'  => null,
                'protect_code' => 'a658ab',
                1              => 'hi there',
                2              => [
                    'some' => 1,
                    'keys' => '2'
                ]
            ],
            1 => [
                'coupon_code'          => null,
                'protect_code'         => '6bee66',
                'shipping_description' => 'Flat Rate - Fixed',
                'is_virtual'           => '0',
                'store_id'             => '1',
                'customer_id'          => '1',
            ],
            2 => [
                0 => 'some value',
                2 => 'some other value'
            ]
        ];
    }

    public function testEqualizeArrayKeys()
    {
        $data = $this->getCsvDataSampleArray();

        $expectedKeyCount = count($data[0] + $data[1] + $data[2]);

        $this->helper->equalizeArrayKeys($data);
        $keys1 = implode('', array_keys($data[0]));
        $keys2 = implode('', array_keys($data[1]));
        $keys3 = implode('', array_keys($data[2]));

        $this->assertEquals($keys1, $keys2);
        $this->assertEquals($keys1, $keys3);
        $this->assertEquals($expectedKeyCount, count($data[0]));
        $this->assertEquals($expectedKeyCount, count($data[1]));
        $this->assertEquals($expectedKeyCount, count($data[2]));
    }

    public function testAddHeadersRowToArray()
    {
        $data = $this->getCsvDataSampleArray();
        $this->helper->equalizeArrayKeys($data);
        $this->helper->addHeadersRowToArray($data);

        $headers = implode('', $data[0]);
        $keys1   = implode('', array_keys($data[1]));
        $keys2   = implode('', array_keys($data[2]));
        $keys3   = implode('', array_keys($data[3]));

        $this->assertCount(4, $data);
        $this->assertEquals($headers, $keys1);
        $this->assertEquals($headers, $keys2);
        $this->assertEquals($headers, $keys3);
    }

    public function testAddPrefix()
    {
        $data   = $this->getCsvDataSampleArray();
        $prefix = 'testing_';
        foreach ($data as &$subarray) {
            $this->helper->addPrefix($prefix, $subarray);
            foreach ($subarray as $key => $value) {
                $this->assertStringStartsWith($prefix, $key);
            }
        }
    }

    public function testStringifyPathsFlatArray()
    {
        $data   = $this->getCsvDataSampleArray();
        $result = $this->helper->stringifyPaths($data[1]);

        $this->assertEquals('coupon_code', $result[0]);
        $this->assertEquals('protect_code', $result[1]);
        $this->assertEquals('shipping_description', $result[2]);
        $this->assertEquals('is_virtual', $result[3]);
        $this->assertEquals('store_id', $result[4]);
        $this->assertEquals('customer_id', $result[5]);
        $this->assertCount(6, $result);
    }

    public function testStringifyPathsMultidimensionalArray()
    {
        $data   = $this->getCsvDataSampleArray();
        $result = $this->helper->stringifyPaths($data);

        $this->assertEquals('0', $result[0]);
        $this->assertEquals('0/entity_id', $result[1]);
        $this->assertEquals('0/2/some', $result[8]);
    }

    public function testStartsWithSameCaseAndIsCaseSensitive()
    {
        $needle   = 'something-';
        $haystack = 'something-is-in this string';
        $this->assertTrue($this->helper->startsWith($needle, $haystack, true));
    }

    public function testStartsWithSameCaseAndIsNotCaseSensitive()
    {
        $needle   = 'something-';
        $haystack = 'something-is-in this string';
        $this->assertTrue($this->helper->startsWith($needle, $haystack, false));
    }

    public function testStartsWithDifferentCaseAndIsCaseSensitive()
    {
        $needle   = 'Something';
        $haystack = 'something is in this string';
        $this->assertFalse($this->helper->startsWith($needle, $haystack, true));
    }

    public function testStartsWithDifferentCaseAndIsNotCaseSensitive()
    {
        $needle   = 'Something';
        $haystack = 'something is in this string';
        $this->assertTrue($this->helper->startsWith($needle, $haystack, false));
    }

    public function testDoesNotStartsWithContainsSpecialChars()
    {
        $needle = '^checking';
        $haystack = '^chec$king this # \\ thing out.';
        $this->assertFalse($this->helper->startsWith($needle, $haystack, false));
    }

    public function testStartsWithContainsSpecialCharsAndIsNotCaseSensitive()
    {
        $needle = '^Chec$king';
        $haystack = '^chec$king this # \\ thing out.';
        $this->assertTrue($this->helper->startsWith($needle, $haystack, false));
    }

    public function testStartsWithContainsSpecialCharsAndIsCaseSensitive()
    {
        $needle = '^Chec$king';
        $haystack = '^chec$king this # \\ thing out.';
        $this->assertFalse($this->helper->startsWith($needle, $haystack, true));
    }

    public function testEndsWithSameCaseAndIsCaseSensitive()
    {
        $needle   = 'string';
        $haystack = 'something-is-in this string';
        $this->assertTrue($this->helper->endsWith($needle, $haystack, true));
    }

    public function testEndsWithSameCaseAndIsNotCaseSensitive()
    {
        $needle   = 'string';
        $haystack = 'something-is-in this string';
        $this->assertTrue($this->helper->endsWith($needle, $haystack, false));
    }

    public function testEndsWithDifferentCaseAndIsCaseSensitive()
    {
        $needle   = 'String';
        $haystack = 'something is in this string';
        $this->assertFalse($this->helper->endsWith($needle, $haystack, true));
    }

    public function testEndsWithDifferentCaseButIsNotCaseSensitive()
    {
        $needle   = 'String';
        $haystack = 'something is in this string';
        $this->assertTrue($this->helper->endsWith($needle, $haystack, false));
    }

    public function testDoesNotEndWithContainsSpecialChars()
    {
        $needle = 'out';
        $haystack = '^chec$king this # \\ thing out$.';
        $this->assertFalse($this->helper->endsWith($needle, $haystack, false));
    }

    public function testEndsWithContainsSpecialCharsAndIsNotCaseSensitive()
    {
        $needle = 'Out$.';
        $haystack = '^chec$king this # \\ thing out$.';
        $this->assertTrue($this->helper->endsWith($needle, $haystack, false));
    }

    public function testEndsWithContainsSpecialCharsAndIsCaseSensitive()
    {
        $needle = 'Out$.';
        $haystack = '^chec$king this # \\ thing out$.';
        $this->assertFalse($this->helper->endsWith($needle, $haystack, true));
    }
}
