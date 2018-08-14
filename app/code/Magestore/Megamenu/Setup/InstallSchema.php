<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Megamenu
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Megamenu\Setup;

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
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $installer->getConnection()->dropTable($installer->getTable('magestore_megamenu_megamenu'));

        /**
         * Create table 'magestore_megamenu_megamenu'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('magestore_megamenu_megamenu'))
            ->addColumn(
                'megamenu_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Megamenu Id'
            )->addColumn(
                'name_menu',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false , 'default'=>''],
                'Name Menu'
            )->addColumn(
                'stores',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Stores'
            )->addColumn(
                'link',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Link'
            )->addColumn(
                'sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => true],
                'Sort Order'
            )->addColumn(
                'colum',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => true],
                'Colum'
            )->addColumn(
                'item_icon',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Item Icon'
            )->addColumn(
                'megamenu_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                3,
                ['nullable' => false, 'default'=>'0'],
                'Megamenu Type'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['nullable' => false, 'default'=>'0'],
                'Status'
            )->addColumn(
                'menu_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false, 'default'=>'6'],
                'Menu Type'
            )->addColumn(
                'products',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Products'
            )->addColumn(
                'products_using_label',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Products Using Label'
            )->addColumn(
                'product_label',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Product Label'
            )->addColumn(
                'product_label_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Product Label Color'
            )->addColumn(
                'categories',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Categories'
            )->addColumn(
                'products_box_title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Products Box Title'
            )->addColumn(
                'categories_box_title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Categories Box Title'
            )->addColumn(
                'header',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Header'
            )->addColumn(
                'footer',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Footer'
            )->addColumn(
                'featured_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                3,
                ['nullable' => false, 'default'=>'0'],
                'Featured Type'
            )->addColumn(
                'featured_products',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Featured Products'
            )->addColumn(
                'featured_categories',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Featured Categories'
            )->addColumn(
                'featured_title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Featured Title'
            )->addColumn(
                'featured_width',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                3,
                ['nullable' => false, 'default'=>'30'],
                'Featured Title'
            )->addColumn(
                'submenu_width',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                3,
                ['nullable' => false, 'default'=>'100'],
                'Submenu Width'
            )->addColumn(
                'submenu_align',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                3,
                ['nullable' => false, 'default'=>'0'],
                'Submenu Align'
            )->addColumn(
                'leftsubmenu_align',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                3,
                ['nullable' => false, 'default'=>'0'],
                'Leftsubmenu Align'
            )->addColumn(
                'category_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                3,
                ['nullable' => false, 'default'=>'0'],
                'Category Type'
            )->addColumn(
                'view_all',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                3,
                ['nullable' => false, 'default'=>'0'],
                'View_all'
            )->addColumn(
                'featured_content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Featured Content'
            )->addColumn(
                'main_content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Main Content'
            )->addColumn(
                'featured_column',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                3,
                ['nullable' => false, 'default'=>'0'],
                'Main Content'
            )->addColumn(
                'category_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                3,
                ['nullable' => false, 'default'=>'0'],
                'Category Image'
            )->addColumn(
                'number_products',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false, 'default'=>'20'],
                'Number Products'
            )
            ->setComment('Megamenu entities');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
