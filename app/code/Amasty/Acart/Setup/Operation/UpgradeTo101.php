<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Setup\Operation;

use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeTo101
{
    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()
            ->newTable($setup->getTable('amasty_acart_attribute'))
            ->addColumn(
                'attr_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Attribute Id'
            )
            ->addColumn(
                'rule_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false,],
                'Rule Id'
            )
            ->addColumn(
                'code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Email'
            )
            ->addForeignKey(
                $setup->getFkName('amasty_acart_attribute', 'rule_id', 'amasty_acart_rule', 'rule_id'),
                'rule_id',
                $setup->getTable('amasty_acart_rule'),
                'rule_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Amasty Acart Attribute');

        $setup->getConnection()->createTable($table);
    }
}
