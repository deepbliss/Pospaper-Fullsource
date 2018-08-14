<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'aw_fslabel_label'
         */
        $labelTable = $installer->getConnection()->newTable($installer->getTable('aw_fslabel_label'))->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'is_enabled',
            Table::TYPE_SMALLINT,
            1,
            ['nullable' => false],
            'Is enabled'
        )->addColumn(
            'goal',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Free Shipping Goal'
        )->addColumn(
            'page_type',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Page Types'
        )->addColumn(
            'position',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Position'
        )->addColumn(
            'delay',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Delay'
        )->addColumn(
            'font_name',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Font name'
        )->addColumn(
            'font_size',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Font size'
        )->addColumn(
            'font_weight',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Font weight'
        )->addColumn(
            'font_color',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Font color'
        )->addColumn(
            'goal_font_color',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Goal ont color'
        )->addColumn(
            'background_color',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Background color'
        )->addColumn(
            'text_align',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Text align'
        )->addColumn(
            'custom_css',
            Table::TYPE_TEXT,
            null,
            [],
            'Custom css'
        );
        $installer->getConnection()->createTable($labelTable);

        /**
         * Create table 'aw_fslabel_label_customer_group'
         */
        $customerGroupTable = $installer->getConnection()
            ->newTable(
                $installer->getTable('aw_fslabel_label_customer_group')
            )->addColumn(
                'label_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Label ID'
            )->addColumn(
                'customer_group_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Customer Group ID'
            )->addIndex(
                $installer->getIdxName('aw_fslabel_label_customer_group', ['label_id']),
                ['label_id']
            )->addIndex(
                $installer->getIdxName('aw_fslabel_label_customer_group', ['customer_group_id']),
                ['customer_group_id']
            )->addForeignKey(
                $installer->getFkName('aw_fslabel_label_customer_group', 'label_id', 'aw_fslabel_label', 'id'),
                'label_id',
                $installer->getTable('aw_fslabel_label'),
                'id',
                Table::ACTION_CASCADE
            )->setComment(
                'AW Fslabel Label To Customer Group Relation Table'
            );
        $installer->getConnection()->createTable($customerGroupTable);

        /**
         * Create table 'aw_fslabel_label_content'
         */
        $contentTable = $installer->getConnection()
            ->newTable(
                $installer->getTable('aw_fslabel_label_content')
            )->addColumn(
                'label_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Label ID'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Store Id'
            )->addColumn(
                'content_type',
                Table::TYPE_TEXT,
                255,
                ['unsigned' => true, 'nullable' => false],
                'Content type'
            )->addColumn(
                'message',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Message'
            )->addIndex(
                $installer->getIdxName('aw_fslabel_label_content', ['label_id']),
                ['label_id']
            )->addIndex(
                $installer->getIdxName('aw_fslabel_label_content', ['store_id']),
                ['store_id']
            )->addForeignKey(
                $installer->getFkName('aw_fslabel_label_content', 'label_id', 'aw_fslabel_label', 'id'),
                'label_id',
                $installer->getTable('aw_fslabel_label'),
                'id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName('aw_fslabel_label_content', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment(
                'AW Fslabel Label Content'
            );
        $installer->getConnection()->createTable($contentTable);

        $installer->endSetup();
    }
}
