<?php

namespace Pos\Custommodule\Block;

class Searchbybrand extends \Magento\Framework\View\Element\Template
{
    protected $manufacturerCollectionFactory;

    public function __construct(
        \Pos\Manufacturer\Model\ResourceModel\Manufacturer\CollectionFactory $manufacturerCollectionFactory,
        \Magento\Framework\View\Element\Template\Context $context,   
        array $data = []
    ){        
        parent::__construct($context,$data);
        $this->manufacturerCollectionFactory = $manufacturerCollectionFactory;
    }

    public function getAllBrand(){
        //$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        //$getOptionValue = $objectManager->create('Pos\Manufacturer\Model\Manufacturer')->getCollection();
        $brands = $this->manufacturerCollectionFactory->create();
        return $brands;
    }  
}