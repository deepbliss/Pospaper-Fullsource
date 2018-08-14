<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Aheadworks\RewardPoints\Model\Source\NotifiedStatus;
use Aheadworks\RewardPoints\Model\Source\SubscribeStatus;
use Aheadworks\RewardPoints\Model\Source\Transaction\Type;
use Aheadworks\RewardPoints\Model\Source\Transaction\Status;

/**
 * Class \Aheadworks\RewardPoints\Setup\InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for the Aheadworks_RewardPoints module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('aw_rp_earn_rate'))
            ->addColumn(
                'rate_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Primary Index Id'
            )
            ->addColumn(
                'website_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Website Id'
            )
            ->addColumn(
                'customer_group_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Customer Group Id'
            )
            ->addColumn(
                'lifetime_sales_amount',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Customer lifetime sales amount'
            )
            ->addColumn(
                'base_amount',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Base Currency'
            )
            ->addColumn(
                'points',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Points'
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_earn_rate', ['website_id']),
                ['website_id']
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_earn_rate', ['customer_group_id']),
                ['customer_group_id']
            )
            ->addIndex(
                $installer->getIdxName(
                    'aw_rp_earn_rate',
                    [
                        'website_id',
                        'customer_group_id',
                        'lifetime_sales_amount'
                    ]
                ),
                ['website_id', 'customer_group_id', 'lifetime_sales_amount'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_earn_rate', ['website_id', 'customer_group_id']),
                ['website_id', 'customer_group_id']
            )
            ->addForeignKey(
                $installer->getFkName('aw_rp_earn_rate', 'website_id', 'store_website', 'website_id'),
                'website_id',
                $installer->getTable('store_website'),
                'website_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Aheadworks Reward Points Earn Rate Table');

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('aw_rp_product_share'))
            ->addColumn(
                'share_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Primary Index Id'
            )
            ->addColumn(
                'website_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Website Id'
            )
            ->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Customer Id'
            )
            ->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Product Id'
            )
            ->addColumn(
                'network',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                16,
                [],
                'Share Network'
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_product_share', ['website_id']),
                ['website_id']
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_product_share', ['customer_id']),
                ['customer_id']
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_product_share', ['product_id']),
                ['product_id']
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_product_share', ['network']),
                ['network']
            )
            ->addForeignKey(
                $installer->getFkName('aw_rp_product_share', 'website_id', 'store_website', 'website_id'),
                'website_id',
                $installer->getTable('store_website'),
                'website_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('aw_rp_product_share', 'customer_id', 'customer_entity', 'entity_id'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('aw_rp_product_share', 'product_id', 'catalog_product_entity', 'entity_id'),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Aheadworks Reward Points Product Share Table');

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('aw_rp_spend_rate'))
            ->addColumn(
                'rate_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Primary Index Id'
            )
            ->addColumn(
                'website_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Website Id'
            )
            ->addColumn(
                'customer_group_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Customer Group Id'
            )
            ->addColumn(
                'lifetime_sales_amount',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Customer lifetime sales amount'
            )
            ->addColumn(
                'points',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Points'
            )
            ->addColumn(
                'base_amount',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Base Currency'
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_spend_rate', ['website_id']),
                ['website_id']
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_spend_rate', ['customer_group_id']),
                ['customer_group_id']
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_spend_rate', ['website_id', 'customer_group_id']),
                ['website_id', 'customer_group_id']
            )
            ->addIndex(
                $installer->getIdxName(
                    'aw_rp_spend_rate',
                    [
                        'website_id',
                        'customer_group_id',
                        'lifetime_sales_amount',
                    ]
                ),
                ['website_id', 'customer_group_id', 'lifetime_sales_amount'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addForeignKey(
                $installer->getFkName('aw_rp_spend_rate', 'website_id', 'store_website', 'website_id'),
                'website_id',
                $installer->getTable('store_website'),
                'website_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Aheadworks Reward Points Spend Rate Table');

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('aw_rp_transaction'))
            ->addColumn(
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Primary Index Id'
            )
            ->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Customer Id'
            )
            ->addColumn(
                'customer_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Customer Name'
            )
            ->addColumn(
                'customer_email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Customer Email'
            )
            ->addColumn(
                'comment_to_customer',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Customer Comments'
            )
            ->addColumn(
                'comment_to_customer_placeholder',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Comment To Customer Placeholder'
            )
            ->addColumn(
                'comment_to_admin',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Admin Comments'
            )
            ->addColumn(
                'balance',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Customer Balance'
            )
            ->addColumn(
                'current_balance',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true, 'default' => 0],
                'Current Customer Balance'
            )
            ->addColumn(
                'transaction_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Transaction Date'
            )
            ->addColumn(
                'expiration_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true],
                'Expiration Date'
            )
            ->addColumn(
                'website_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Website Id'
            )
            ->addColumn(
                'type',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true, 'default' => Type::BALANCE_ADJUSTED_BY_ADMIN],
                'Transaction Type'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true, 'default' => Status::ACTIVE],
                'Transaction Status'
            )
            ->addColumn(
                'balance_update_notified',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true, 'default' => NotifiedStatus::NO],
                'Balance Update Notified'
            )
            ->addColumn(
                'expiration_notified',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true, 'default' => NotifiedStatus::WAITING],
                'Expiration Notified'
            )
            ->addColumn(
                'created_by',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => true, 'unsigned' => true],
                'Created By'
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_transaction', ['customer_id']),
                ['customer_id']
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_transaction', ['website_id']),
                ['website_id']
            )
            ->addForeignKey(
                $installer->getFkName('aw_rp_transaction', 'customer_id', 'customer_entity', 'entity_id'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('aw_rp_transaction', 'website_id', 'store_website', 'website_id'),
                'website_id',
                $installer->getTable('store_website'),
                'website_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Aheadworks Reward Points Transaction Table');

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('aw_rp_points_summary'))
            ->addColumn(
                'summary_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Primary Index Id'
            )
            ->addColumn(
                'website_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Website Id'
            )
            ->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Customer Id'
            )
            ->addColumn(
                'points',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Points'
            )
            ->addColumn(
                'points_earn',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => false, 'nullable' => false, 'default' => '0'],
                'Earn Points'
            )
            ->addColumn(
                'points_spend',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => false, 'nullable' => false, 'default' => '0'],
                'Spend Points'
            )
            ->addColumn(
                'daily_review_points',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => false, 'nullable' => false, 'default' => '0'],
                'Daily Reward Points for Review'
            )
            ->addColumn(
                'daily_review_points_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                ['nullable' => true],
                'Daily Reward Points for Review Date'
            )
            ->addColumn(
                'daily_share_points',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => false, 'nullable' => false, 'default' => '0'],
                'Daily Reward Points for Share'
            )
            ->addColumn(
                'monthly_share_points',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => false, 'nullable' => false, 'default' => '0'],
                'Monthly Reward Points for Share'
            )
            ->addColumn(
                'daily_share_points_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                ['nullable' => true],
                'Daily Reward Points for Share Date'
            )
            ->addColumn(
                'is_awarded_for_newsletter_signup',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                false,
                ['nullable' => false, 'default' => '0'],
                'Is Awarded for Newsletter Signup'
            )
            ->addColumn(
                'balance_update_notification_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true, 'default' => SubscribeStatus::SUBSCRIBED],
                'Balance Update Notifications (status)'
            )
            ->addColumn(
                'expiration_notification_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true, 'default' => SubscribeStatus::SUBSCRIBED],
                'Points Expiration Notification (status)'
            )
            ->addIndex(
                $installer->getIdxName('aw_rp_points_summary', ['website_id']),
                ['website_id']
            )
            ->addIndex(
                $installer->getIdxName(
                    'aw_rp_points_summary',
                    ['customer_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['customer_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addForeignKey(
                $installer->getFkName('aw_rp_points_summary', 'website_id', 'store_website', 'website_id'),
                'website_id',
                $installer->getTable('store_website'),
                'website_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('aw_rp_points_summary', 'customer_id', 'customer_entity', 'entity_id'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Aheadworks Reward Points Summary Table');

        $installer->getConnection()->createTable($table);

        /**
         * Create table 'aw_rp_transaction_entity'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('aw_rp_transaction_entity'))
            ->addColumn(
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Transaction Id'
            )->addColumn(
                'entity_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true, 'primary' => true],
                'Entity Type'
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true, 'primary' => true],
                'Entity Id'
            )->addColumn(
                'entity_label',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                ['nullable' => true],
                'Entity Label'
            )->addIndex(
                $installer->getIdxName('aw_rp_transaction_entity', ['transaction_id', 'entity_type', 'entity_id']),
                ['transaction_id', 'entity_type', 'entity_id']
            )->addForeignKey(
                $installer->getFkName(
                    'aw_rp_transaction_entity',
                    'transaction_id',
                    'aw_rp_transaction',
                    'transaction_id'
                ),
                'transaction_id',
                $installer->getTable('aw_rp_transaction'),
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment('Aheadworks Reward Points Transaction Entity');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'aw_rp_transaction_adjusted_history'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('aw_rp_transaction_adjusted_history'))
            ->addColumn(
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Transaction Id'
            )->addColumn(
                'from_transaction_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'primary' => true],
                'From Transaction Id'
            )->addColumn(
                'balance',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Balance'
            )->addIndex(
                $installer->getIdxName('aw_rp_transaction_adjusted_history', ['transaction_id', 'from_transaction_id']),
                ['transaction_id', 'from_transaction_id']
            )->addForeignKey(
                $installer->getFkName(
                    'aw_rp_transaction_adjusted_history',
                    'transaction_id',
                    'aw_rp_transaction',
                    'transaction_id'
                ),
                'transaction_id',
                $installer->getTable('aw_rp_transaction'),
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'aw_rp_transaction_adjusted_history',
                    'from_transaction_id',
                    'aw_rp_transaction',
                    'transaction_id'
                ),
                'from_transaction_id',
                $installer->getTable('aw_rp_transaction'),
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment('Aheadworks Reward Points Transaction Adjusted History');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
