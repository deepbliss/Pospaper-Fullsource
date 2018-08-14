<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model\Filters\Transaction;

use Aheadworks\RewardPoints\Model\Filters\Transaction\CustomerSelection;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\Filters\Transaction\CustomerSelectionTest
 */
class CustomerSelectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CustomerSelection
     */
    private $object;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->object = $this->objectManager->getObject(CustomerSelection::class, []);

        $this->assertAttributeEquals(CustomerSelection::DEFAULT_FIELD_NAME, 'fieldName', $this->object);
    }

    /**
     * Test set custom field name
     */
    public function testCustomFieldName()
    {
        $customeFieldName = 'custome_field_name';

        $object = $this->objectManager->getObject(CustomerSelection::class, ['fieldName' => $customeFieldName]);
        $this->assertAttributeEquals($customeFieldName, 'fieldName', $object);
    }

    /**
     * Test filter method
     *
     * @dataProvider dataProviderFilterTest
     * @param mixed $value
     * @param mixed $expected
     */
    public function testFilterMethod($value, $expected)
    {
        $this->assertEquals($expected, $this->object->filter($value));
    }

    /**
     * Data provider for filter test
     *
     * @return array
     */
    public function dataProviderFilterTest()
    {
        return [
            [1, []],
            [[], []],
            [null, []],
            ['', []],
            [new \stdClass(1), []],
            [[CustomerSelection::DEFAULT_FIELD_NAME => 1], []],
            [[CustomerSelection::DEFAULT_FIELD_NAME => []], []],
            [[CustomerSelection::DEFAULT_FIELD_NAME => null], []],
            [[CustomerSelection::DEFAULT_FIELD_NAME => ''], []],
            [[CustomerSelection::DEFAULT_FIELD_NAME => new \stdClass(1)], []],
            [[CustomerSelection::DEFAULT_FIELD_NAME => [1]], [[
                TransactionInterface::CUSTOMER_ID => null,
                TransactionInterface::CUSTOMER_NAME => null,
                TransactionInterface::CUSTOMER_EMAIL => null,
                TransactionInterface::COMMENT_TO_CUSTOMER => null,
                TransactionInterface::COMMENT_TO_ADMIN => null,
                TransactionInterface::BALANCE => null,
                TransactionInterface::EXPIRATION_DATE => null,
                TransactionInterface::WEBSITE_ID => null,
            ]]],
            [[CustomerSelection::DEFAULT_FIELD_NAME => [null]], [[
                TransactionInterface::CUSTOMER_ID => null,
                TransactionInterface::CUSTOMER_NAME => null,
                TransactionInterface::CUSTOMER_EMAIL => null,
                TransactionInterface::COMMENT_TO_CUSTOMER => null,
                TransactionInterface::COMMENT_TO_ADMIN => null,
                TransactionInterface::BALANCE => null,
                TransactionInterface::EXPIRATION_DATE => null,
                TransactionInterface::WEBSITE_ID => null,
            ]]],
            [
                [
                    CustomerSelection::DEFAULT_FIELD_NAME =>
                    [
                        [
                            TransactionInterface::CUSTOMER_ID => 1,
                            TransactionInterface::CUSTOMER_NAME => 'Test User',
                            TransactionInterface::CUSTOMER_EMAIL => 'test@test.com',
                            TransactionInterface::WEBSITE_ID => [1]
                        ]
                    ],
                    TransactionInterface::COMMENT_TO_CUSTOMER => 'Comment To Customer',
                    TransactionInterface::COMMENT_TO_ADMIN => 'Comment To admin',
                    TransactionInterface::BALANCE => 150,
                    TransactionInterface::EXPIRATION_DATE => '2016-11-01',
                    TransactionInterface::WEBSITE_ID => 1,
                ],
                [
                    [
                        TransactionInterface::CUSTOMER_ID => 1,
                        TransactionInterface::CUSTOMER_NAME => 'Test User',
                        TransactionInterface::CUSTOMER_EMAIL => 'test@test.com',
                        TransactionInterface::COMMENT_TO_CUSTOMER => 'Comment To Customer',
                        TransactionInterface::COMMENT_TO_ADMIN => 'Comment To admin',
                        TransactionInterface::BALANCE => 150,
                        TransactionInterface::EXPIRATION_DATE => '2016-11-01',
                        TransactionInterface::WEBSITE_ID => 1,
                    ]
                ]
            ],
        ];
    }
}
