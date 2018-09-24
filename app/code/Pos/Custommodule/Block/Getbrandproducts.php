<?php
namespace Pos\Custommodule\Block;

use Magento\Framework\ObjectManagerInterface;

class Getbrandproducts extends \Magento\Framework\View\Element\Template
{

     protected $objectManager;
     protected $_reviewFactory;
     protected $_storeManager;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context, 
        ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
         \Magento\Review\Model\ReviewFactory $reviewFactory,
         \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;    
        $this->objectManager = $objectManager;
        $this->_storeManager = $storeManager;    
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->_reviewFactory = $reviewFactory;
        parent::__construct(
            $context,
            $data
        );
        $this->pageConfig->getTitle()->set(__($this->getPageTitle()));
    }

    public function getFormatedPrice($amount)
    {
        return $this->objectManager->create('\Magento\Framework\Pricing\PriceCurrencyInterface')->format($amount,true,2);
    }

    public function getPageTitle()
    {
        $manufacturer = $_GET['manufacturer'];
        $brand = $_GET['brand'];
        $title = "Search results for: Manufacturer = ".$manufacturer." & Model = ".$brand;
        return $title;
    }

public function getRatingSummary($product)
{
    $this->_reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
    $ratingSummary = $product->getRatingSummary()->getRatingSummary();
    return $ratingSummary;
}
    public function getAddtocartlink($collection)
    {
        $listBlock = $this->objectManager->get('\Magento\Catalog\Block\Product\ListProduct');
        return $listBlock->getAddToCartUrl($collection);
    }

    public function getMediaUrl()
    {

            $media_dir = $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
                ->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

            return $media_dir;
    }
        
    public function getProducts($manufacturer,$model)
    {
        //$porIds=array('5387303','19080DT','19023DT','19045CDT','19017DT','200082','19214DT','19350DT','200087');


       $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
        $getOptionValue = $objectManager->create('Pos\Manufacturer\Model\Manufacturer')->getCollection();
        $getOptionValue->addFieldToFilter('manufacturer', $manufacturer);
        $getOptionValue->addFieldToFilter('model', $model);
        foreach ($getOptionValue as $value) {
            $productSkuString = $value->getData('product_ids');
        }
        $porIds = explode(",",$productSkuString);

        $collection = $this->_productCollectionFactory->create();
        $collection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')->addAttributeToFilter('status', '1')
                        //->addAttributeToFilter('is_featured', '1')
                        ->addAttributeToSort('sku', 'ASC')
                        ->addAttributeToFilter('sku', array('in' => $porIds));
                      // ->addAttributeToFilter('manufacturer',array('finset' => $manufacturer))
                       // ->addAttributeToFilter('model',array('finset' => $model));

        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());
        $collection->addAttributeToSort('sku', 'ASC');
        return $collection;
    }   

}
