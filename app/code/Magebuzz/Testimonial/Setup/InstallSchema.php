<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */

namespace Magebuzz\Testimonial\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('mb_testimonial'))
            ->addColumn(
                'mb_testimonial_id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Testimonial ID'
            )
            ->addColumn('name', Table::TYPE_TEXT, 100, ['nullable' => true, 'default' => null])
            ->addColumn('email', Table::TYPE_TEXT, 255, ['nullable' => false], 'Testimonial Email')
            ->addColumn('avatar', Table::TYPE_TEXT, 255, ['nullable' => false], 'Testimonial Avatar')
            ->addColumn('website', Table::TYPE_TEXT, 255, [], 'Testimonial Website')
            ->addColumn('company', Table::TYPE_TEXT, 255, [], 'Testimonial Company')
            ->addColumn('address', Table::TYPE_TEXT, 255, [], 'Testimonial Address')
            ->addColumn('testimonial', Table::TYPE_TEXT, '64k', [], 'Testimonial Testimonial')
            ->addColumn('created_time', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default'=> Table::TIMESTAMP_INIT], 'Testimonial Creation Time')
            ->addColumn('updated_time', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default'   => Table::TIMESTAMP_INIT_UPDATE], 'Testimonial Update Time')
            ->addColumn('is_active', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Is Testimonial Pending?')
            ->addIndex($installer->getIdxName('table', ['website']), ['website'])
            ->setComment('Magebuzz Team Testimonials');

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }

}
