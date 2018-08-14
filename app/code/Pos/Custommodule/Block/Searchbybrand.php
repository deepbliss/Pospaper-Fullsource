<?php
namespace Pos\Custommodule\Block;

class Searchbybrand extends \Magento\Framework\View\Element\Template
{
    protected $_productAttributeRepository;

    public function __construct(        
        \Magento\Framework\View\Element\Template\Context $context,   
        \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,
        array $data = [] 
    ){        
        parent::__construct($context,$data);
        $this->_productAttributeRepository = $productAttributeRepository;
    } 

    public function getAllBrand(){
        /*$manufacturerOptions = $this->_productAttributeRepository->get('manufacturer')->getOptions();       


        $values = array();
        
        foreach ($manufacturerOptions as $manufacturerOption) { 
             if($manufacturerOption->getValue() != NULL)       
            $label[$manufacturerOption->getValue()] = $manufacturerOption->getLabel();
        }   
            return $label;*/


            $objectManager =  \Magento\Framework\App\ObjectManager::getInstance(); 
            $getOptionValue = $objectManager->create('Pos\Manufacturer\Model\Manufacturer')->getCollection();
            return $getOptionValue;
    }  
}