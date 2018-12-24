<?php


namespace Pos\CreditApp\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $table_pos_creditapp_creditapp = $setup->getConnection()->newTable($setup->getTable('pos_creditapp_creditapp'));

        $table_pos_creditapp_creditapp->addColumn(
            'creditapp_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );


        //Your install script

        $setup->getConnection()->createTable($table_pos_creditapp_creditapp);

        $sql = "ALTER TABLE `{$setup->getTable('pos_creditapp_creditapp')}`
                  ADD COLUMN `federal` varchar(50) NOT NULL,
                  ADD COLUMN `billingcompany` varchar(50) NOT NULL,
                  ADD COLUMN `billingaddress` varchar(100) NOT NULL,
                  ADD COLUMN `billingcity` varchar(50) NOT NULL,
                  ADD COLUMN `billingstate` varchar(50) NOT NULL,
                  ADD COLUMN `billingzipcode` int(50) NOT NULL,
                  ADD COLUMN `billingaccountname` varchar(100) NOT NULL,
                  ADD COLUMN `billingphoneno` int(50) NOT NULL,
                  ADD COLUMN `billingfax` varchar(50) NOT NULL,
                  ADD COLUMN `billingemail` varchar(100) NOT NULL,
                  ADD COLUMN `shippingcompany` varchar(50) NOT NULL,
                  ADD COLUMN `shippingaddress` varchar(100) NOT NULL,
                  ADD COLUMN `shippingcity` varchar(50) NOT NULL,
                  ADD COLUMN `shippingstate` varchar(50) NOT NULL,
                  ADD COLUMN `shippingzipcode` int(50) NOT NULL,
                  ADD COLUMN `shippingaccountname` varchar(100) NOT NULL,
                  ADD COLUMN `shippingphoneno` int(50) NOT NULL,
                  ADD COLUMN `shippingfax` varchar(100) NOT NULL,
                  ADD COLUMN `shippingemail` varchar(100) NOT NULL,
                  ADD COLUMN `owner` varchar(100) NOT NULL,
                  ADD COLUMN `ownerprincipal` varchar(100) NOT NULL,
                  ADD COLUMN `salesvolume` varchar(100) NOT NULL,
                  ADD COLUMN `openingorderamount` int(100) NOT NULL,
                  ADD COLUMN `creditline` varchar(100) NOT NULL,
                  ADD COLUMN `business` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferenceone` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferenceoneaddress` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferenceonecity` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferenceonestate` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferenceonezipcode` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferenceoneemail` varchar(50) NOT NULL,
                  ADD COLUMN `tradereferenceonephoneno` int(50) NOT NULL,
                  ADD COLUMN `tradereferenceonefax` varchar(50) NOT NULL,
                  ADD COLUMN `tradereferencetwo` varchar(50) NOT NULL,
                  ADD COLUMN `tradereferencetwoaddress` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferencetwocity` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferencetwostate` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferencetwozipcode` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferencetwoemail` varchar(50) NOT NULL,
                  ADD COLUMN `tradereferencetwophoneno` int(50) NOT NULL,
                  ADD COLUMN `tradereferencetwofax` varchar(50) NOT NULL,
                  ADD COLUMN `tradereferencethree` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferencethreeaddress` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferencethreecity` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferencethreestate` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferencethreezipcode` varchar(100) NOT NULL,
                  ADD COLUMN `tradereferencethreeemail` varchar(50) NOT NULL,
                  ADD COLUMN `tradereferencethreephoneno` int(50) NOT NULL,
                  ADD COLUMN `tradereferencethreefax` varchar(50) NOT NULL,
                  ADD COLUMN `fullname` varchar(50) NOT NULL,
                  ADD COLUMN `title` varchar(50) NOT NULL,
                  ADD COLUMN `dateinsert` date NOT NULL,
                  ADD COLUMN `datereviewed` date NOT NULL,
                  ADD COLUMN `credtlimits` varchar(50) NOT NULL,
                  ADD COLUMN `approvedby` varchar(50) NOT NULL,
                  ADD COLUMN `customerservice` varchar(50) NOT NULL,
                  ADD COLUMN `filename` varchar(100) NOT NULL,
                  ADD COLUMN `customerid` int(10) NOT NULL,
                  ADD COLUMN `customername` varchar(100) NOT NULL,
                  ADD COLUMN `customeremail` varchar(100) NOT NULL;";

        $setup->run($sql);
    }
}
