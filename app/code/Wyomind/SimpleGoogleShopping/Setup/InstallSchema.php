<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Install schema for Simple Google Shopping
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * @version 10.0.2
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    )
    {
        unset($context);

        $installer = $setup;
        $installer->startSetup();

        $installer->getConnection()->dropTable($installer->getTable('simplegoogleshopping_feeds')); // drop if exists
        $installer->getConnection()->dropTable($installer->getTable('simplegoogleshopping_functions'));

        $simplegoogleshoppingTable = $installer->getConnection()
            ->newTable($installer->getTable('simplegoogleshopping_feeds'))
            ->addColumn(
                'simplegoogleshopping_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Data Feed ID'
            )
            ->addColumn(
                'simplegoogleshopping_filename',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => 'simple'],
                'Data Feed Filename'
            )
            ->addColumn(
                'simplegoogleshopping_path',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => 'simple'],
                'Data Feed File path'
            )
            ->addColumn(
                'simplegoogleshopping_time',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Data Feed Last Update Time'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Data Feed Associated Store ID'
            )
            ->addColumn(
                'simplegoogleshopping_url',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                120,
                ['nullable' => true, 'default' => 'simple'],
                'Data Feed Website Url'
            )
            ->addColumn(
                'simplegoogleshopping_title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Data Feed Title'
            )
            ->addColumn(
                'simplegoogleshopping_description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Data Feed Description'
            )
            ->addColumn(
                'simplegoogleshopping_xmlitempattern',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Data Feed XML Item Pattern'
            )
            ->addColumn(
                'simplegoogleshopping_categories',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Data Feed Categories Selection'
            )
            ->addColumn(
                'simplegoogleshopping_category_filter',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Data Feed Categories Inclusion Type'
            )
            ->addColumn(
                'simplegoogleshopping_category_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Data Feed Categories Filter (product/parent)'
            )
            ->addColumn(
                'simplegoogleshopping_type_ids',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                150,
                [],
                'Data Feed Product Types Selection'
            )
            ->addColumn(
                'simplegoogleshopping_visibility',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                150,
                [],
                'Data Feed Product Visibilities Selection'
            )
            ->addColumn(
                'simplegoogleshopping_attribute_sets',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                250,
                ['default' => '*'],
                'Data Feed Attribute Sets Selection'
            )
            ->addColumn(
                'simplegoogleshopping_attributes',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Data Feed Advanced Filters'
            )
            ->addColumn(
                'simplegoogleshopping_report',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                4000,
                [],
                'Data Feed Last Report'
            )
            ->addColumn(
                'cron_expr',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                900,
                [],
                'Data Feed Schedule Task'
            )
            ->addColumn(
                'simplegoogleshopping_feed_taxonomy',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                150,
                ['default' => '[default] en_US.txt'],
                'Data Feed Taxonomies File'
            )
            ->addIndex(
                $installer->getIdxName('simplegoogleshopping_feeds', ['simplegoogleshopping_id']),
                ['simplegoogleshopping_id']
            )
            ->setComment('Simple Google Shopping Data Feeds Table');

        $installer->getConnection()->createTable($simplegoogleshoppingTable);


        $simplegoogleshoppingFunctionsTable = $installer->getConnection()
            ->newTable($installer->getTable('simplegoogleshopping_functions'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Data Feed ID'
            )
            ->addColumn(
                'script',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Custom Function Script'
            )
            ->addIndex(
                $installer->getIdxName('simplegoogleshopping_functions', ['id']),
                ['id']
            )
            ->setComment('Simple Google Shopping Custom Functions Table');

        $installer->getConnection()->createTable($simplegoogleshoppingFunctionsTable);


        $installer->endSetup();
    }
}
