<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class UpgradeSchema
 * @package Amasty\Base\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.4.0', '<')) {
            $this->addIsAmastyField($setup);
        }

        if (version_compare($context->getVersion(), '1.4.2', '<')) {
            $this->addExpireField($setup);
        }

        if (version_compare($context->getVersion(), '1.6.2', '<')) {
            $this->addImageUrlField($setup);
        }
    }

    private function addIsAmastyField(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('adminnotification_inbox'),
            'is_amasty',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => 0],
            'Is Amasty Notification'
        );
    }

    private function addExpireField(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('adminnotification_inbox'),
            'expiration_date',
            Table::TYPE_DATETIME,
            null,
            ['nullable' => false],
            'Expiration Date'
        );
    }

    private function addImageUrlField(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('adminnotification_inbox'),
            'image_url',
            Table::TYPE_TEXT,
            null,
            ['nullable' => true, 'default' => null],
            'Image Url'
        );
    }
}
