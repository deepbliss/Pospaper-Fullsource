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

namespace MageModule\Core\Test\Unit\Model\Data;

class MapperTest extends \Magento\Framework\TestFramework\Unit\BaseTestCase
{
    /**
     * @var \MageModule\Core\Model\Data\Mapper
     */
    private $model;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    private $objectFactory;

    public function setUp()
    {
        parent::setUp();

        $helper = $this->objectManager->getObject(\MageModule\Core\Helper\Data::class);

        $this->model = $this->objectManager->getObject(
            \MageModule\Core\Model\Data\Mapper::class,
            ['helper' => $helper]
        );

        $objectFactoryMock = $this->getMockBuilder('Magento\Framework\DataObjectFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $objectFactoryMock->method('create')->willReturn(
            $this->objectManager->getObject(\Magento\Framework\DataObject::class)
        );
        $this->objectFactory = $objectFactoryMock;
    }

    /**
     * @return array
     */
    public function getValidMapping()
    {
        return [
            'entity_id'   => 'test_entity_id',
            'name'        => 'customer_name',
            'postal_code' => 'zip_code'
        ];
    }

    /**
     * @return array
     */
    public function getNonUniqueMapping()
    {
        return [
            'entity_id'   => 'test_entity_id',
            'name'        => 'customer_name',
            'postal_code' => 'zip_code',
            'postcode'    => 'zip_code'
        ];
    }

    /**
     * @return array
     */
    public function getMappingWhereNewFieldEqualsPreviousOldField()
    {
        return [
            'entity_id'   => 'test_entity_id',
            'name'        => 'customer_name',
            'postal_code' => 'zip_code',
            'some_id'     => 'entity_id'
        ];
    }

    /**
     * @return array
     */
    public function getMappingWhereToFieldsAreMappedToSameField()
    {
        return [
            'entity_id' => 'some_field',
            'name'      => 'some_field'
        ];
    }

    /**
     * @return array
     */
    public function getArrayToMap()
    {
        return [
            'entity_id'   => '1',
            'name'        => 'MageModule',
            'postal_code' => '90210',
            'street1'     => '123 Rodeo Drive',
            'city'        => 'Beverly Hills'
        ];
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getObjectToMap()
    {
        /** @var \Magento\Framework\DataObject $object */
        $object = $this->objectManager->getObject(\Magento\Framework\DataObject::class);
        $object->setData($this->getArrayToMap());

        return $object;
    }

    public function testValidateValidMapping()
    {
        $this->assertTrue($this->model->validateMapping($this->getValidMapping()));
    }

    public function testMapArrayKeepOrigFields()
    {
        $array = $this->getArrayToMap();

        $this->model->setMapping($this->getValidMapping());
        $this->model->map($array, true);

        $this->assertArrayHasKey('entity_id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('postal_code', $array);
        $this->assertArrayHasKey('street1', $array);
        $this->assertArrayHasKey('city', $array);
        $this->assertArrayHasKey('test_entity_id', $array);
        $this->assertArrayHasKey('customer_name', $array);
        $this->assertArrayHasKey('zip_code', $array);

        $this->assertEquals(1, $array['entity_id']);
        $this->assertEquals(1, $array['test_entity_id']);
        $this->assertEquals('MageModule', $array['name']);
        $this->assertEquals('MageModule', $array['customer_name']);
        $this->assertEquals('90210', $array['postal_code']);
        $this->assertEquals('90210', $array['zip_code']);
        $this->assertEquals('123 Rodeo Drive', $array['street1']);
        $this->assertEquals('Beverly Hills', $array['city']);
    }

    public function testMapArrayOmitOrigFields()
    {
        $array = $this->getArrayToMap();

        $this->model->setMapping($this->getValidMapping());
        $this->model->map($array, false);

        $this->assertArrayNotHasKey('entity_id', $array);
        $this->assertArrayNotHasKey('name', $array);
        $this->assertArrayNotHasKey('postal_code', $array);
        $this->assertArrayHasKey('street1', $array);
        $this->assertArrayHasKey('city', $array);
        $this->assertArrayHasKey('test_entity_id', $array);
        $this->assertArrayHasKey('customer_name', $array);
        $this->assertArrayHasKey('zip_code', $array);

        $this->assertEquals(1, $array['test_entity_id']);
        $this->assertEquals('MageModule', $array['customer_name']);
        $this->assertEquals('90210', $array['zip_code']);
        $this->assertEquals('123 Rodeo Drive', $array['street1']);
        $this->assertEquals('Beverly Hills', $array['city']);
    }

    public function testMapObjectKeepOrigFields()
    {
        $object = $this->getObjectToMap();

        $this->model->setMapping($this->getValidMapping());
        $this->model->map($object, true);

        $this->assertTrue($object->hasData('entity_id'));
        $this->assertTrue($object->hasData('name'));
        $this->assertTrue($object->hasData('postal_code'));
        $this->assertTrue($object->hasData('street1'));
        $this->assertTrue($object->hasData('city'));
        $this->assertTrue($object->hasData('test_entity_id'));
        $this->assertTrue($object->hasData('customer_name'));
        $this->assertTrue($object->hasData('zip_code'));

        $this->assertEquals(1, $object->getData('entity_id'));
        $this->assertEquals(1, $object->getData('test_entity_id'));
        $this->assertEquals('MageModule', $object->getData('name'));
        $this->assertEquals('MageModule', $object->getData('customer_name'));
        $this->assertEquals('90210', $object->getData('postal_code'));
        $this->assertEquals('90210', $object->getData('zip_code'));
        $this->assertEquals('123 Rodeo Drive', $object->getData('street1'));
        $this->assertEquals('Beverly Hills', $object->getData('city'));
    }

    public function testMapObjectOmitOrigFields()
    {
        $object = $this->getObjectToMap();

        $this->model->setMapping($this->getValidMapping());
        $this->model->map($object, false);

        $this->assertTrue(!$object->hasData('entity_id'));
        $this->assertTrue(!$object->hasData('name'));
        $this->assertTrue(!$object->hasData('postal_code'));
        $this->assertTrue($object->hasData('street1'));
        $this->assertTrue($object->hasData('city'));
        $this->assertTrue($object->hasData('test_entity_id'));
        $this->assertTrue($object->hasData('customer_name'));
        $this->assertTrue($object->hasData('zip_code'));

        $this->assertEquals(1, $object->getData('test_entity_id'));
        $this->assertEquals('MageModule', $object->getData('customer_name'));
        $this->assertEquals('90210', $object->getData('zip_code'));
        $this->assertEquals('123 Rodeo Drive', $object->getData('street1'));
        $this->assertEquals('Beverly Hills', $object->getData('city'));
    }

    public function testMapTwoFieldsMappedToSameField()
    {
        $array = $this->getArrayToMap();

        $this->model->setMapping($this->getMappingWhereToFieldsAreMappedToSameField());
        $this->model->map($array, false);

        $this->assertArrayNotHasKey('entity_id', $array);
        $this->assertArrayNotHasKey('name', $array);
        $this->assertArrayHasKey('some_field', $array);
        $this->assertArrayHasKey('postal_code', $array);
        $this->assertArrayHasKey('street1', $array);
        $this->assertArrayHasKey('city', $array);
        $this->assertCount(4, $array);

        $this->assertEquals('MageModule', $array['some_field']);
        $this->assertEquals('90210', $array['postal_code']);
        $this->assertEquals('123 Rodeo Drive', $array['street1']);
        $this->assertEquals('Beverly Hills', $array['city']);
    }

    public function testGetExistingMappedField()
    {
        $this->model->setMapping($this->getValidMapping());
        $result = $this->model->getMappedField('name');
        $this->assertEquals('customer_name', $result);
    }

    public function testGetNonExistentMappedField()
    {
        $this->model->setMapping($this->getValidMapping());
        $result = $this->model->getMappedField('some_non_field');
        $this->assertNull($result);
    }

    public function testMapObjectWithComplexField()
    {
        $data = [
            'product_options' =>
                [
                    'options' =>
                        [
                            [
                                'label'        => 'Test Text Field Option',
                                'value'        => 'lorem ipsum',
                                'print_value'  => 'lorem ipsum',
                                'option_id'    => '1',
                                'option_type'  => 'field',
                                'option_value' => 'lorem ipsum',
                                'custom_view'  => false,
                            ],
                            [
                                'label'        => 'Test Drop Down Option',
                                'value'        => 'Option Drop Down 2',
                                'print_value'  => 'Option Drop Down 2',
                                'option_id'    => '3',
                                'option_type'  => 'drop_down',
                                'option_value' => '2',
                                'custom_view'  => false,
                            ]
                        ]

                ]
        ];

        $object = $this->objectFactory->create();
        $object->setData($data);

        $this->model->setMapping(['product_options/options' => 'options']);
        $result = $this->model->map($object, false);
        $this->assertTrue(is_array($result->getData('product_options/options')));
        $this->assertEquals($result->getData('product_options/options/0/label'), 'Test Text Field Option');
        $this->assertEquals($result->getData('product_options/options/0/value'), 'lorem ipsum');
        $this->assertEquals($result->getData('product_options/options/0/print_value'), 'lorem ipsum');
        $this->assertEquals($result->getData('product_options/options/0/option_id'), '1');
        $this->assertEquals($result->getData('product_options/options/0/option_type'), 'field');
        $this->assertEquals($result->getData('product_options/options/0/option_value'), 'lorem ipsum');
        $this->assertFalse($result->getData('product_options/options/0/custom_view'));

        $this->assertEquals($result->getData('product_options/options/1/label'), 'Test Drop Down Option');
        $this->assertEquals($result->getData('product_options/options/1/value'), 'Option Drop Down 2');
        $this->assertEquals($result->getData('product_options/options/1/print_value'), 'Option Drop Down 2');
        $this->assertEquals($result->getData('product_options/options/1/option_id'), '3');
        $this->assertEquals($result->getData('product_options/options/1/option_type'), 'drop_down');
        $this->assertEquals($result->getData('product_options/options/1/option_value'), '2');
        $this->assertFalse($result->getData('product_options/options/1/custom_view'));
    }
}
