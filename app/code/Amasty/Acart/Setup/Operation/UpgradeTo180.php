<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Setup\Operation;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table as DdlTable;
use Amasty\Acart\Model\ResourceModel\RuleQuote;

class UpgradeTo180
{
    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable(RuleQuote::MAIN_TABLE);
        $connection = $setup->getConnection();

        $connection->addColumn(
            $table,
            'abandoned_status',
            [
                'type' => DdlTable::TYPE_TEXT,
                'nullable' => false,
                'length' => 25,
                'default' => \Amasty\Acart\Model\RuleQuote::ABANDONED_NOT_RESTORED_STATUS,
                'comment' => 'Abandoned Cart Status'
            ]
        );
    }
}
