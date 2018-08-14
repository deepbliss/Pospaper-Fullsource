<?php
/**
 * Copyright © 2015 CommerceExtensions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace CommerceExtensions\CategoriesImportExport\Model\Data;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 *  CSV Import Handler
 */
class CsvImportHandler
{

    protected $_categoryCache = [];

    /**
     * Resource instance
     *
     * @var Resource
     */
    protected $_resource;

    /**
     * Write connection adapter
     *
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $_connection;

    /**
     * Collection of publicly available stores
     *
     * @var \Magento\Store\Model\Resource\Store\Collection
     */
    protected $_publicStores;

    protected $_category;

    /**
     * Categoiry factory
     *
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * CSV Processor
     *
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    protected $_logger;

    /**
     * @param \Magento\Store\Model\Resource\Store\Collection $storeCollection
     * @param \Magento\Catalog\Model\CategoryFactory         $categoryFactory
     * @param \Magento\Framework\File\Csv                    $csvProcessor
     */
    public function __construct(
        ResourceConnection $resource,
        \Magento\Store\Model\ResourceModel\Store\Collection $storeCollection,
        \Magento\Catalog\Model\Category $category,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\File\Csv $csvProcessor
    )
    {
        // prevent admin store from loading
        $this->_resource        = $resource;
        $this->_publicStores    = $storeCollection->setLoadDefault(true);
        $this->_category        = $category;
        $this->_categoryFactory = $categoryFactory;
        $this->_logger          = $logger;
        $this->csvProcessor     = $csvProcessor;
    }

    /**
     * Retrieve a list of fields required for CSV file (order is important!)
     *
     * @return array
     */
    public function getRequiredCsvFields()
    {
        // indexes are specified for clarity, they are used during import
        return [
            0  => __('rootid'),
            1  => __('store'),
            2  => __('category_id'),
            3  => __('name'),
            4  => __('categories'),
            5  => __('level'),
            6  => __('position'),
            7  => __('description'),
            8  => __('url_key'),
            9  => __('is_active'),
            10 => __('display_mode'),
            11 => __('page_layout'),
            12 => __('cms_block'),
            13 => __('is_anchor'),
            14 => __('meta_title'),
            15 => __('meta_keywords'),
            16 => __('meta_description'),
            17 => __('category_image'),
            18 => __('include_in_menu'),
            19 => __('custom_layout_update'),
            20 => __('custom_design'),
            21 => __('custom_use_parent_settings'),
            22 => __('custom_apply_to_products')
        ];
    }

    /**
     * Import Categories from CSV file
     *
     * @param array $file file info retrieved from $_FILES array
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function importFromCsvFile($file, $params)
    {
        if (!isset($file['tmp_name'])) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));
        }

        if ($params['import_delimiter'] != "") {
            $this->csvProcessor->setDelimiter($params['import_delimiter']);
        }
        if ($params['import_enclose'] != "") {
            $this->csvProcessor->setEnclosure($params['import_enclose']);
        }

        $RawData = $this->csvProcessor->getData($file['tmp_name']);
        // first row of file represents headers
        $fileFields = $RawData[0];
        #$validFields = $this->_filterFileFields($fileFields);
        #print_r($validFields);
        #$invalidFields = array_diff_key($fileFields, $validFields);
        #print_r($invalidFields);
        $ratesData = $this->_filterData($fileFields, $RawData);
        #print_r($ratesData);
        // store cache array is used to quickly retrieve store ID when handling locale-specific tax rate titles
        #$storesCache = $this->_composeStoreCache($validFields);
        #$regionsCache = [];
        foreach ($ratesData as $dataRow) {
            // skip headers
            #if ($rowIndex == 0) {
            #continue;
            #}
            $regionsCache = $this->_importRate($dataRow, $params);
        }
    }

    /**
     * Filter file fields (i.e. unset invalid fields)
     *
     * @param array $fileFields
     *
     * @return string[] filtered fields
     */
    protected function _filterFileFields(array $fileFields)
    {
        $filteredFields    = $this->getRequiredCsvFields();
        $requiredFieldsNum = count($this->getRequiredCsvFields());
        $fileFieldsNum     = count($fileFields);

        // process title-related fields that are located right after required fields with store code as field name)
        for ($index = $requiredFieldsNum; $index < $fileFieldsNum; $index++) {
            $titleFieldName = $fileFields[$index];
            if ($this->_isStoreCodeValid($titleFieldName)) {
                // if store is still valid, append this field to valid file fields
                $filteredFields[$index] = $titleFieldName;
            }
        }

        return $filteredFields;
    }

    /**
     * Filter data (i.e. unset all invalid fields and check consistency)
     *
     * @param array $rateRawData
     * @param array $invalidFields assoc array of invalid file fields
     * @param array $validFields   assoc array of valid file fields
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function _filterData(array $RawDataHeader, array $RawData)
    {
        $rowCount = 0;
        #$RawDataHeader = array();
        $RawDataRows = [];
        #print_r($RawData);
        #$validFieldsNum = count($validFields);
        foreach ($RawData as $rowIndex => $dataRow) {
            // skip headers
            if ($rowIndex == 0) {
				if(!in_array("rootid", $dataRow)) {
                	throw new \Magento\Framework\Exception\LocalizedException(__('ERROR: REQUIRED FIELD "rootid" NOT FOUND'));
				}
				if(!in_array("store", $dataRow)) {
                	throw new \Magento\Framework\Exception\LocalizedException(__('ERROR: REQUIRED FIELD "store" NOT FOUND'));
				}
				if(!in_array("is_active", $dataRow)) {
                	throw new \Magento\Framework\Exception\LocalizedException(__('ERROR: REQUIRED FIELD "is_active" NOT FOUND'));
				}
                continue;
            }
            // skip empty rows
            if (count($dataRow) <= 1) {
                unset($RawData[$rowIndex]);
                continue;
            }
            #print_r($RawDataHeader);
            /* we take rows from [0] = > value to [website] = base */
            if ($rowIndex > 0) {
                foreach ($dataRow as $rowIndex => $dataRowNew) {
                    $RawDataRows[$rowCount][$RawDataHeader[$rowIndex]] = $dataRowNew;
                }
            }
            #$RawDatafix[$dataRow] = $dataRow;
            // unset invalid fields from data row
            #foreach ($dataRow as $fieldIndex => $fieldValue) {
            #if (isset($invalidFields[$fieldIndex])) {
            #unset($RawData[$rowIndex][$fieldIndex]);
            #}
            #}
            // check if number of fields in row match with number of valid fields
            #if (count($RawData[$rowIndex]) != $validFieldsNum) {
            # throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file format.'));
            #}
            $rowCount++;
        }
        return $RawDataRows;
    }

    /**
     * Compose stores cache
     *
     * This cache is used to quickly retrieve store ID when handling locale-specific tax rate titles
     *
     * @param string[] $validFields list of valid CSV file fields
     *
     * @return array
     */
    protected function _composeStoreCache($validFields)
    {
        $storesCache       = [];
        $requiredFieldsNum = count($this->getRequiredCsvFields());
        $validFieldsNum    = count($validFields);
        // title related fields located right after required fields
        for ($index = $requiredFieldsNum; $index < $validFieldsNum; $index++) {
            foreach ($this->_publicStores as $store) {
                $storeCode = $validFields[$index];
                if ($storeCode === $store->getCode()) {
                    $storesCache[$index] = $store->getId();
                }
            }
        }
        return $storesCache;
    }

    /**
     * Check if public store with specified code still exists
     *
     * @param string $storeCode
     *
     * @return boolean
     */
    protected function _isStoreCodeValid($storeCode)
    {
        $isStoreCodeValid = false;
        foreach ($this->_publicStores as $store) {
            if ($storeCode === $store->getCode()) {
                $isStoreCodeValid = true;
                break;
            }
        }
        return $isStoreCodeValid;
    }

    /**
     * Import single category
     *
     * @param array $Data
     * @param array $regionsCache cache of regions of already used countries (is used to optimize performance)
     * @param array $storesCache  cache of stores related to tax rate titles
     *
     * @return array regions cache populated with regions related to country of imported tax rate
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _importRate(array $Data, array $params)
    {
        $modelData     = [];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		
        //
        if (isset($Data['store'])) {
            if (empty($Data['store'])) {
                $store          = $this->getStoreByCode("admin");
                $currentStoreId = $store->getId(); //$currentStoreId = "0";
            } else {
                if($store = $this->getStoreByCode($Data['store'])) {
					$currentStoreId = $store->getId();
				} else {
                	throw new \Magento\Framework\Exception\LocalizedException(__('ERROR: Store value "'.$Data['store'].'" in "store" is NOT FOUND and not a valid store'));
				}
            }
        } else {
            $store          = $this->getStoreByCode($Data['store']);
            $currentStoreId = $store->getId();
            #$currentStoreId = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore($Data['store'])->getId();
        }
        if (isset($Data['rootid']) && $Data['rootid'] != "") {
            $rootId = $Data['rootid'];
        } else {
            $rootId = 2;
        }
        if (!$rootId) {
            return [];
        }
        $rootPath = '1/' . $rootId;
        if (empty($this->_categoryCache[$store->getId()])) {

            $collection = $objectManager->create('Magento\Catalog\Model\Category')->getCollection()
                                        ->setStore($store)
                                        ->addAttributeToSelect('name');
            $collection->getSelect()->where("path like '" . $rootPath . "/%'");
			
            foreach ($collection as $cat) {

                $pathArr  = explode('/', $cat->getPath());
                $namePath = '';
				
				try {
					for ($i = 2, $l = sizeof($pathArr); $i < $l; $i++) {
						if ($pathArr[$i] != "") {
							$name = $collection->getItemById($pathArr[$i])->getName();
							$namePath .= (empty($namePath) ? '' : '/') . trim($name);
						}
					}
				} catch (\Exception $e) {
                    throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
                }
                $cat->setNamePath($namePath);
            }

            $cache = [];
            foreach ($collection as $cat) {
                $cache[strtolower($cat->getNamePath())] = $cat;
                $cat->unsNamePath();
            }
            $this->_categoryCache[$store->getId()] = $cache;
        }
        $cache =& $this->_categoryCache[$store->getId()];
        //Remove this line if your using ^ vs / as delimiter for categories.. fix for cat names with / in them
        if ($params['categorydelimiter'] == "/") {
            $Data['categories'] = preg_replace('#\s*/\s*#', '/', trim($Data['categories']));
        }
        $path = $rootPath;
        #$this->_logger->log(100,"PATH START: " .$path);
        $namePath = '';
        $i        = 1;
        if ($params['categorydelimiter'] != "") {
            $delimitertouse = $params['categorydelimiter'];
        } else {
            $delimitertouse = "/";
        }

        $file = "";
        if ($params['import_images_by_url'] == "true") {
            if (isset($Data['category_image'])) {
			 	if ($Data['category_image']!= "") {
					$_filesystem = $objectManager->create('Magento\Framework\Filesystem');
					$orgfile     = $Data['category_image'];
					$path_parts  = pathinfo($orgfile);
					$file        = $path_parts['basename'];
					$fullpath    = $_filesystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath('catalog') . '/category/' . $file;
					try {
						#$filewithspacesreplaced = str_replace(" ","%20", $orgfile); //fix for urls with spaces in the Url
						$ch = curl_init($orgfile);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
						$rawdata = curl_exec($ch);
						curl_close($ch);
						if (file_exists($fullpath)) {
							#$file = $path_parts['filename'].".".$path_parts['extension'];
							$fullpath = $_filesystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath('catalog') . '/category/' . $file;
							if (file_exists($fullpath)) {
								unlink($fullpath);
							}
						}
						$fp = fopen($fullpath, 'x');
						fwrite($fp, $rawdata);
						fclose($fp);
					} catch (\Exception $e) {
						throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
					}
				}
            }
        }

        if (isset($Data['rootid'])) {
            $modelData['root_id'] = $Data['rootid'];
        }
        if (isset($Data['store'])) {
            $modelData['store'] = $Data['store'];
        }
        if (isset($Data['category_id'])) {
            $modelData['category_id'] = $Data['category_id'];
        }
        if (isset($Data['name'])) {
            $modelData['name'] = $Data['name'];
        }
        if (isset($Data['categories'])) {
            #$categories = $Data['categories'];
            $categories = explode($delimitertouse, $Data['categories']);
        }
        if (isset($Data['level'])) {
            $modelData['level'] = $Data['level'];
        }
        if (isset($Data['position'])) {
            $modelData['position'] = $Data['position'];
        }
        if (isset($Data['description'])) {
            $modelData['description'] = $Data['description'];
        }
        if (isset($Data['url_key'])) {
            $modelData['url_key'] = $Data['url_key'];
        }
        if (isset($Data['is_active'])) {
            $modelData['is_active'] = $Data['is_active'];
        }
        if (isset($Data['display_mode'])) {
            $modelData['display_mode'] = $Data['display_mode'];
        }
        if (isset($Data['page_layout'])) {
            $modelData['page_layout'] = $Data['page_layout'];
        }
        if (isset($Data['cms_block'])) {
            $modelData['landing_page'] = $Data['cms_block'];
        }
        if (isset($Data['is_anchor'])) {
            $modelData['is_anchor'] = $Data['is_anchor'];
        }
        if (isset($Data['meta_title'])) {
            $modelData['meta_title'] = $Data['meta_title'];
        }
        if (isset($Data['meta_keywords'])) {
            $modelData['meta_keywords'] = $Data['meta_keywords'];
        }
        if (isset($Data['meta_description'])) {
            $modelData['meta_description'] = $Data['meta_description'];
        }
        //lets check and if available use the image we downloaded via http vs path set in csv
        if ($file != "") {
            $modelData['image'] = $file;
        } else {
			if(isset($Data['category_image'])) {
            	$modelData['image'] = $Data['category_image'];
			}
        }
        if (isset($Data['include_in_menu'])) {
            $modelData['include_in_menu'] = $Data['include_in_menu'];
        }
        if (isset($Data['custom_layout_update'])) {
            $modelData['custom_layout_update'] = $Data['custom_layout_update'];
        }
        if (isset($Data['custom_design'])) {
            $modelData['custom_design'] = $Data['custom_design'];
        }
        if (isset($Data['custom_use_parent_settings'])) {
            $modelData['custom_use_parent_settings'] = $Data['custom_use_parent_settings'];
        }
        if (isset($Data['custom_apply_to_products'])) {
            $modelData['custom_apply_to_products'] = $Data['custom_apply_to_products'];
        }
        if (isset($Data['category_id'])) {
            $modelData['entity_id'] = $Data['category_id'];
        }
        #if(isset($Data['available_sort_by'])) {
        $modelData['available_sort_by'] = "name";
        #}
        #if(isset($Data['default_sort_by'])) {
        $modelData['default_sort_by'] = "name";
        #}
        $modelData['store_id'] = $currentStoreId;
       
        // try to load existing category

        if (isset($Data['category_id'])) {

            $categoryModel = $this->_categoryFactory->create()->load($Data['category_id']);
            //$categoryModel = $this->_objectManager->create('Magento\Catalog\Model\Category')->load($Data['category_id']);

            if ($categoryModel->getId()) {
                $categoryModel->addData($modelData);
                try {
                    $categoryModel->save();
                } catch (\Exception $e) {
                    #echo "ERROR: " . $e->getMessage();
                    throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
                }
                
                //CUSTOM CODE CATEGORY PRODUCT'S / POSITIONS
                if (isset($Data['category_products']) && $Data['category_products'] != "") {
                    $connection                = $this->_resource->getConnection();
                    $_catalog_category_product = $this->_resource->getTableName('catalog_category_product');

                    $pipedelimiteddatabycomma = explode(',', $Data['category_products']);
                    foreach ($pipedelimiteddatabycomma as $options_data) {

                        $option_parts = explode(':', $options_data);
                        $productId    = $objectManager->create('Magento\Catalog\Model\Product')->getIdBySku($option_parts[0]);

                        if ($productId > 0) {
                            $connection->query("DELETE FROM " . $_catalog_category_product . " WHERE category_id = '" . $Data['category_id'] . "' AND product_id = '$productId'");
                            if (isset($option_parts[1])) {
                                $cat_product_position = $option_parts[1];
                            } else {
                                $cat_product_position = "0";
                            }

                            $connection->query("INSERT INTO " . $_catalog_category_product . " (category_id,product_id,position) VALUES ('" . $Data['category_id'] . "','$productId','$cat_product_position')");
                        } else {
                            throw new \Magento\Framework\Exception\LocalizedException(
                                __('ERROR: PRODUCT DOES NOT EXIST')
                            );
                        }
                    }
                }
                //CUSTOM CODE CATEGORY PRODUCT'S / POSITIONS

            } else {
                #echo "NEW CAT ID: " . $Data['category_id'] . "<br/>";
                if (isset($Data['category_id']) && $Data['category_id'] != "") {

                    $connection                = $this->_resource->getConnection();
                    $_catalog_category_product = $this->_resource->getTableName('catalog_category_product');

                    #$this->_logger->log(100,"INSIDE START OF CAT W ID: " .$path);
                    foreach ($categories as $catName) {
                        $namePath .= (empty($namePath) ? '' : '/') . strtolower($catName);
                        if (empty($cache[$namePath])) {
                            /// Get Root Category
                            #$rootCat = $this->_category->load($Data['rootid']);
                            /// Add a new sub category under root category
                            #$this->_logger->log(100,"BEING CAT: " .$path);
                            $categoryNew       = $this->_categoryFactory->create();
                            $catAttributeSetID = $categoryNew->getDefaultAttributeSetId();
                            $categoryNew->setName($catName);
                            $categoryNew->setIsActive($Data['is_active']);
                            if (isset($Data['url_key'])) {
                                $categoryNew->setUrlKey($Data['url_key']);
                            }
                            if (isset($Data['description'])) {
                                $categoryNew->setData('description', $Data['description']);
                            }
                            if (isset($Data['is_anchor'])) {
                                $categoryNew->setData('is_anchor', $Data['is_anchor']);
                            }
                            if (isset($Data['display_mode'])) {
                                $categoryNew->setData('display_mode', $Data['display_mode']);
                            }
                            if (isset($Data['page_layout'])) {
                                $categoryNew->setData('page_layout', $Data['page_layout']);
                            }
                            if (isset($Data['cms_block'])) {
                                $categoryNew->setData('landing_page', $Data['cms_block']);
                            }
                            if (isset($Data['meta_title'])) {
                                $categoryNew->setData('meta_title', $Data['meta_title']);
                            }
                            if (isset($Data['meta_keywords'])) {
                                $categoryNew->setData('meta_keywords', $Data['meta_keywords']);
                            }
                            if (isset($Data['meta_description'])) {
                                $categoryNew->setData('meta_description', $Data['meta_description']);
                            }
                            if (isset($Data['include_in_menu'])) {
                                $categoryNew->setData('include_in_menu', $Data['include_in_menu']);
                            }
                            if (isset($Data['custom_layout_update'])) {
                                $categoryNew->setData('custom_layout_update', $Data['custom_layout_update']);
                            }
                            if (isset($Data['custom_design'])) {
                                $categoryNew->setData('custom_design', $Data['custom_design']);
                            }
                            if (isset($Data['custom_use_parent_settings'])) {
                                $categoryNew->setData('custom_use_parent_settings', $Data['custom_use_parent_settings']);
                            }
                            if (isset($Data['custom_apply_to_products'])) {
                                $categoryNew->setData('custom_apply_to_products', $Data['custom_apply_to_products']);
                            }
							if (isset($Data['position'])) {
								if ($Data['position'] != "") {
									$categoryNew->setData('position', $Data['position']);
								} else {
									$position = "1";
								}
							}
                            #$categoryNew->setId($Data['category_id']);
                            $categoryNew->setAttributeSetId($catAttributeSetID);
                            #$categoryNew->setParentId($rootNodeId);
                            $categoryNew->setStoreId($currentStoreId);
                            #$categoryNew->setPath($Data['path']);
                            #$categoryNew->setPath($rootCat->getPath());
                            #$this->_logger->log(100,"BEFORE SAVE: " .$path);
                            $categoryNew->save();
                            $cache[$namePath] = $categoryNew;
                        } else if (!empty($cache[$namePath])) {
							
							$categoryNew = $this->_categoryFactory->create()->load($cache[$namePath]->getId());
							
                            if ($Data['category_id'] != $cache[$namePath]->getId() && $catName == $Data['name']) {
                                #$this->_logger->log(100,"MAKE NEW CAT: " .$Data['category_id']);
                                #$this->_logger->log(100,"MAKE NEW NAME: " .$Data['name']);
                                //THIS IS FOR WHEN WE ARE IMPORTING A NEW CATEGORY BUT THE NAME EXISTS ALREADY IN THE SAME FOLDER.. BUT WE WANT IT TWICE WITH SAME NAME
                                /*
                                $categoryNew = $this->_categoryFactory->create();
                                $catAttributeSetID = $categoryNew->getDefaultAttributeSetId();
                                $categoryNew->setName($catName);
                                $categoryNew->setIsActive($Data['is_active']);
                                #if(isset($Data['url_key'])) { $categoryNew->setUrlKey($Data['url_key']); }
                                if(isset($Data['description'])) { $categoryNew->setData('description', $Data['description']); }
                                if(isset($Data['is_anchor'])) { $categoryNew->setData('is_anchor', $Data['is_anchor']); }
                                if(isset($Data['display_mode'])) { $categoryNew->setData('display_mode', $Data['display_mode']); }
                                if(isset($Data['page_layout'])) { $categoryNew->setData('page_layout', $Data['page_layout']); }
                                if(isset($Data['cms_block'])) { $categoryNew->setData('landing_page', $Data['cms_block']); }
                                if(isset($Data['meta_title'])) { $categoryNew->setData('meta_title', $Data['meta_title']); }
                                if(isset($Data['meta_keywords'])) { $categoryNew->setData('meta_keywords', $Data['meta_keywords']); }
                                if(isset($Data['meta_description'])) { $categoryNew->setData('meta_description', $Data['meta_description']); }
                                if(isset($Data['include_in_menu'])) { $categoryNew->setData('include_in_menu', $Data['include_in_menu']); }
                                if(isset($Data['custom_layout_update'])) { $categoryNew->setData('custom_layout_update', $Data['custom_layout_update']); }
                                if(isset($Data['custom_design'])) { $categoryNew->setData('custom_design', $Data['custom_design']); }
                                if(isset($Data['custom_use_parent_settings'])) { $categoryNew->setData('custom_use_parent_settings', $Data['custom_use_parent_settings']); }
                                if(isset($Data['custom_apply_to_products'])) { $categoryNew->setData('custom_apply_to_products', $Data['custom_apply_to_products']); }
                                $categoryNew->setAttributeSetId($catAttributeSetID);
                                $categoryNew->setStoreId($currentStoreId);
                                $categoryNew->save();
                                $cache[$namePath] = $categoryNew;
                                */
                            }
                        }
                        #$this->_logger->log(100,"NAME PATH: " .$namePath);
                        $catId = $cache[$namePath]->getId();
                        $path .= '/' . $catId;
                        $i++;
                    }
                    //this code clones and then forces existing category Ids
                    $newpath        = "";
                    $new_id         = $Data['category_id'];
                    $copy           = clone $categoryModel;
					
                    $currentcatname = $categoryNew->getName();
                    #echo "HERE: " . $currentcatname;
                    #$path = $categoryNew->getPath(); #$path = $rootCat->getPath(). '/'.$oldid;
                    $oldid      = $categoryNew->getId();
                    $oldlevelid = $categoryNew->getLevel();
                    if ($oldlevelid < 1) {
                        $oldlevelid = 1;
                    }
                    $oldchilderncount  = $categoryNew->getChildrenCount() - 1;
                    $oldattributesetid = $categoryNew->getDefaultAttributeSetId();
                    if ($oldchilderncount == "") {
                        $pathArr          = explode('/', $path);
                        $l                = sizeof($pathArr);
                        $oldchilderncount = $l - 2;
                    }
                    if ($oldchilderncount < 0) {
                        $oldchilderncount = 0;
                    }
                    $categoryNew->delete();

                    $a     = explode("/", $path);
                    $count = 1;
                    foreach ($a as $i => $k) {
                        if ($count != count($a)) {
                            $newpath .= $k . "/";
                            $count++;
                        }
                    }
                    $newpath .= $new_id;
                    $generalupdateidset['name']      = $currentcatname;
                    $generalupdateidset['is_active'] = $Data['is_active'];
                    if (isset($Data['url_key'])) {
                        $generalupdateidset['url_key'] = $Data['url_key'];
                    }
                    if (isset($Data['is_anchor'])) {
                        $generalupdateidset['is_anchor'] = $Data['is_anchor'];
                    }
                    if (isset($Data['cms_block'])) {
                        $generalupdateidset['landing_page'] = $Data['cms_block'];
                    }
                    if (isset($Data['display_mode'])) {
                        $generalupdateidset['display_mode'] = $Data['display_mode'];
                    }
                    if (isset($Data['meta_title'])) {
                        $generalupdateidset['meta_title'] = $Data['meta_title'];
                    }
                    if (isset($Data['meta_keywords'])) {
                        $generalupdateidset['meta_keywords'] = $Data['meta_keywords'];
                    }
                    if (isset($Data['meta_description'])) {
                        $generalupdateidset['meta_description'] = $Data['meta_description'];
                    }
                    if (isset($Data['page_layout'])) {
                        $generalupdateidset['page_layout'] = $Data['page_layout'];
                    }
                    if (isset($Data['custom_layout_update'])) {
                        $generalupdateidset['custom_layout_update'] = $Data['custom_layout_update'];
                    }
                    if (isset($Data['custom_design'])) {
                        $generalupdateidset['custom_design'] = $Data['custom_design'];
                    }
                    if (isset($Data['include_in_menu'])) {
                        $generalupdateidset['include_in_menu'] = $Data['include_in_menu'];
                    }
                    if (isset($Data['custom_apply_to_products'])) {
                        $generalupdateidset['custom_apply_to_products'] = $Data['custom_apply_to_products'];
                    }
                    if (isset($Data['custom_use_parent_settings'])) {
                        $generalupdateidset['custom_use_parent_settings'] = $Data['custom_use_parent_settings'];
                    }
                    if (isset($Data['position'])) {
                        if ($Data['position'] != "") {
                            $generalupdateidset['position'] = $Data['position'];
                            $position                       = $Data['position'];
                        } else {
                            $position = "1";
                        }
                    }
                    $path = "";
                    $copy->addData($generalupdateidset);
                    #$this->_logger->log(100,"NEW PATH: ". $newpath);
                    $copy->setId($new_id)->setPath($newpath)->setPosition($position)->setLevel($oldlevelid)->setAttributeSetId($oldattributesetid)->setChildrenCount($oldchilderncount)->save();

                    $cache[$namePath] = $copy;

                    //CUSTOM CODE CATEGORY PRODUCT'S / POSITIONS
                    if (isset($Data['category_products']) && $Data['category_products'] != "") {
                        $pipedelimiteddatabycomma = explode(',', $Data['category_products']);
                        foreach ($pipedelimiteddatabycomma as $options_data) {

                            $option_parts = explode(':', $options_data);
                            $productId    = $objectManager->create('Magento\Catalog\Model\Product')->getIdBySku($option_parts[0]);

                            if ($productId > 0) {
                                $connection->query("DELETE FROM " . $_catalog_category_product . " WHERE category_id = '" . $new_id . "' AND product_id = '$productId'");
                                if (isset($option_parts[1])) {
                                    $cat_product_position = $option_parts[1];
                                } else {
                                    $cat_product_position = "0";
                                }

                                $connection->query("INSERT INTO " . $_catalog_category_product . " (category_id,product_id,position) VALUES ('" . $new_id . "','$productId','$cat_product_position')");
                            } else {
                                throw new \Magento\Framework\Exception\LocalizedException(
                                    __('ERROR: PRODUCT DOES NOT EXIST')
                                );
                            }
                        }
                    }
                    //CUSTOM CODE CATEGORY PRODUCT'S / POSITIONS
                }
            }
        } else { //checks if category_id is set in csv or not

            foreach ($categories as $catName) {
                $namePath .= (empty($namePath) ? '' : '/') . strtolower($catName);
                if (empty($cache[$namePath])) {
                    /// Get Root Category
                    #$rootCat = $this->_category->load($Data['rootid']);
                    /// Add a new sub category under root category
                    $categoryNew       = $this->_categoryFactory->create();
                    $catAttributeSetID = $categoryNew->getDefaultAttributeSetId();
                    $categoryNew->setName($catName);
                    $categoryNew->setIsActive($Data['is_active']);
                    if (isset($Data['url_key'])) {
                        $categoryNew->setUrlKey($Data['url_key']);
                    }
                    if (isset($Data['description'])) {
                        $categoryNew->setData('description', $Data['description']);
                    }
                    if (isset($Data['is_anchor'])) {
                        $categoryNew->setData('is_anchor', $Data['is_anchor']);
                    }
                    if (isset($Data['display_mode'])) {
                        $categoryNew->setData('display_mode', $Data['display_mode']);
                    }
                    if (isset($Data['page_layout'])) {
                        $categoryNew->setData('page_layout', $Data['page_layout']);
                    }
                    if (isset($Data['cms_block'])) {
                        $categoryNew->setData('landing_page', $Data['cms_block']);
                    }
                    if (isset($Data['image'])) {
                        $categoryNew->setData('image', $Data['image']);
                    }
                    if (isset($Data['meta_title'])) {
                        $categoryNew->setData('meta_title', $Data['meta_title']);
                    }
                    if (isset($Data['meta_keywords'])) {
                        $categoryNew->setData('meta_keywords', $Data['meta_keywords']);
                    }
                    if (isset($Data['meta_description'])) {
                        $categoryNew->setData('meta_description', $Data['meta_description']);
                    }
                    if (isset($Data['include_in_menu'])) {
                        $categoryNew->setData('include_in_menu', $Data['include_in_menu']);
                    }
                    if (isset($Data['custom_layout_update'])) {
                        $categoryNew->setData('custom_layout_update', $Data['custom_layout_update']);
                    }
                    if (isset($Data['custom_design'])) {
                        $categoryNew->setData('custom_design', $Data['custom_design']);
                    }
                    if (isset($Data['custom_use_parent_settings'])) {
                        $categoryNew->setData('custom_use_parent_settings', $Data['custom_use_parent_settings']);
                    }
                    if (isset($Data['custom_apply_to_products'])) {
                        $categoryNew->setData('custom_apply_to_products', $Data['custom_apply_to_products']);
                    }
                    if (isset($Data['position'])) {
                        if ($Data['position'] != "") {
                       		$categoryNew->setData('position', $Data['position']);
                        } else {
                            $position = "1";
                        }
                    }
                    #$categoryNew->setId($Data['category_id']);
                    $categoryNew->setAttributeSetId($catAttributeSetID);
                    #$categoryNew->setParentId($rootNodeId);
                    $categoryNew->setStoreId($currentStoreId);
                    $categoryNew->setPath($path);
                    #$categoryNew->setPath($rootCat->getPath());
                    $categoryNew->save();

                    //CUSTOM CODE CATEGORY PRODUCT'S / POSITIONS
                    if (isset($Data['category_products'])) {
						if($Data['category_products'] != "") {
							$pipedelimiteddatabycomma = explode(',', $Data['category_products']);
							$connection               = $this->_resource->getConnection();
							foreach ($pipedelimiteddatabycomma as $options_data) {
	
								$option_parts = explode(':', $options_data);
								$productId    = $objectManager->create('Magento\Catalog\Model\Product')->getIdBySku($option_parts[0]);
	
								if ($productId > 0) {
									$connection->query("DELETE FROM " . $_catalog_category_product . " WHERE category_id = '" . $categoryNew->getId() . "' AND product_id = '$productId'");
									if (isset($option_parts[1])) {
										$cat_product_position = $option_parts[1];
									} else {
										$cat_product_position = "0";
									}
	
									$connection->query("INSERT INTO " . $_catalog_category_product . " (category_id,product_id,position) VALUES ('" . $categoryNew->getId() . "','$productId','$cat_product_position')");
								} else {
									throw new \Magento\Framework\Exception\LocalizedException(
										__('ERROR: PRODUCT DOES NOT EXIST')
									);
								}
							}
						}
                    }
                    //CUSTOM CODE CATEGORY PRODUCT'S / POSITIONS
                    $cache[$namePath] = $categoryNew;
                }
                $catId = $cache[$namePath]->getId();
                $path .= '/' . $catId;
                $i++;
            }
        }

        /* THIS IS FOR UPDATING CATEGORY DATA */
		if(isset($cache[$namePath])) {
			$catId         = $cache[$namePath]->getId();
			$categoryModel = $this->_categoryFactory->create()->load($catId);
			if ($categoryModel->getId() > 0) {
				$categoryModel->addData($modelData);
				if (isset($Data['url_key'])) {
					$categoryModel->setUrlKey($Data['url_key']);
				}
	
				try {
					$categoryModel->save();
				} catch (\Exception $e) {
					#echo "ERROR: " . $e->getMessage();
					throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
				}
				#echo "UPDATE EXISTING";
				//CUSTOM CODE CATEGORY PRODUCT'S / POSITIONS
				if (isset($Data['category_products']) && $Data['category_products'] != "") {
					$connection                = $this->_resource->getConnection();
					$_catalog_category_product = $this->_resource->getTableName('catalog_category_product');
	
					$pipedelimiteddatabycomma = explode(',', $Data['category_products']);
					foreach ($pipedelimiteddatabycomma as $options_data) {
	
						$option_parts = explode(':', $options_data);
						$productId    = $objectManager->create('Magento\Catalog\Model\Product')->getIdBySku($option_parts[0]);
	
						if ($productId > 0) {
							$connection->query("DELETE FROM " . $_catalog_category_product . " WHERE category_id = '" . $Data['category_id'] . "' AND product_id = '$productId'");
							if (isset($option_parts[1])) {
								$cat_product_position = $option_parts[1];
							} else {
								$cat_product_position = "0";
							}
	
							$connection->query("INSERT INTO " . $_catalog_category_product . " (category_id,product_id,position) VALUES ('" . $Data['category_id'] . "','$productId','$cat_product_position')");
						} else {
							throw new \Magento\Framework\Exception\LocalizedException(
								__('ERROR: PRODUCT DOES NOT EXIST')
							);
						}
					}
				}
				//CUSTOM CODE CATEGORY PRODUCT'S / POSITIONS
			}
		} //ends check if we have a cached category name space
    }

    protected function getStoreByCode($storeCode)
    {
        foreach ($this->_publicStores as $store) {
            if ($storeCode === $store->getCode()) {
                return $store;
            }
        }
        return false;
    }

    /**
     * Add regions of the given country to regions cache
     *
     * @param string $countryCode
     * @param array  $regionsCache
     *
     * @return array
     */
    protected function _addCountryRegionsToCache($countryCode, array $regionsCache)
    {
        if (!isset($regionsCache[$countryCode])) {
            $regionsCache[$countryCode] = [];
            // add 'All Regions' to the list
            $regionsCache[$countryCode]['*'] = '*';
            $regionCollection                = clone $this->_regionCollection;
            $regionCollection->addCountryFilter($countryCode);
            if ($regionCollection->getSize()) {
                foreach ($regionCollection as $region) {
                    $regionsCache[$countryCode][$region->getCode()] = $region->getRegionId();
                }
            }
        }
        return $regionsCache;
    }
}
