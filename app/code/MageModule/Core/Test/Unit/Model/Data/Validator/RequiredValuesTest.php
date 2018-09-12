<?php

namespace MageModule\Core\Test\Unit\Model\Data\Validator;

class RequiredValuesTest extends \Magento\Framework\TestFramework\Unit\BaseTestCase
{
    /**
     * @param array $requiredFields
     *
     * @return \MageModule\Core\Model\Data\Validator\RequiredValues
     */
    private function getValidator(array $requiredFields)
    {
        $resultFactoryMock = $this->getMockBuilder('MageModule\Core\Model\Data\Validator\ResultFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $resultFactoryMock->method('create')->withAnyParameters()->willReturnArgument(0);

        /** @var \MageModule\Core\Model\Data\Validator\RequiredValues $validator */
        $validator = $this->objectManager->getObject(
            \MageModule\Core\Model\Data\Validator\RequiredValues::class,
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
                    'increment_id' => '0',
                    'store_id'     => 0,
                    'store_code'   => '',
                    'store_name'   => null,
                    'state'        => false,
                    'status'       => NULL,
                    'created_at'   => '2/26/18 1:28',
                    'updated_at'   => '2/26/18 1:32',
                    'addresses'    => ' '
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFieldContainingFalseShouldReturnValid(array $data)
    {
        $validator = $this->getValidator(['state']);

        $result = $validator->validate($data);
        $this->assertTrue($result['isValid']);
        $this->assertNull($result['message']);
        $this->assertEmpty($result['invalidData']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFieldContainingZeroShouldReturnValid(array $data)
    {
        $validator = $this->getValidator(['store_id']);

        $result = $validator->validate($data);
        $this->assertTrue($result['isValid']);
        $this->assertNull($result['message']);
        $this->assertEmpty($result['invalidData']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFieldContainingZeroStringShouldReturnValid(array $data)
    {
        $validator = $this->getValidator(['increment_id']);

        $result = $validator->validate($data);
        $this->assertTrue($result['isValid']);
        $this->assertNull($result['message']);
        $this->assertEmpty($result['invalidData']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFieldContainingEmptyStringShouldReturnInvalid(array $data)
    {
        $validator = $this->getValidator(['store_code']);

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertCount(1, $result['invalidData']);
        $this->assertEquals('store_code', $result['invalidData'][0]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFieldContainingLowercaseNullShouldReturnInvalid(array $data)
    {
        $validator = $this->getValidator(['store_name']);

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertCount(1, $result['invalidData']);
        $this->assertEquals('store_name', $result['invalidData'][0]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFieldContainingUppercaseNullShouldReturnInvalid(array $data)
    {
        $validator = $this->getValidator(['status']);

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertCount(1, $result['invalidData']);
        $this->assertEquals('status', $result['invalidData'][0]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFieldContainingOnlySpaceShouldReturnInvalid(array $data)
    {
        $validator = $this->getValidator(['addresses']);

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertCount(1, $result['invalidData']);
        $this->assertEquals('addresses', $result['invalidData'][0]);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFieldMissingFieldShouldReturnInvalid(array $data)
    {
        $validator = $this->getValidator(['missing_field_1']);

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertCount(1, $result['invalidData']);
        $this->assertEquals('missing_field_1', $result['invalidData'][0]);
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
                [1 => ['store_code', 'store_name', 'some_missing_choice_1']]
            ]
        );

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertContains('store_code', $result['message']);
        $this->assertContains('store_name', $result['message']);
        $this->assertContains('some_missing_choice_1', $result['message']);
        $this->assertNotEmpty($result['invalidData']);
        $this->assertEquals(1, $result['invalidData'][0]['required']);
        $this->assertCount(3, $result['invalidData'][0]['choices']);
        $this->assertContains('store_code', $result['invalidData'][0]['choices']);
        $this->assertContains('store_name', $result['invalidData'][0]['choices']);
        $this->assertContains('some_missing_choice_1', $result['invalidData'][0]['choices']);
        $this->assertEmpty($result['invalidData'][0]['present']);
        $this->assertCount(3, $result['invalidData'][0]['missing']);
        $this->assertContains('store_code', $result['invalidData'][0]['missing']);
        $this->assertContains('store_name', $result['invalidData'][0]['missing']);
        $this->assertContains('some_missing_choice_1', $result['invalidData'][0]['missing']);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testChoiceOfFourFieldsTwoRequiredTwoPresent(array $data)
    {
        $validator = $this->getValidator(
            [
                [2 => ['state', 'increment_id', 'some_missing_choice_1', 'some_missing_choice_2']]
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
                [2 => ['store_name', 'status', 'store_code', 'updated_at']]
            ]
        );

        $result = $validator->validate($data);
        $this->assertFalse($result['isValid']);
        $this->assertNotNull($result['message']);
        $this->assertContains('store_name', $result['message']);
        $this->assertContains('status', $result['message']);
        $this->assertContains('store_code', $result['message']);
        $this->assertContains('updated_at', $result['message']);
        $this->assertNotEmpty($result['invalidData']);
        $this->assertEquals(2, $result['invalidData'][0]['required']);
        $this->assertCount(4, $result['invalidData'][0]['choices']);
        $this->assertContains('store_name', $result['invalidData'][0]['choices']);
        $this->assertContains('status', $result['invalidData'][0]['choices']);
        $this->assertContains('store_code', $result['invalidData'][0]['choices']);
        $this->assertContains('updated_at', $result['invalidData'][0]['choices']);
        $this->assertCount(1, $result['invalidData'][0]['present']);
        $this->assertContains('updated_at', $result['invalidData'][0]['present']);
        $this->assertCount(3, $result['invalidData'][0]['missing']);
        $this->assertContains('store_name', $result['invalidData'][0]['missing']);
        $this->assertContains('status', $result['invalidData'][0]['missing']);
        $this->assertContains('store_code', $result['invalidData'][0]['missing']);
    }
}
