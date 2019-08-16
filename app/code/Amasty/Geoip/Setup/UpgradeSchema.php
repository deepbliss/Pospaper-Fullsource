<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */


namespace Amasty\Geoip\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class UpgradeSchema
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $this->addIndexes($setup);
        }

        if (version_compare($context->getVersion(), '1.1.1', '<')) {
            $this->addCommonKey($setup);
        }

        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            $this->addRegion($setup);
        }

        if (version_compare($context->getVersion(), '1.3.2', '<')) {
            $this->addIndexBlock($setup);
        }

        if (version_compare($context->getVersion(), '1.4.0', '<')) {
            $this->addIpV6Table($setup);
        }

        $setup->endSetup();
    }

    protected function addIndexes(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addIndex(
            $setup->getTable('amasty_geoip_block'),
            $setup->getConnection()->getIndexName(
                $setup->getTable('amasty_geoip_block'),
                'start_ip_num',
                'index'
            ),
            'start_ip_num'
        );

        $setup->getConnection()->addIndex(
            $setup->getTable('amasty_geoip_block'),
            $setup->getConnection()->getIndexName(
                $setup->getTable('amasty_geoip_block'),
                'end_ip_num',
                'index'
            ),
            'end_ip_num'
        );

        $setup->getConnection()->addIndex(
            $setup->getTable('amasty_geoip_location'),
            $setup->getConnection()->getIndexName(
                $setup->getTable('amasty_geoip_location'),
                'geoip_loc_id',
                'index'
            ),
            'geoip_loc_id'
        );
    }

    protected function addCommonKey(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->dropIndex(
            $setup->getTable('amasty_geoip_block'),
            $setup->getConnection()->getIndexName(
                $setup->getTable('amasty_geoip_block'),
                'start_ip_num',
                'index'
            )
        );
        $setup->getConnection()->dropIndex(
            $setup->getTable('amasty_geoip_block'),
            $setup->getConnection()->getIndexName(
                $setup->getTable('amasty_geoip_block'),
                'end_ip_num',
                'index'
            )
        );

        $setup->getConnection()->addIndex(
            $setup->getTable('amasty_geoip_block'),
            $setup->getConnection()->getIndexName(
                $setup->getTable('amasty_geoip_block'),
                ['start_ip_num', 'end_ip_num'],
                'index'
            ),
            ['start_ip_num', 'end_ip_num']
        );
    }

    protected function addRegion(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('amasty_geoip_location'),
            'region',
            [
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Region'
            ]
        );
    }

    protected function addIndexBlock(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addIndex(
            $setup->getTable('amasty_geoip_block'),
            $setup->getConnection()->getIndexName(
                $setup->getTable('amasty_geoip_block'),
                'geoip_loc_id',
                'index'
            ),
            'geoip_loc_id'
        );
    }

    protected function addIpV6Table(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $table = $connection->newTable($setup->getTable('amasty_geoip_block_v6'))
            ->addColumn(
                'block_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Block Id'
            )
            ->addColumn(
                'start_ip_num',
                Table::TYPE_TEXT,
                40,
                ['unsigned' => true, 'nullable' => false],
                'Start Ip Num'
            )
            ->addColumn(
                'end_ip_num',
                Table::TYPE_TEXT,
                40,
                ['unsigned' => true, 'nullable' => false],
                'End Ip Num'
            )
            ->addColumn(
                'geoip_loc_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Geoip Loc Id'
            )
            ->addColumn(
                'postal_code',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Postal Code'
            )
            ->addColumn(
                'latitude',
                Table::TYPE_FLOAT,
                null,
                ['nullable' => true],
                'Latitude'
            )
            ->addColumn(
                'longitude',
                Table::TYPE_FLOAT,
                null,
                ['nullable' => true],
                'Longitude'
            )
            ->setComment('Amasty Geoip Block Table IpV6')
            ->setOption('type', 'MyISAM');

        $connection->createTable($table);
    }
}
