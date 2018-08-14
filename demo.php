<?php

use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';
// adding bootstrap
$bootstraps = Bootstrap::create(BP, $_SERVER);
$object_Manager = $bootstraps->getObjectManager();

$app_state = $object_Manager->get('\Magento\Framework\App\State');
$app_state->setAreaCode('frontend');

echo "<pre/>";

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$cat_id = 11;
$cateinstance = $objectManager->create('Magento\Catalog\Model\CategoryFactory');
$allcategoryproduct = $cateinstance->create()->load($cat_id)->getProductCollection()->addAttributeToSelect('*');
$array_cat_products = array();
foreach ($allcategoryproduct as $categoryproduct) 
{
	array_push($array_cat_products, $categoryproduct->getId());
}
$productCollection = $objectManager->create('Magento\Reports\Model\ResourceModel\Report\Collection\Factory'); 
$collection = $productCollection->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection'); 
$collection->setPeriod('year');

foreach ($collection as $item) 
{
	echo $item->getData('product_id')."<br/>";
	if(in_array($item->getData('product_id'),$array_cat_products))
	{
		$product = $objectManager->create('Magento\Catalog\Model\Product')->load($item->getData('product_id'));
		echo $product->getName()."<br/>";
	}
    
}