<?php

namespace Pos\Subscriptions\Command;

use Magento\Framework\Exception\NoSuchEntityException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use WeltPixel\GoogleTagManager\lib\Google\Exception;


class UpdateProducts extends Command
{
    const INPUT_KEY_PRODUCTID = 'productIds';

    protected $output;
    protected $productCollectionFactory;
    protected $productRepository;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productRepository = $productRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('pospaper:update_products')
            ->setDescription('Update Products')
            ->setDefinition([
                new InputOption(
                    self::INPUT_KEY_PRODUCTID,
                    null,
                    InputArgument::OPTIONAL,
                    'Product Ids'
                )
            ]);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->output->writeln("Starting Updates");

        $productIds = $input->getOption(self::INPUT_KEY_PRODUCTID);

        $collection = $this->productCollectionFactory->create()
            ->addStoreFilter(1)
            ->addAttributeToSelect('*');
            //->addAttributeToFilter('subscription_active',1);

        if($productIds) {
            $collection->addIdFilter(array_unique(explode(',',$productIds)));
        }

        $interval = array(
            'frequency_count'   => 1,
            'frequency_unit'    => 'month',
            'length'            => '',
            'installment_price' => '',
            'adjustment_price'  => ''
        );
        $intervals = array();
        for ($i = 1; $i <= 6; $i++) {
            $interval['frequency_count'] = $i;
            $intervals[] = $interval;
        }

        foreach($collection as $product) {
            try {
                $this->output->writeln($product->getSku());
                $productModel = $this->productRepository->getById($product->getId());
                $productModel->setData('subscription_active',0)->save();

                $productModel->setData('subscription_active',1);
                $productModel->setData('subscription_allow_onetime',1);
                $productModel->setData('subscription_intervals',"1,2,3,4,5,6");
                $productModel->setData('subscription_intervals_grid',$intervals);
                $productModel->save();

            } catch (NoSuchEntityException $e) {

            } catch (Exception $e) {
                $this->output->writeln($e->getMessage());
            }
        }

        $this->output->writeln("Updates finished");
    }
}