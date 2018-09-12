<?php

namespace MageModule\Core\Test\Unit\Model\Data\Validator;

class RequiredFieldsTest extends \Magento\Framework\TestFramework\Unit\BaseTestCase
{
    /**
     * @param array $requiredFields
     *
     * @return \MageModule\Core\Model\Data\Validator\RequiredFields
     */
    private function getValidator(array $requiredFields)
    {
        $resultFactoryMock = $this->getMockBuilder('MageModule\Core\Model\Data\Validator\ResultFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $resultFactoryMock->method('create')->withAnyParameters()->willReturnArgument(0);

        /** @var \MageModule\Core\Model\Data\Validator\RequiredFields $validator */
        $validator = $this->objectManager->getObject(
            \MageModule\Core\Model\Data\Validator\RequiredFields::class,
            [
                'resultFactory'  => $resultFactoryMock,
                'requiredFields' => $requiredFields
            ]
        );

        return $validator;
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [
                [
                    'entity_id'    => '2517',
                    'increment_id' => '1433',
                    'store_id'     => '1',
                    'store_code'   => 'default',
                    'store_name'   => 'Main Website',
                    'state'        => 'processing',
                    'status'       => 'processing',
                    'created_at'   => '2/26/18 1:28',
                    'updated_at'   => '2/26/18 1:32',
                    'addresses'    => ''
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAllRequiredFieldsArePresent(array $data)
    {
        $validator = $this->getValidator(['store_id', 'store_code', 'store_name']);

        $result = $validator->validate($data);
        $this->assertTrue($result['isValid']);
        $this->assertNull($result['message']);
        $this->assertEmpty($result['invalidData']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testTwoRequiredFieldsAreMissing(array $data)
    {
        $validator = $this->getValidator(['store_id', 'missing_field_1', 'missing_field_2']);

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertCount(2, $result['invalidData']);
        $this->assertContains('missing_field_1', $result['invalidData']);
        $this->assertContains('missing_field_2', $result['invalidData']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testRequiredFieldsNotDeclared(array $data)
    {
        $validator = $this->getValidator([]);

        $result = $validator->validate($data);
        $this->assertTrue($result['isValid']);
        $this->assertNull($result['message']);
        $this->assertEmpty($result['invalidData']);
    }

    public function testDataEmpty()
    {
        $data = [];

        $validator = $this->getValidator(['store_id', 'store_code', 'store_name']);

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertCount(3, $result['invalidData']);
        $this->assertContains('store_id', $result['invalidData']);
        $this->assertContains('store_code', $result['invalidData']);
        $this->assertContains('store_name', $result['invalidData']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testChoiceOfTwoFieldsOneRequiredOnePresent(array $data)
    {
        $validator = $this->getValidator(
            [
                [1 => ['store_id', 'some_other_choice']]
            ]
        );

        $result = $validator->validate($data);
        $this->assertTrue($result['isValid']);
        $this->assertNull($result['message']);
        $this->assertEmpty($result['invalidData']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testChoiceOfTwoFieldsOneRequiredNonePresent(array $data)
    {
        $validator = $this->getValidator(
            [
                [1 => ['some_missing_choice_1', 'some_missing_choice_2']]
            ]
        );

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertContains('some_missing_choice_1', $result['message']);
        $this->assertContains('some_missing_choice_2', $result['message']);
        $this->assertNotEmpty($result['invalidData']);
        $this->assertEquals(1, $result['invalidData'][0]['required']);
        $this->assertCount(2, $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_1', $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_2', $result['invalidData'][0]['choices']);
        $this->assertEmpty($result['invalidData'][0]['present']);
        $this->assertCount(2, $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_1', $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_2', $result['invalidData'][0]['missing']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testChoiceOfThreeFieldsOneRequiredOnePresent(array $data)
    {
        $validator = $this->getValidator(
            [
                [1 => ['entity_id', 'some_missing_choice_1', 'some_missing_choice_2']]
            ]
        );

        $result = $validator->validate($data);
        $this->assertTrue($result['isValid']);
        $this->assertNull($result['message']);
        $this->assertEmpty($result['invalidData']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testChoiceOfThreeFieldsOneRequiredNonePresent(array $data)
    {
        $validator = $this->getValidator(
            [
                [1 => ['some_missing_choice_1', 'some_missing_choice_2', 'some_missing_choice_3']]
            ]
        );

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertContains('some_missing_choice_1', $result['message']);
        $this->assertContains('some_missing_choice_2', $result['message']);
        $this->assertContains('some_missing_choice_3', $result['message']);
        $this->assertNotEmpty($result['invalidData']);
        $this->assertEquals(1, $result['invalidData'][0]['required']);
        $this->assertCount(3, $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_1', $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_2', $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_3', $result['invalidData'][0]['choices']);
        $this->assertEmpty($result['invalidData'][0]['present']);
        $this->assertCount(3, $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_1', $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_2', $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_3', $result['invalidData'][0]['missing']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testChoiceOfFourFieldsTwoRequiredTwoPresent(array $data)
    {
        $validator = $this->getValidator(
            [
                [2 => ['store_id', 'store_code', 'some_missing_choice_1', 'some_missing_choice_2']]
            ]
        );

        $result = $validator->validate($data);
        $this->assertTrue($result['isValid']);
        $this->assertNull($result['message']);
        $this->assertEmpty($result['invalidData']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testChoiceOfFourFieldsTwoRequiredOnePresent(array $data)
    {
        $validator = $this->getValidator(
            [
                [2 => ['store_id', 'some_missing_choice_1', 'some_missing_choice_2', 'some_missing_choice_3']]
            ]
        );

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertContains('some_missing_choice_1', $result['message']);
        $this->assertContains('some_missing_choice_2', $result['message']);
        $this->assertContains('some_missing_choice_3', $result['message']);
        $this->assertNotEmpty($result['invalidData']);
        $this->assertEquals(2, $result['invalidData'][0]['required']);
        $this->assertCount(4, $result['invalidData'][0]['choices']);
        $this->assertContains('store_id', $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_1', $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_2', $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_3', $result['invalidData'][0]['choices']);
        $this->assertCount(1, $result['invalidData'][0]['present']);
        $this->assertCount(3, $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_1', $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_2', $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_3', $result['invalidData'][0]['missing']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testChoiceOfFourFieldsTwoRequiredNonePresent(array $data)
    {
        $validator = $this->getValidator(
            [
                [2 => ['some_missing_choice_1', 'some_missing_choice_2', 'some_missing_choice_3', 'some_missing_choice_4']]
            ]
        );

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertContains('some_missing_choice_1', $result['message']);
        $this->assertContains('some_missing_choice_2', $result['message']);
        $this->assertContains('some_missing_choice_3', $result['message']);
        $this->assertContains('some_missing_choice_4', $result['message']);
        $this->assertEquals(2, $result['invalidData'][0]['required']);
        $this->assertCount(4, $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_1', $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_2', $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_3', $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_4', $result['invalidData'][0]['choices']);
        $this->assertEmpty($result['invalidData'][0]['present']);
        $this->assertCount(4, $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_1', $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_2', $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_3', $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_4', $result['invalidData'][0]['missing']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCombinationRegularRequiredFieldsAndOptionalFieldsAllRequiredArePresent(array $data)
    {
        $validator = $this->getValidator(
            [
                'entity_id',
                'increment_id',
                [2 => ['state', 'status', 'not_present_1', 'not_present_2']],
                [1 => ['created_at', 'updated_at', 'not_present_1', 'not_present_2']]
            ]
        );

        $result = $validator->validate($data);
        $this->assertTrue($result['isValid']);
        $this->assertNull($result['message']);
        $this->assertEmpty($result['invalidData']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCombinationRegularRequiredFieldsAndOptionalFieldsSomeAreMissing(array $data)
    {
        $validator = $this->getValidator(
            [
                'entity_id',
                'missing_choice',
                [3 => ['created_at', 'updated_at', 'not_present_1', 'not_present_2']]
            ]
        );

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertNotEmpty($result['invalidData']);
        $this->assertEquals(3, $result['invalidData'][1]['required']);
        $this->assertCount(4, $result['invalidData'][1]['choices']);
        $this->assertEquals('created_at', $result['invalidData'][1]['choices'][0]);
        $this->assertEquals('updated_at', $result['invalidData'][1]['choices'][1]);
        $this->assertEquals('not_present_1', $result['invalidData'][1]['choices'][2]);
        $this->assertEquals('not_present_2', $result['invalidData'][1]['choices'][3]);
        $this->assertCount(2, $result['invalidData'][1]['present']);
        $this->assertEquals('created_at', $result['invalidData'][1]['present'][0]);
        $this->assertEquals('updated_at', $result['invalidData'][1]['present'][1]);
        $this->assertCount(2, $result['invalidData'][1]['missing']);
        $this->assertEquals('not_present_1', $result['invalidData'][1]['missing'][0]);
        $this->assertEquals('not_present_2', $result['invalidData'][1]['missing'][1]);
    }
}
