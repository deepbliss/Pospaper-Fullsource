<?php


namespace Pos\CreditApp\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        if (version_compare($context->getVersion(), "1.0.1", "<")) {
            $setup->getConnection()->addColumn(
                $setup->getTable('pos_creditapp_creditapp'),
                'hash',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'comment' => 'Hash'
                ]
            );
        }

        if (version_compare($context->getVersion(), "1.0.2", "<")) {
            $sql = "ALTER TABLE `{$setup->getTable('pos_creditapp_creditapp')}`
                      MODIFY datereviewed varchar(50) null,
                      MODIFY credtlimits varchar(255) null,
                      MODIFY approvedby varchar(255) null,
                      MODIFY customerservice varchar(255) null,
                      MODIFY filename varchar(255) null;";

            $setup->run($sql);
        }

        if (version_compare($context->getVersion(), "1.0.3", "<")) {
            $sql = "ALTER TABLE `{$setup->getTable('pos_creditapp_creditapp')}`
                    MODIFY `tradereferenceone` varchar(100) NULL,
                    MODIFY `tradereferenceoneaddress` varchar(255) NULL,
                    MODIFY `tradereferenceonecity` varchar(100) NULL,
                    MODIFY `tradereferenceonestate` varchar(100) NULL,
                    MODIFY `tradereferenceonezipcode` varchar(50) NULL,
                    MODIFY `tradereferenceoneemail` varchar(100) NULL,
                    MODIFY `tradereferenceonephoneno` int(50) NULL,
                    MODIFY `tradereferenceonefax` varchar(50) NULL,
                    MODIFY `tradereferencetwo` varchar(100) NULL,
                    MODIFY `tradereferencetwoaddress` varchar(255) NULL,
                    MODIFY `tradereferencetwocity` varchar(100) NULL,
                    MODIFY `tradereferencetwostate` varchar(100) NULL,
                    MODIFY `tradereferencetwozipcode` varchar(50) NULL,
                    MODIFY `tradereferencetwoemail` varchar(100) NULL,
                    MODIFY `tradereferencetwophoneno` int(50) NULL,
                    MODIFY `tradereferencetwofax` varchar(50) NULL,
                    MODIFY `tradereferencethree` varchar(100) NULL,
                    MODIFY `tradereferencethreeaddress` varchar(255) NULL,
                    MODIFY `tradereferencethreecity` varchar(100) NULL,
                    MODIFY `tradereferencethreestate` varchar(100) NULL,
                    MODIFY `tradereferencethreezipcode` varchar(50) NULL,
                    MODIFY `tradereferencethreeemail` varchar(100) NULL,
                    MODIFY `tradereferencethreephoneno` int(50) NULL,
                    MODIFY `tradereferencethreefax` varchar(50) NULL;";
            $setup->run($sql);
        }

        if (version_compare($context->getVersion(), "1.0.4", "<")) {
            $sql = "ALTER TABLE `{$setup->getTable('pos_creditapp_creditapp')}`
                      MODIFY `salesvolume` varchar(50) NULL,
                      MODIFY `openingorderamount` varchar(50) NULL,
                      MODIFY `business` varchar(100) NULL;";

            $setup->run($sql);
        }

        if (version_compare($context->getVersion(), "1.0.5", "<")) {
            $sql = "ALTER TABLE `{$setup->getTable('pos_creditapp_creditapp')}`
                      MODIFY `billingzipcode` varchar(50) NOT NULL,
                      MODIFY `billingphoneno` varchar(50) NOT NULL,
                      MODIFY `shippingzipcode` varchar(50) NOT NULL,
                      MODIFY `shippingphoneno` varchar(50) NOT NULL,
                      MODIFY `openingorderamount` varchar(50) NOT NULL,
                      MODIFY `tradereferenceonephoneno` varchar(50) NOT NULL,
                      MODIFY `tradereferencetwophoneno` varchar(50) NOT NULL,
                      MODIFY `tradereferencethreephoneno` varchar(50) NOT NULL;";

            $setup->run($sql);
        }
    }
}
