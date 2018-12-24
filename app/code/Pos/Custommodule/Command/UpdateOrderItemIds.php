<?php

namespace Pos\Custommodule\Command;

use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\Website;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

class UpdateOrderItemIds extends Command
{
    protected $objectManager;
    protected $orderCollectionFactory;
    protected $dateTime;
    protected $output;

    public function __construct(
        ObjectManagerInterface $objectManager,
        OrderCollectionFactory $collectionFactory,
        \Magento\Framework\Stdlib\DateTime $dateTime
    ) {
        $this->objectManager = $objectManager;
        $this->orderCollectionFactory = $collectionFactory;
        $this->dateTime = $dateTime;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('pospaper:update_order_item_ids')
            ->setDescription('Update Order Item IDs');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->output->writeln("Starting Updates");

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $productsTableName = $resource->getTableName('catalog_product_entity');
        $orderTableName = $resource->getTableName('sales_order');
        $orderItemsTableName = $resource->getTableName('sales_order_item');
        $products = array();
        $sql = "Select sku,entity_id FROM " . $productsTableName;
        $results = $connection->fetchAll($sql);
        $min = 9999999;
        foreach($results as $result) {
            $products[$result['sku']] = $result['entity_id'];
            if($result['entity_id'] < $min) {
                $min = $result['entity_id'];
            }
        }

        $sql = "SELECT so.increment_id,soi.item_id,soi.product_id,soi.sku FROM {$orderTableName} so LEFT JOIN {$orderItemsTableName} soi ON soi.order_id = so.entity_id WHERE soi.product_id < {$min}";
        $results = $connection->fetchAll($sql);
        foreach($results as $result) {
            if(isset($products[$result['sku']])) {
                $sql = "UPDATE {$orderItemsTableName} SET product_id = {$products[$result['sku']]} WHERE item_id = {$result['item_id']};";
                var_dump($sql);
            }
        }

        $this->output->writeln("Process finished");
    }
}