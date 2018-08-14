<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class UpgradeSchema
 *
 * @package Aheadworks\Freeshippinglabel\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if ($context->getVersion() && version_compare($context->getVersion(), '1.0.2', '<')) {
            $this->removeFkKeyFromCustomerGroupsTable($setup);
        }
    }

    /**
     * Drop foreign key from 'aw_fslabel_label_customer_group' table
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     */
    private function removeFkKeyFromCustomerGroupsTable(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();

        $connection->dropForeignKey(
            $setup->getTable('aw_fslabel_label_customer_group'),
            $setup->getFkName(
                'aw_fslabel_label_customer_group',
                'customer_group_id',
                'customer_group',
                'customer_group_id'
            )
        );

        return $this;
    }
}
