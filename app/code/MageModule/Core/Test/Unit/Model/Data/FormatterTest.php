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
 * @author       MageModule admin@magemodule.com 
 * @copyright   2018 MageModule
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Test\Unit\Model\Data;

class FormatterTest extends \Magento\Framework\TestFramework\Unit\BaseTestCase
{
    /**
     * @var \MageModule\Core\Model\Data\Formatter
     */
    private $formatter;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    private $objectFactory;

    /**
     * @var \MageModule\Core\Model\Data\Formatter\IteratorFactory
     */
    private $iteratorFactory;

    public function setUp()
    {
        parent::setUp();

        $objectFactoryMock = $this->getMockBuilder('Magento\Framework\DataObjectFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $objectFactoryMock->method('create')->willReturn(
            $this->objectManager->getObject(\Magento\Framework\DataObject::class)
        );
        $this->objectFactory = $objectFactoryMock;

        $helper = $this->objectManager->getObject(\MageModule\Core\Helper\Data::class);

        $this->formatter = $this->objectManager->getObject(
            \MageModule\Core\Model\Data\Formatter::class,
            [
                'objectFactory' => $objectFactoryMock,
                'helper'        => $helper
            ]
        );

        $iteratorFactoryMock = $this->getMockBuilder('MageModule\Core\Model\Data\Formatter\IteratorFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $iteratorFactoryMock->method('create')->willReturn(
            $this->objectManager->getObject(\MageModule\Core\Model\Data\Formatter\Iterator::class)
        );
        $this->iteratorFactory = $iteratorFactoryMock;
    }

    public function testGetHelper()
    {
        $this->assertInstanceOf(\MageModule\Core\Helper\Data::class, $this->formatter->getHelper());
    }

    public function testSetIterators()
    {
        $iterators = [
            'custom_options' => $this->iteratorFactory->create(),
            'customer_name'  => $this->iteratorFactory->create()
        ];

        $this->formatter->setIterators($iterators);
    }

    public function testGetIterators()
    {
        $iterators = $this->formatter->getIterators();
        $this->assertCount(0, $iterators);

        $this->testSetIterators();
        $iterators = $this->formatter->getIterators();
        $this->assertCount(2, $iterators);
    }

    public function testGetIteratorByFieldname()
    {
        $this->testSetIterators();
        $iterator = $this->formatter->getIterator('custom_options');
        $this->assertInstanceOf(\MageModule\Core\Model\Data\Formatter\Iterator::class, $iterator);
    }

    public function testGetIteratorByInvalidFieldname()
    {
        $this->testSetIterators();
        $iterator = $this->formatter->getIterator('custom_optionsssss');
        $this->assertNull($iterator);
    }

    public function testSetStringFormat()
    {
        $this->formatter->setFormat('string');
    }

    public function testSetArrayFormat()
    {
        $this->formatter->setFormat('array');
    }

    public function testSetObjectFormat()
    {
        $this->formatter->setFormat('object');
    }

    public function testSetInvalidFormat()
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException(\Magento\Framework\Exception\LocalizedException::class);
        } else {
            $this->setExpectedException(\Magento\Framework\Exception\LocalizedException::class);
        }

        $this->formatter->setFormat('some unacceptable value');
    }

    public function testSetInvalidGlue()
    {
        /** @var \Magento\Framework\DataObject $object */
        $object = $this->objectFactory->create();
        $object->setData('glue', 'test');
        $this->formatter->setGlue($object);
        $this->assertNull($this->formatter->getGlue());
    }

    public function testSetGlueArray()
    {
        $array = [
            'testing',
            '123',
            null
        ];
        $this->formatter->setGlue($array);
        $this->assertEquals('testing123', $this->formatter->getGlue());
    }

    public function testSetNewlineGlueWhenAllowed()
    {
        $this->formatter->setAllowNewlineChar(true);
        $this->formatter->setGlue('newline');
        $this->assertEquals(PHP_EOL, $this->formatter->getGlue());
    }

    public function testSetNewlineGlueWhenNotAllowed()
    {
        $this->formatter->setAllowNewlineChar(false);
        $this->formatter->setGlue('newline');
        $this->assertNull($this->formatter->getGlue());
    }

    public function testSetReturnCharGlueWhenAllowed()
    {
        $this->formatter->setAllowReturnChar(true);
        $this->formatter->setGlue('return');
        $this->assertEquals("\r", $this->formatter->getGlue());
    }

    public function testSetReturnCharGlueWhenNotAllowed()
    {
        $this->formatter->setAllowReturnChar(false);
        $this->formatter->setGlue('return');
        $this->assertNull($this->formatter->getGlue());
    }

    public function testSetTabCharGlueWhenAllowed()
    {
        $this->formatter->setAllowTabChar(true);
        $this->formatter->setGlue('tab');
        $this->assertEquals("\t", $this->formatter->getGlue());
    }

    public function testSetTabCharGlueWhenNotAllowed()
    {
        $this->formatter->setAllowTabChar(false);
        $this->formatter->setGlue('tab');
        $this->assertNull($this->formatter->getGlue());
    }

    public function testPrependWithSpecialChars()
    {
        $value    = 'this is the main string';
        $prepend  = PHP_EOL . "\t\rfourthprepend";
        $expected = $prepend . $value;

        $this->formatter->setAllowNewlineChar(true);
        $this->formatter->setAllowReturnChar(true);
        $this->formatter->setAllowTabChar(true);
        $this->formatter->setPrepend(
            [
                'newline',
                'tab',
                'return',
                'fourthprepend'
            ]
        );
        $this->assertEquals($prepend, $this->formatter->getPrepend());
        $this->assertEquals($expected, $this->formatter->prepend($value, $this->formatter->getPrepend()));
    }

    public function testPrependWithoutSpecialChars()
    {
        $value    = 'this is the main string';
        $prepend  = 'fourthprepend';
        $expected = $prepend . $value;

        $this->formatter->setAllowNewlineChar(false);
        $this->formatter->setAllowReturnChar(false);
        $this->formatter->setAllowTabChar(false);
        $this->formatter->setPrepend(
            [
                'newline',
                'tab',
                'return',
                'fourthprepend'
            ]
        );
        $this->assertEquals($prepend, $this->formatter->getPrepend());
        $this->assertEquals($expected, $this->formatter->prepend($value, $this->formatter->getPrepend()));
    }

    public function testAppendWithSpecialChars()
    {
        $value    = 'this is the main string';
        $append   = PHP_EOL . "\t\rfourthappend";
        $expected = $value . $append;

        $this->formatter->setAllowNewlineChar(true);
        $this->formatter->setAllowReturnChar(true);
        $this->formatter->setAllowTabChar(true);
        $this->formatter->setAppend(
            [
                'newline',
                'tab',
                'return',
                'fourthappend'
            ]
        );
        $this->assertEquals($append, $this->formatter->getAppend());
        $this->assertEquals($expected, $this->formatter->append($value, $this->formatter->getAppend()));
    }

    public function testAppendWithoutSpecialChars()
    {
        $value    = 'this is the main string';
        $append   = 'fourthappend';
        $expected = $value . $append;

        $this->formatter->setAllowNewlineChar(false);
        $this->formatter->setAllowReturnChar(false);
        $this->formatter->setAllowTabChar(false);
        $this->formatter->setAppend(
            [
                'newline',
                'tab',
                'return',
                'fourthappend'
            ]
        );
        $this->assertEquals($append, $this->formatter->getAppend());
        $this->assertEquals($expected, $this->formatter->append($value, $this->formatter->getAppend()));
    }

    public function testWrapValueWithSpecialChars()
    {
        $field    = 'customer_name';
        $value    = 'Mage Module';
        $pattern  = '{{newline}}{{return}}{{tab}}{{FIELD}}{{field}}{{value}}{{newline}}{{return}}{{tab}}{{FIELD}}{{field}}';
        $expected = "\n\r\t" . strtoupper($field) . strtolower($field) . $value . "\n\r\t" . strtoupper($field) . strtolower($field);
        $this->formatter->setAllowNewlineChar(true);
        $this->formatter->setAllowReturnChar(true);
        $this->formatter->setAllowTabChar(true);
        $result = $this->formatter->wrapValue($field, $value, $pattern);
        $this->assertEquals($expected, $result);
    }

    public function testWrapValueWithSpecialCharsNotPassingPattern()
    {
        $field    = 'customer_name';
        $value    = 'Mage Module';
        $pattern  = '{{newline}}{{return}}{{tab}}{{FIELD}}{{field}}{{value}}{{newline}}{{return}}{{tab}}{{FIELD}}{{field}}';
        $expected = "\n\r\t" . strtoupper($field) . strtolower($field) . $value . "\n\r\t" . strtoupper($field) . strtolower($field);
        $this->formatter->setAllowNewlineChar(true);
        $this->formatter->setAllowReturnChar(true);
        $this->formatter->setAllowTabChar(true);
        $this->formatter->setValueWrapPattern($pattern);
        $result = $this->formatter->wrapValue($field, $value);
        $this->assertEquals($expected, $result);
    }

    public function testWrapValueWithSpecialCharsAllowedAndSpecialCharsAsValue()
    {
        $field    = 'customer_name';
        $value    = PHP_EOL;
        $pattern  = '{{newline}}{{return}}{{tab}}{{FIELD}}{{field}}{{value}}{{newline}}{{return}}{{tab}}{{FIELD}}{{field}}';
        $expected = "\n\r\t" . strtoupper($field) . strtolower($field) . $value . "\n\r\t" . strtoupper($field) . strtolower($field);
        $this->formatter->setAllowNewlineChar(true);
        $this->formatter->setAllowReturnChar(true);
        $this->formatter->setAllowTabChar(true);
        $result = $this->formatter->wrapValue($field, $value, $pattern);
        $this->assertEquals($expected, $result);
    }

    public function testWrapValueWithoutSpecialChars()
    {
        $field    = 'customer_name';
        $value    = 'Mage Module';
        $pattern  = '{{newline}}{{return}}{{tab}}{{FIELD}}{{field}}{{value}}{{newline}}{{return}}{{tab}}{{FIELD}}{{field}}';
        $expected = strtoupper($field) . strtolower($field) . $value . strtoupper($field) . strtolower($field);
        $this->formatter->setAllowNewlineChar(false);
        $this->formatter->setAllowReturnChar(false);
        $this->formatter->setAllowTabChar(false);
        $result = $this->formatter->wrapValue($field, $value, $pattern);
        $this->assertEquals($expected, $result);
    }

    public function testWrapValueWithoutSpecialCharsNotPassingPattern()
    {
        $field    = 'customer_name';
        $value    = 'Mage Module';
        $pattern  = '{{newline}}{{return}}{{tab}}{{FIELD}}{{field}}{{value}}{{newline}}{{return}}{{tab}}{{FIELD}}{{field}}';
        $expected = strtoupper($field) . strtolower($field) . $value . strtoupper($field) . strtolower($field);
        $this->formatter->setAllowNewlineChar(false);
        $this->formatter->setAllowReturnChar(false);
        $this->formatter->setAllowTabChar(false);
        $this->formatter->setValueWrapPattern($pattern);
        $result = $this->formatter->wrapValue($field, $value);
        $this->assertEquals($expected, $result);
    }

    public function testFilterArrayByIncludedFields()
    {
        $fields = array_flip(['entity_id', 'customer_name']);
        $array  = [
            'foo'           => 'bar',
            'entity_id'     => 50,
            'customer_name' => 'Mage Module'
        ];
        $this->formatter->setIncludedFields($fields);
        $this->formatter->filterArrayByIncludedFields($array);

        reset($array);
        $this->assertCount(2, $array);
        $this->assertEquals('entity_id', key($array));
        next($array);
        $this->assertEquals('customer_name', key($array));
    }

    public function testFilterArrayByIncludedFieldsButNoFieldsMatch()
    {
        $fields = array_flip(['some', 'thing']);
        $array  = [
            'foo'           => 'bar',
            'entity_id'     => 50,
            'customer_name' => 'Mage Module'
        ];
        $this->formatter->setIncludedFields($fields);
        $this->formatter->filterArrayByIncludedFields($array);

        $this->assertCount(2, $array);
        $this->assertNull($array['some']);
        $this->assertNull($array['thing']);
    }

    public function testFilterObjectByIncludedFields()
    {
        $fields = array_flip(['entity_id', 'customer_name']);
        $object = $this->objectFactory->create();
        $object->setData(
            [
                'foo'           => 'bar',
                'entity_id'     => 50,
                'customer_name' => 'Mage Module'
            ]
        );
        $this->formatter->setIncludedFields($fields);
        $this->formatter->filterObjectByIncludedFields($object);

        $this->assertCount(2, $object->getData());
        $this->assertTrue($object->hasData('entity_id'));
        $this->assertTrue($object->hasData('customer_name'));
    }

    public function testFilterObjectByIncludedFieldsButNoFieldsMatch()
    {
        $fields = array_flip(['some', 'thing']);
        $object = $this->objectFactory->create();
        $object->setData(
            [
                'foo'           => 'bar',
                'entity_id'     => 50,
                'customer_name' => 'Mage Module'
            ]
        );
        $this->formatter->setIncludedFields($fields);
        $this->formatter->filterObjectByIncludedFields($object);

        $this->assertCount(2, $object->getData());
        $this->assertTrue($object->hasData('some'));
        $this->assertTrue($object->hasData('thing'));
        $this->assertNull($object->getData('some'));
        $this->assertNull($object->getData('thing'));
        $this->assertFalse($object->hasData('foo'));
        $this->assertFalse($object->hasData('entity_id'));
        $this->assertFalse($object->hasData('customer_name'));
    }
}
