<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Setup;

/**
 * DB setup script for Subscriptions
 */
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * DB setup code
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $this->createSubscriptionTable($setup);
        $this->createLogTable($setup);

        $this->createProductIntervalTable($setup);
    }

    /**
     * Create table 'paradoxlabs_subscription'
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @return void
     */
    public function createSubscriptionTable(\Magento\Framework\Setup\SchemaSetupInterface $setup)
    {
        $quoteDb = $setup->getConnection('checkout');

        $table = $quoteDb->newTable(
            $setup->getTable('paradoxlabs_subscription')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'increment_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Subscription Increment ID'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Creation Time'
        )->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Updated Time'
        )->addColumn(
            'last_run',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Last Run'
        )->addColumn(
            'next_run',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Next Run'
        )->addColumn(
            'last_notified',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['nullable' => false],
            'Last Notified'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Status'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Store ID'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Customer ID'
        )->addColumn(
            'quote_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Source Quote ID'
        )->addColumn(
            'frequency_count',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Billing Frequency (count)'
        )->addColumn(
            'frequency_unit',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Billing Frequency (unit)'
        )->addColumn(
            'length',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '0'],
            'Billing Length'
        )->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Description'
        )->addColumn(
            'subtotal',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            [],
            'Subtotal (base currency)'
        )->addColumn(
            'run_count',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '0'],
            'Run Count'
        )->addColumn(
            'additional_information',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Additional Info'
        )->addColumn(
            'keyword_fulltext',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Fulltext data for keyword search'
        )->addIndex(
            $setup->getIdxName('paradoxlabs_subscription', ['status', 'next_run']),
            ['status', 'next_run']
        )->addIndex(
            $setup->getIdxName(
                'paradoxlabs_subscription',
                [
                    'increment_id',
                    'description',
                    'status',
                    'frequency_unit',
                    'additional_information',
                    'keyword_fulltext',
                ]
            ),
            [
                'increment_id',
                'description',
                'status',
                'frequency_unit',
                'additional_information',
                'keyword_fulltext',
            ],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->addForeignKey(
            $setup->getFkName('paradoxlabs_subscription', 'quote_id', 'quote', 'entity_id'),
            'quote_id',
            $setup->getTable('quote'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Subscriptions for ParadoxLabs_Subscriptions'
        );

        $quoteDb->createTable($table);
    }

    /**
     * Create table 'paradoxlabs_subscription_log'
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @return void
     */
    public function createLogTable(\Magento\Framework\Setup\SchemaSetupInterface $setup)
    {
        $quoteDb = $setup->getConnection('checkout');

        $table = $quoteDb->newTable(
            $setup->getTable('paradoxlabs_subscription_log')
        )->addColumn(
            'log_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Log ID'
        )->addColumn(
            'subscription_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Subscription ID'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Creation Time'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Status'
        )->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true],
            'Order ID'
        )->addColumn(
            'order_increment_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Order Increment ID'
        )->addColumn(
            'agent_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Agent ID'
        )->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Description'
        )->addColumn(
            'additional_information',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Additional Info'
        )->addIndex(
            $setup->getIdxName('paradoxlabs_subscription_log', ['subscription_id']),
            ['subscription_id']
        )->addIndex(
            $setup->getIdxName(
                'paradoxlabs_subscription_log',
                ['description', 'status', 'order_increment_id', 'additional_information']
            ),
            ['description', 'status', 'order_increment_id', 'additional_information'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->addForeignKey(
            $setup->getFkName(
                'paradoxlabs_subscription_log',
                'subscription_id',
                'paradoxlabs_subscription',
                'entity_id'
            ),
            'subscription_id',
            $setup->getTable('paradoxlabs_subscription'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Log/history for ParadoxLabs_Subscriptions'
        );

        $quoteDb->createTable($table);
    }

    /**
     * Create table 'paradoxlabs_subscription_product_interval' for storing product custom option <=> interval data.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @return void
     */
    public function createProductIntervalTable(\Magento\Framework\Setup\SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable(
            $setup->getTable('paradoxlabs_subscription_product_interval')
        )->addColumn(
            'interval_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Interval ID'
        )->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Product ID'
        )->addColumn(
            'option_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Option ID'
        )->addColumn(
            'value_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Value ID'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Store ID'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Creation Time'
        )->addColumn(
            'frequency_count',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Billing Frequency (count)'
        )->addColumn(
            'frequency_unit',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            [],
            'Billing Frequency (unit)'
        )->addColumn(
            'length',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Billing Length'
        )->addColumn(
            'installment_price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            [],
            'Installment price'
        )->addColumn(
            'adjustment_price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            [],
            'Adjustment price'
        )->addColumn(
            'additional_information',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Additional Info'
        )->addIndex(
            $setup->getIdxName('paradoxlabs_subscription_product_interval', ['product_id']),
            ['product_id']
        )->addIndex(
            $setup->getIdxName('paradoxlabs_subscription_product_interval', ['option_id']),
            ['option_id']
        )->setComment(
            'Product intervals record for ParadoxLabs_Subscriptions'
        );

        $setup->getConnection()->createTable($table);
    }
}
