<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 */

namespace ParadoxLabs\Subscriptions\Setup;

/**
 * DB upgrade script for Subscriptions
 */
class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    /**
     * DB upgrade code
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     */
    public function upgrade(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        /** @var \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb */
        $quoteDb = $setup->getConnection('checkout');

        $this->addOrderIdToLogs($setup, $quoteDb);

        $this->createProductIntervalTable($setup);

        $this->addIncrementIdToSubscriptions($setup, $quoteDb);
        $this->addLastNotifiedToSubscriptions($setup, $quoteDb);
        $this->addKeywordFulltextToSubscriptions($setup, $quoteDb);

        $this->addFulltextIdxToSubs($setup, $quoteDb);
        $this->addFulltextIdxToLogs($setup, $quoteDb);
    }

    /**
     * Add order_id column to the logs table and fill values.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
     * @return void
     */
    protected function addOrderIdToLogs(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
    ) {
        // Short-circuit if column already exists.
        $orderIdExists = $quoteDb->tableColumnExists($setup->getTable('paradoxlabs_subscription_log'), 'order_id');
        if ($orderIdExists === true) {
            return;
        }

        /**
         * Add order_id column
         */
        $quoteDb->addColumn(
            $setup->getTable('paradoxlabs_subscription_log'),
            'order_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'comment' => 'Order ID',
                'unsigned' => true,
                'nullable' => true,
            ]
        );

        /**
         * Fill in order_id values for existing logs
         *
         * Note: We're ignoring split DBs (Assuming log and order will be in same), because that's necessarily
         * true for any version where the order_id column doesn't exist yet. so.
         */
        $order = $setup->getTable('sales_order');
        $log = $setup->getTable('paradoxlabs_subscription_log');

        // Running a manual update because this is way faster than looping updates.
        $setup->getConnection()->query(
            "UPDATE {$log} log, {$order} o SET log.order_id=o.entity_id
            WHERE log.order_id IS NULL AND o.increment_id=log.order_increment_id"
        );
    }

    /**
     * Add fulltext index to subscriptions table for admin "search by keyword" feature.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
     * @return void
     */
    protected function addFulltextIdxToSubs(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
    ) {
        $subsIndexes = $quoteDb->getIndexList($setup->getTable('paradoxlabs_subscription'));
        $subsIdxKey = $setup->getIdxName(
            'paradoxlabs_subscription',
            [
                'increment_id',
                'description',
                'status',
                'frequency_unit',
                'additional_information',
                'keyword_fulltext',
            ]
        );

        // Add fulltext index if it doesn't exist.
        if (!isset($subsIndexes[$subsIdxKey])) {
            $quoteDb->addIndex(
                $setup->getTable('paradoxlabs_subscription'),
                $subsIdxKey,
                [
                    'increment_id',
                    'description',
                    'status',
                    'frequency_unit',
                    'additional_information',
                    'keyword_fulltext',
                ],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
    }

    /**
     * Add fulltext index to logs table for admin "search by keyword" feature.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
     * @return void
     */
    protected function addFulltextIdxToLogs(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
    ) {
        $subsIndexes = $quoteDb->getIndexList($setup->getTable('paradoxlabs_subscription_log'));
        $subsIdxKey = $setup->getIdxName(
            'paradoxlabs_subscription_log',
            ['description', 'status', 'order_increment_id', 'additional_information']
        );

        // Add fulltext index if it doesn't exist.
        if (!isset($subsIndexes[$subsIdxKey])) {
            $quoteDb->addIndex(
                $setup->getTable('paradoxlabs_subscription_log'),
                $subsIdxKey,
                ['description', 'status', 'order_increment_id', 'additional_information'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
    }

    /**
     * Create table 'paradoxlabs_subscription_product_interval' for storing product custom option <=> interval data.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @return void
     */
    protected function createProductIntervalTable(\Magento\Framework\Setup\SchemaSetupInterface $setup)
    {
        // Short-circuit if table already exists.
        $intervalTableExists = $setup->getConnection()->isTableExists(
            $setup->getTable('paradoxlabs_subscription_product_interval')
        );
        if ($intervalTableExists === true) {
            return;
        }

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

    /**
     * Add 'increment_id' column to paradoxlabs_subscription. This is a reference ID distinct from entity_id.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
     * @return void
     */
    protected function addIncrementIdToSubscriptions(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
    ) {
        // Short-circuit if column already exists.
        $incrementIdExists = $quoteDb->tableColumnExists(
            $setup->getTable('paradoxlabs_subscription'),
            'increment_id'
        );
        if ($incrementIdExists === true) {
            return;
        }

        /**
         * Add increment_id column
         */
        $quoteDb->addColumn(
            $setup->getTable('paradoxlabs_subscription'),
            'increment_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 32,
                'comment' => 'Subscription Increment ID',
                'after' => 'entity_id',
            ]
        );

        /**
         * Populate increment_id column.
         */
        $select = $quoteDb->select();
        $select->from(
            [
                'subscription' => $setup->getTable('paradoxlabs_subscription'),
            ],
            [
                'increment_id' => 'entity_id',
            ]
        );
        $select->where('update.entity_id=subscription.entity_id');
        $select->where('update.increment_id is null');

        $update = $quoteDb->updateFromSelect(
            $select,
            [
                'update' => $setup->getTable('paradoxlabs_subscription'),
            ]
        );

        $quoteDb->query($update);
    }

    /**
     * Add 'last_notified' column to paradoxlabs_subscription. This is used for pre-bill notification tracking.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
     * @return void
     */
    protected function addLastNotifiedToSubscriptions(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
    ) {
        // Short-circuit if column already exists.
        $lastNotifiedExists = $quoteDb->tableColumnExists(
            $setup->getTable('paradoxlabs_subscription'),
            'last_notified'
        );
        if ($lastNotifiedExists === true) {
            return;
        }

        /**
         * Add last_notified column
         */
        $quoteDb->addColumn(
            $setup->getTable('paradoxlabs_subscription'),
            'last_notified',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                'comment' => 'Last Notified',
                'nullable' => false,
                'after' => 'next_run',
            ]
        );
    }

    /**
     * Add 'keyword_fulltext' column to paradoxlabs_subscription. This is used as datastore to drive the 'keyword
     * search' feature on the admin grid.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
     * @return void
     */
    protected function addKeywordFulltextToSubscriptions(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
    ) {
        // Short-circuit if column already exists.
        $keywordFulltextExists = $quoteDb->tableColumnExists(
            $setup->getTable('paradoxlabs_subscription'),
            'keyword_fulltext'
        );
        if ($keywordFulltextExists === true) {
            return;
        }

        /**
         * Add keyword_fulltext column
         */
        $quoteDb->addColumn(
            $setup->getTable('paradoxlabs_subscription'),
            'keyword_fulltext',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'Fulltext data for keyword search',
                'length' => '2M',
                'after' => 'additional_information',
            ]
        );

        /**
         * Fill in the column data
         *
         * We need it to contain everything that should be searchable but isn't directly accessible on the
         * subscription table. That means customer name and email from quote, and order IDs from logs. This is...messy.
         * But we can do it in one query! Go team.
         */
        $quoteDb->query(
            sprintf(
                'UPDATE %s s
                        JOIN %s q ON q.entity_id=s.quote_id
                        SET s.keyword_fulltext = concat_ws(
                            " ",
                            q.customer_firstname,
                            q.customer_lastname,
                            q.customer_email,
                            s.increment_id,
                            (SELECT group_concat(log.order_increment_id separator " ")
                            FROM %s log
                            WHERE log.subscription_id=s.entity_id AND log.order_increment_id IS NOT NULL
                            GROUP BY log.subscription_id)
                        )
                        WHERE s.keyword_fulltext IS NULL',
                $setup->getTable('paradoxlabs_subscription'),
                $setup->getTable('quote'),
                $setup->getTable('paradoxlabs_subscription_log')
            )
        );
    }
}
