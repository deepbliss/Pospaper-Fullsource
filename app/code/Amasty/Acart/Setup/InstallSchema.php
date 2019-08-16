<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_acart_rule')
        )->addColumn(
            'rule_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Rule Id'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Name'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'Is Active'
        )->addColumn(
            'priority',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Priority'
        )->addColumn(
            'conditions_serialized',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Conditions Serialized'
        )->addColumn(
            'store_ids',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
            'Stores'
        )->addColumn(
            'customer_group_ids',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
            'Customer Groups'
        )->addColumn(
            'cancel_condition',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
            'Cancel Condition'
        )->addColumn(
            'utm_source',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Utm Source'
        )->addColumn(
            'utm_medium',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Utm Medium'
        )->addColumn(
            'utm_term',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Utm Term'
        )->addColumn(
            'utm_content',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Utm Content'
        )->addColumn(
            'utm_campaign',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Utm Campaign'
        )->addIndex(
            $installer->getIdxName(
                'amasty_acart_rule',
                [
                    'name',
                    'utm_source',
                    'utm_medium',
                    'utm_term',
                    'utm_content',
                    'utm_campaign'
                ],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            [
                'name',
                'utm_source',
                'utm_medium',
                'utm_term',
                'utm_content',
                'utm_campaign'
            ],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Amasty Acart Rule Grid'
        );

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_acart_schedule')
        )->addColumn(
            'schedule_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Schedule Id'
        )->addColumn(
            'rule_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Rule ID'
        )->addColumn(
            'template_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Template ID'
        )->addColumn(
            'days',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Days'
        )->addColumn(
            'hours',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Hours'
        )->addColumn(
            'minutes',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Minutes'
        )->addColumn(
            'simple_action',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Simple Action'
        )->addColumn(
            'discount_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            [12, 4],
            ['nullable' => false, 'default' => '0.0000'],
            'Discount Amount'
        )->addColumn(
            'expired_in_days',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Expired In Days'
        )->addColumn(
            'discount_qty',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            [12, 4],
            [],
            'Discount Qty'
        )->addColumn(
            'discount_step',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Discount Step'
        )->addColumn(
            'subtotal_is_greater_than',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Subtotal Is Greater Than'
        )->addColumn(
            'use_shopping_cart_rule',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            ['default' => false, 'nullable' => false],
            'Use Shopping Cart Rule'
        )->addColumn(
            'sales_rule_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Sales Rule ID'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => true],
            'Creation Date'
        )->addForeignKey(
            $installer->getFkName('amasty_acart_schedule', 'rule_id', 'amasty_acart_rule', 'rule_id'),
            'rule_id',
            $installer->getTable('amasty_acart_rule'),
            'rule_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('amasty_acart_schedule', 'template_id', 'email_template', 'template_id'),
            'template_id',
            $installer->getTable('email_template'),
            'template_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('amasty_acart_schedule', 'sales_rule_id', 'salesrule', 'rule_id'),
            'sales_rule_id',
            $installer->getTable('salesrule'),
            'rule_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        );

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_acart_rule_quote')
        )->addColumn(
            'rule_quote_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Rule Quote Id'
        )->addColumn(
            'quote_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Quote ID'
        )->addColumn(
            'rule_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Rule ID'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Status'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => true, 'default' => '0'],
            'Store Id'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true],
            'Customer Id'
        )->addColumn(
            'customer_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Customer Email'
        )->addColumn(
            'customer_firstname',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Customer Firstname'
        )->addColumn(
            'customer_lastname',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Customer Lastname'
        )->addColumn(
            'test_mode',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '0'],
            'Test Mode'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => true],
            'Creation Date'
        )->addForeignKey(
            $installer->getFkName('amasty_acart_rule_quote', 'rule_id', 'amasty_acart_rule', 'rule_id'),
            'rule_id',
            $installer->getTable('amasty_acart_rule'),
            'rule_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->addForeignKey(
            $installer->getFkName('amasty_acart_rule_quote', 'quote_id', 'quote', 'entity_id'),
            'quote_id',
            $installer->getTable('quote'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->addForeignKey(
            $installer->getFkName('amasty_acart_rule_quote', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->addIndex(
            $installer->getIdxName(
                'amasty_acart_rule_quote',
                ['status'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            ),
            ['status'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
        )->addIndex(
            $installer->getIdxName(
                'amasty_acart_rule_quote',
                ['customer_email'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            ),
            ['customer_email'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
        );

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_acart_history')
        )->addColumn(
            'history_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'History Id'
        )->addColumn(
            'rule_quote_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Rule Quote ID'
        )->addColumn(
            'schedule_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Schedule ID'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Status'
        )->addColumn(
            'public_key',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Public Key'
        )->addColumn(
            'email_subject',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Email Subject'
        )->addColumn(
            'email_body',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '1M',
            [],
            'Email Body'
        )->addColumn(
            'sales_rule_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Sales Rule ID'
        )->addColumn(
            'sales_rule_coupon_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Sales Rule Coupon ID'
        )->addColumn(
            'sales_rule_coupon',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Sales Rule Coupon'
        )->addColumn(
            'scheduled_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => true],
            'Dcheduled Date'
        )->addColumn(
            'executed_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => true],
            'Executed Date'
        )->addColumn(
            'finished_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => true],
            'Finished Date'
        )->addForeignKey(
            $installer->getFkName('amasty_acart_history', 'rule_quote_id', 'amasty_acart_rule_quote', 'rule_quote_id'),
            'rule_quote_id',
            $installer->getTable('amasty_acart_rule_quote'),
            'rule_quote_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('amasty_acart_history', 'sales_rule_id', 'salesrule', 'rule_id'),
            'sales_rule_id',
            $installer->getTable('salesrule'),
            'rule_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->addForeignKey(
            $installer->getFkName('amasty_acart_history', 'sales_rule_coupon_id', 'salesrule_coupon', 'rule_id'),
            'sales_rule_coupon_id',
            $installer->getTable('salesrule_coupon'),
            'coupon_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->addForeignKey(
            $installer->getFkName('amasty_acart_history', 'schedule_id', 'amasty_acart_schedule', 'schedule_id'),
            'schedule_id',
            $installer->getTable('amasty_acart_schedule'),
            'schedule_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->addIndex(
            $installer->getIdxName(
                'amasty_acart_history',
                ['status'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            ),
            ['status'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
        );

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_acart_quote_email')
        )->addColumn(
            'quote_email_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Quote Email Id'
        )->addColumn(
            'quote_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Quote ID'
        )->addColumn(
            'customer_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Customer Email'
        )->addForeignKey(
            $installer->getFkName('amasty_acart_quote_email', 'quote_id', 'quote', 'entity_id'),
            'quote_id',
            $installer->getTable('quote'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addIndex(
            $installer->getIdxName(
                'amasty_acart_quote_email',
                ['quote_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['quote_id'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        );

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_acart_blacklist')
        )->addColumn(
            'blacklist_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Blacklist Id'
        )->addColumn(
            'customer_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Customer Email'
        )->addIndex(
            $installer->getIdxName(
                'amasty_acart_blacklist',
                ['customer_email'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['customer_email'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        );

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
