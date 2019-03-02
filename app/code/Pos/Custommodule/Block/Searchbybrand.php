<?php

namespace Pos\Custommodule\Block;

class Searchbybrand extends \Magento\Framework\View\Element\Template
{
    protected $manufacturerCollectionFactory;
    protected $registry;

    public function __construct(
        \Pos\Manufacturer\Model\ResourceModel\Manufacturer\CollectionFactory $manufacturerCollectionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Element\Template\Context $context,   
        array $data = []
    ){        
        parent::__construct($context,$data);
        $this->manufacturerCollectionFactory = $manufacturerCollectionFactory;
        $this->registry = $registry;
    }

    public function getAllBrand()
    {
        $brands = $this->manufacturerCollectionFactory->create();
        return $brands;
    }

    public function canShowPaperRollSearchTool()
    {
        $category = $this->registry->registry('current_category');
        if(!$category) {
            return true;
        }
        if($category->getData('custom_attribute1')) {
            return true;
        }

        return false;
    }
}