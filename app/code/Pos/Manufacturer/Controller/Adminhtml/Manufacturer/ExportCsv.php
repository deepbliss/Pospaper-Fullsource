<?php

/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Bannerslider
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

namespace Pos\Manufacturer\Controller\Adminhtml\Manufacturer;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

/**
 * Export Csv action
 * @category Magestore
 * @package  Magestore_Bannerslider
 * @module   Bannerslider
 * @author   Magestore Developer
 */
class ExportCsv extends \Magento\Backend\App\Action
{
	
    public function execute()
    {
        	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
			$connection = $resource->getConnection();
			$sql = 'select * from manufacturer_manufacturer';
			$data1 = $connection->fetchAll($sql);

			/*[id] => 546
            [manufacturer] => 4Access
            [model] => Orion
            [product_ids] => 19017DT,19023DT,19045CDT,19080DT,200082,5387303*/

			//echo '<pre>';print_r($data);


			$data = "";
			$data .= "id,manufacturer,model,product_ids"."\n";
foreach($data1 as $row){
	 $prod_ids = '"'.$row['product_ids'].'"';


$data .= $row['id'].",".$row['manufacturer'].",".$row['model'].",".$prod_ids."\n";
 } 

header('Content-Type: application/csv');
$curr_date=date("dmY");
header('Content-Disposition: attachment; filename="manufacturer'.$curr_date.'.csv"');
echo $data; exit();
    }
}

