<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Setup\Operation;

use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeTo108
{
    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('amasty_acart_history');
        $connection = $setup->getConnection();

        $connection->addColumn(
            $table,
            'sales_rule_coupon_expiration_date',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                'nullable' => true,
                'comment' => 'Expiration Date'
            ]
        );
    }
}
