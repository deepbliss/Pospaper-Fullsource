<?php
/**
 * Copyright © 2015 Pos. All rights reserved.
 */

namespace Pos\Manufacturer\Setup;

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

		/**
         * Create table 'manufacturer_manufacturer'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('manufacturer_manufacturer')
        )
		->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'manufacturer_manufacturer'
        )
		->addColumn(
            'manufacturer',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'manufacturer'
        )
		->addColumn(
            'model',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'model'
        )
		->addColumn(
            'product_ids',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'product_ids'
        )
		/*{{CedAddTableColumn}}}*/
		
		
        ->setComment(
            'Pos Manufacturer manufacturer_manufacturer'
        );
		
		$installer->getConnection()->createTable($table);
		/*{{CedAddTable}}*/

        $installer->endSetup();

    }
}
