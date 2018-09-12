<?php

namespace Nextopia\Search\Cron;

use Magento\Framework\App\Bootstrap;
use Magento\Framework\UrlInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator;
use Nextopia\Search\Helper\Data;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config;

/**
 * 
 */
class NxtExporter {

    // Set the password to export data here
    const PASSWORD = '';
    // Double Pipe for multivalues field
    const DOUBLE_PIPE = '||';
    // Fields to remove from data
    CONST FIELDS_TO_REMOVE = array('image', 'json_categories', 'json_tier_pricing');
    // Price fields
    CONST PRICE_FIELDS = array('price', 'nxt_regular_price', 'special_price',
        'minimal_price', 'tier_price');

    // Helper variables
    private $_tablePrefix;
    private $_dbi;
    private $_objectManager;
    private $IncludeDisabled;
    private $ExcludeOutOfStock;
    private $DownloadAsAttachment;
    private $_fp;
    private $_logger;
    private $_multivalueFields;
    private $_csvFileName;

    // Initialize the Mage application
    public function __construct($publicDir, $logger, $varDir) {

        ini_set('max_execution_time', '-1');
        // Make files written by the profile world-writable/readable
        umask(0);
        // Final csv export file
        $this->_csvFileName = $varDir . "/nextopia-exporter-files/nextopia-export-%s.csv";
        $dirname = dirname($this->_csvFileName);
        
        if (!is_dir($dirname)) {
            mkdir($dirname, 0755, true);
        } 

        // Create bootstrap object
        $this->_logger = $logger;
        $bootstrap = Bootstrap::create(BP, $_SERVER);
        $this->_objectManager = $bootstrap->getObjectManager();
        $deploymentConfig = $this->_objectManager->get('Magento\Framework\App\DeploymentConfig');
        $this->_tablePrefix = $deploymentConfig->get('db/table_prefix');
        $this->_dbi = $this->_objectManager->create('Magento\Framework\App\ResourceConnection')->getConnection();
        // Set default fetch mode to NUM to save memory
        $this->_dbi->setFetchMode(\ZEND_DB::FETCH_NUM);
        $appState = $this->_objectManager->get('Magento\Framework\App\State');
        $appState->setAreaCode('frontend');
        $appConfig = $this->_objectManager->get('Magento\Framework\App\Config');

        // Get all stores availables
        $query = "SELECT store_id, code, is_active
				FROM PFX_store AS str
				WHERE str.store_id <> 0
                                AND str.is_active = 1
				ORDER BY str.store_id ASC";
        $query = $this->_applyTablePrefix($query);
        $this->_logger->debug("$query ");
        $aStoresResult = $this->_dbi->fetchAll($query);

        foreach ($aStoresResult as $store) {

            $storeId = $store[0];
            $multivalueFields = array();
            $this->_logger->info("Store Info: " . print_r($store, true));
            $filename = sprintf($this->_csvFileName, $storeId);
            $productUrlSuffix = $appConfig->getValue(ProductUrlPathGenerator::XML_PATH_PRODUCT_URL_SUFFIX
                    , ScopeInterface::SCOPE_STORE, $storeId);
            $this->_logger->info("Store id $storeId Config: Product Url Suffix: $productUrlSuffix");
            $isSearchEnabled = (bool) $appConfig->getValue(Data::XML_PATH_SEARCHSTATUS
                            , ScopeInterface::SCOPE_STORE, $storeId);
            $this->_logger->info("Store id $storeId Config: Nextopia Search: $isSearchEnabled");
            $isCronNextopiaEnabled = (bool) $appConfig->getValue(Data::XML_PATH_CRON_NEXTOPIA_STATUS
                            , ScopeInterface::SCOPE_STORE, $storeId);
            $this->_logger->info("Store id $storeId Config: Nextopia Cron: $isCronNextopiaEnabled");

            if ($isSearchEnabled && $isCronNextopiaEnabled) {
                try {
                    $storeManager = $this->_objectManager->get('\Magento\Store\Model\StoreManager');
                    $currency = $storeManager->getStore($storeId)->getCurrentCurrencyCode();
                } catch (\Exception $e) {
                    $this->_logger->debug($e->getMessage());
                }

                $this->_fp = fopen($filename, 'w');

                //Run the main application
                $this->_runMain($storeId, $productUrlSuffix, $currency, $multivalueFields);
                fclose($this->_fp);

                $this->_logger->info("$filename");
                $this->_logger->info("Nxt export Store id $storeId Done");
            } else {
                $this->_logger->info("Nxt cron Store id $storeId Disable");

                if (file_exists($filename)) {
                    unlink($filename);
                }
            }
        }
    }

    private function _runMain($storeId, $productUrlSuffix, $currency, $multivalueFields) {

        $this->_logger->debug("_runMain");

        $this->ExcludeOutOfStock = false;
        $this->IncludeDisabled = false;

        // Run extraction
        $this->_runProductExport($storeId, $productUrlSuffix, $currency, $multivalueFields);

        // End script
        return;
    }

    // Extract product data natively directly from the database
    private function _runProductExport($storeId, $productUrlSuffix, $currency, $multivalueFields) {

        $this->_logger->debug("_runProductExport");

        //Validate store and get information
        $aInfos = $this->_getStoreInformation($storeId);

        // Check if staging module is enabled
        $StagingModuleEnabled = $this->_tableExists("PFX_catalog_product_entity", array('row_id'));

        // Check if Amasty Product Labels table exists
        $AmastyProductLabelsTableExists = $this->_tableExists("PFX_am_label");

        // Create a lookup table for the SKU to label_id
        $AmastyProductLabelsLookupTable = array();

        if ($AmastyProductLabelsTableExists == true) {
            // NOTE: Only fetch simple labels and ignore all matching rules.
            //   include_type=0 means "all matching SKUs and listed SKUs"
            //   include_type=1 means "all matching SKUs EXCEPT listed SKUs"
            //   include_type=2 means "listed SKUs only"
            $query = "SELECT label_id, name, include_sku
				FROM PFX_am_label
				WHERE include_type IN (0,2)
				ORDER BY pos DESC";
            $query = $this->_applyTablePrefix($query);
            $labelsTable = $this->_dbi->fetchAll($query);

            // Load each label into the lookup table
            foreach ($labelsTable as $row) {
                // Get the comma-separated SKUs
                $skus = explode(",", $row[2]);
                // Add each SKU to the lookup table

                foreach ($skus as $sku) {
                    $AmastyProductLabelsLookupTable[$sku] = array($row[0], $row[1]);
                }
            }
        }

        // Get product catalog flat table name
        $CatalogProductFlatTableName = $this->_applyTablePrefix("PFX_catalog_product_flat_" . $storeId);

        // Check if product catalog flat table exists
        $CatalogProductFlatTableExists = $this->_tableExists(
                $CatalogProductFlatTableName, array('url_key', 'url_path'));

        // Check if product category flat table exists
        $CatalogCategoryFlatTableExists = $this->_tableExists(
                "PFX_catalog_category_flat_store_" . $storeId);

        // Increase maximium length for group_concat (for additional image URLs field)
        $query = "SET SESSION group_concat_max_len = 1000000;";
        $this->_dbi->query($query);

        // By default, set media gallery attribute id to 703
        //  Look it up later
        $MEDIA_GALLERY_ATTRIBUTE_ID = 703;


        // Get the entity type for products
        $query = "SELECT entity_type_id FROM PFX_eav_entity_type
			WHERE entity_type_code = 'catalog_product'";
        $query = $this->_applyTablePrefix($query);
        $PRODUCT_ENTITY_TYPE_ID = $this->_dbi->fetchOne($query);

        // Prepare list entity table names
        $CatalogProductEntityTableNamesWithPrefix = array(
            'PFX_catalog_product_entity_datetime',
            'PFX_catalog_product_entity_decimal',
            'PFX_catalog_product_entity_int',
            'PFX_catalog_product_entity_text',
            'PFX_catalog_product_entity_varchar',
        );

        // Get attribute codes and types
        $query = "SELECT attribute_id, attribute_code, backend_type, backend_table, frontend_input
			FROM PFX_eav_attribute
			WHERE entity_type_id = $PRODUCT_ENTITY_TYPE_ID
			";
        $query = $this->_applyTablePrefix($query);
        $attributes = $this->_dbi->fetchAssoc($query);
        $attributeCodes = array();
        $blankProduct = array();
        $blankProduct['sku'] = '';

        foreach ($attributes as $row) {
            // Save attribute ID for media gallery
            if ($row['attribute_code'] == 'media_gallery') {
                $MEDIA_GALLERY_ATTRIBUTE_ID = $row['attribute_id'];
            }

            // Multivalue fields
            if ($row['frontend_input'] === 'multiselect') {
                array_push($multivalueFields, $row['attribute_code']);
            }

            switch ($row['backend_type']) {
                case 'datetime':
                case 'decimal':
                case 'int':
                case 'text':
                case 'varchar':
                    $attributeCodes[$row['attribute_id']] = $row['attribute_code'];
                    $blankProduct[$row['attribute_code']] = '';
                    break;
                case 'static':
                    // ignore columns in entity table
                    // print("Skipping static attribute: ".$row['attribute_code']."\n");
                    break;
                default:
                    // print("Unsupported backend_type: ".$row['backend_type']."\n");
                    break;
            }

            // Add table name to list of value tables, if the table exists
            if (isset($row['backend_table']) && $row['backend_table'] != '') {
                // Check if table exists without prefix
                if ($this->_tableExists($row['backend_table']) === true) {
                    $CatalogProductEntityTableNamesWithPrefix[] = $row['backend_table'];
                } else {
                    // If not found, check if table exists with prefix
                    $BackendTableWithPrefix = $this->_applyTablePrefix("PFX_" . $row['backend_table']);
                    if ($this->_tableExists($BackendTableWithPrefix) === true) {
                        $CatalogProductEntityTableNamesWithPrefix[] = $BackendTableWithPrefix;
                    }
                }
            }

            // If the type is multiple choice, cache the option values
            //   in a lookup array for performance (avoids several joins/aggregations)
            if ($row['frontend_input'] == 'select' || $row['frontend_input'] == 'multiselect') {
                // Get the option_id => value from the attribute options
                $query = "
                        SELECT
                                 CASE WHEN SUM(aov.store_id) = 0 THEN MAX(aov.option_id) ELSE 
                                        MAX(CASE WHEN aov.store_id = " . $storeId . " THEN aov.option_id ELSE NULL END)
                                 END AS 'option_id'
                                ,CASE WHEN SUM(aov.store_id) = 0 THEN MAX(aov.value) ELSE 
                                        MAX(CASE WHEN aov.store_id = " . $storeId . " THEN aov.value ELSE NULL END)
                                 END AS 'value'
                        FROM PFX_eav_attribute_option AS ao
                        INNER JOIN PFX_eav_attribute_option_value AS aov
                                ON ao.option_id = aov.option_id
                        WHERE aov.store_id IN (" . $storeId . ", 0)
                                AND ao.attribute_id = " . $row['attribute_id'] . "
                        GROUP BY aov.option_id
                ";
                $query = $this->_applyTablePrefix($query);
                $result = $this->_dbi->fetchPairs($query);

                // If found, then save the lookup table in the attributeOptions array
                if (is_array($result)) {
                    $attributeOptions[$row['attribute_id']] = $result;
                } else {
                    // Otherwise, leave a blank array
                    $attributeOptions[$row['attribute_id']] = array();
                }
                $result = null;
            }
        }

        $blankProduct['nxt_product_url'] = '';
        $blankProduct['nxt_image_url'] = '';
        $blankProduct['nxt_additional_image_url'] = '';
        $blankProduct['nxt_additional_image_value_id'] = '';
        $blankProduct['qty'] = 0;
        $blankProduct['stock_status'] = '';
        $blankProduct['nxt_color_attribute_id'] = '';
        $blankProduct['nxt_regular_price'] = '';
        $blankProduct['parent_id'] = '';
        $blankProduct['parent'] = '';
        $blankProduct['is_parent'] = 0;
        $blankProduct['item_group_id'] = '';
        $blankProduct['entity_id'] = '';
        $blankProduct['created_at'] = '';
        $blankProduct['updated_at'] = '';

        if ($AmastyProductLabelsTableExists === true) {
            $blankProduct['amasty_label_id'] = '';
            $blankProduct['amasty_label_name'] = '';
        }
        if ($CatalogProductFlatTableExists === true) {
            $blankProduct['flat_url_key'] = '';
            $blankProduct['flat_url_path'] = '';
        }

        // Build queries for each attribute type
        $queries = array();
        foreach ($CatalogProductEntityTableNamesWithPrefix as $CatalogProductEntityTableNameWithPrefix) {
            // Get store value if there is one, otherwise, global value
            $AttributeTypeQuery = "
                    SELECT
                             CASE
                                    WHEN SUM(ev.store_id) = 0
                                    THEN MAX(ev.value)
                                    ELSE MAX(CASE WHEN ev.store_id = " . $storeId . " THEN ev.value ELSE NULL END)
                             END AS 'value'
                            ,ev.attribute_id
                    FROM $CatalogProductEntityTableNameWithPrefix AS ev
                    WHERE ev.store_id IN (" . $storeId . ", 0)";

            if ($StagingModuleEnabled) {
                // If staging enabled, always get latest version
                $AttributeTypeQuery .= " AND ev.row_id = 
					(SELECT MAX(e.row_id) FROM PFX_catalog_product_entity AS e WHERE e.entity_id = @ENTITY_ID) ";
                $AttributeTypeQuery .= " GROUP BY ev.attribute_id, ev.row_id ";
            } else {
                $AttributeTypeQuery .= " AND ev.entity_id = @ENTITY_ID ";
                $AttributeTypeQuery .= " GROUP BY ev.attribute_id, ev.entity_id ";
            }
            $queries[] = $AttributeTypeQuery;
        }

        $MasterProductQuery = implode(" UNION ALL ", $queries);
        // Apply table prefix to the query
        $MasterProductQuery = $this->_applyTablePrefix($MasterProductQuery);
        // Clean up white-space in the query
        $MasterProductQuery = trim(preg_replace("/\s+/", " ", $MasterProductQuery));

        // Get all entity_ids for all products in the selected store
        //  into an array - require SKU to be defined
        if ($StagingModuleEnabled) {
            $query = "
                    SELECT cpe.entity_id, MAX(cpe.row_id) AS row_id
                    FROM PFX_catalog_product_entity AS cpe
                    INNER JOIN PFX_catalog_product_website as cpw
                            ON cpw.product_id = cpe.entity_id
                    WHERE cpw.website_id = " . $aInfos['websiteId'] . "
                            AND IFNULL(cpe.sku, '') != ''
                    GROUP BY cpe.entity_id, cpe.sku
            ";
            $query = $this->_applyTablePrefix($query);
            $EntityRows = $this->_dbi->fetchAll($query);
        } else {
            $query = "
                    SELECT cpe.entity_id
                    FROM PFX_catalog_product_entity AS cpe
                    INNER JOIN PFX_catalog_product_website as cpw
                            ON cpw.product_id = cpe.entity_id
                    WHERE cpw.website_id = " . $aInfos['websiteId'] . "
                            AND IFNULL(cpe.sku, '') != ''
            ";
            $query = $this->_applyTablePrefix($query);
            // Just fetch the entity_id column to save memory
//            $start_memory = memory_get_usage();
            $EntityRows = $this->_dbi->fetchCol($query);
//            echo memory_get_usage() - $start_memory;
        }

        // Print header row
        $headerFields = array();
        $headerFields[] = 'sku';

        foreach ($attributeCodes as $fieldName) {

            if (in_array($fieldName, self::FIELDS_TO_REMOVE)) {
                continue;
            }

            $headerFields[] = $fieldName;
        }

        $headerFields[] = 'nxt_product_url';
        $headerFields[] = 'nxt_image_url';
        $headerFields[] = 'nxt_additional_image_url';
        $headerFields[] = 'nxt_additional_image_value_id';
        $headerFields[] = 'qty';
        $headerFields[] = 'stock_status';
        $headerFields[] = 'nxt_color_attribute_id';
        $headerFields[] = 'nxt_regular_price';
        $headerFields[] = 'parent_id';
        $headerFields[] = 'parent'; // parent_id
        $headerFields[] = 'is_parent';
        $headerFields[] = 'item_group_id';
        $headerFields[] = 'entity_id';
        $headerFields[] = 'created_at';
        $headerFields[] = 'updated_at';

        if ($AmastyProductLabelsTableExists === true) {
            $headerFields[] = 'amasty_label_id';
            $headerFields[] = 'amasty_label_name';
        }
        if ($CatalogProductFlatTableExists === true) {
            $headerFields[] = 'flat_url_key';
            $headerFields[] = 'flat_url_path';
        }

        array_push($multivalueFields, 'nxt_additional_image_url', 'nxt_additional_image_value_id');
        $this->_logger->debug(print_r($multivalueFields, true));

        $this->_writeCSVLine($headerFields);

        // Loop through each product and output the data
        foreach ($EntityRows as $EntityRow) {
            // Get the entity_id/row_id from the row
            if ($StagingModuleEnabled) {
                $entity_id = $EntityRow[0];
                $row_id = $EntityRow[1];
            } else {
                $entity_id = $EntityRow;
            }

            // Check if the item is out of stock and skip if needed
            if ($this->ExcludeOutOfStock == true) {
                $query = "
                        SELECT stock_status
                        FROM PFX_cataloginventory_stock_status AS ciss
                        WHERE ciss.website_id = " . $aInfos['websiteId'] . "
                        AND ciss.product_id = " . $entity_id . "
                ";
                $query = $this->_applyTablePrefix($query);
                $stock_status = $this->_dbi->fetchOne($query);

                // If stock status not found or equal to zero, skip the item
                if (empty($stock_status)) {
                    continue;
                }
            }

            // Create a new product record
            $product = $blankProduct;
            $product['entity_id'] = $entity_id;

            // Get the basic product information
            $query = "
                    SELECT cpe.sku, cpe.created_at, cpe.updated_at, cpe.attribute_set_id, 
                            cpe.type_id, cpe.has_options, cpe.required_options, eas.attribute_set_name
                    FROM PFX_catalog_product_entity AS cpe
                    LEFT OUTER JOIN PFX_eav_attribute_set AS eas ON cpe.attribute_set_id = eas.attribute_set_id
                    WHERE cpe.entity_id = " . $entity_id . "
            ";
            $query = $this->_applyTablePrefix($query);
            $entity = $this->_dbi->fetchRow($query);
            if (empty($entity) == true) {
                continue;
            }

            // Initialize basic product data
            $product['sku'] = $entity[0];
            $product['created_at'] = $entity[1];
            $product['updated_at'] = $entity[2];

            // Set label information
            if ($AmastyProductLabelsTableExists == true) {
                // Check if the SKU has a label
                if (array_key_exists($product['sku'], $AmastyProductLabelsLookupTable) == true) {
                    // Set the label ID and name
                    $product['amasty_label_id'] = $AmastyProductLabelsLookupTable[$product['sku']][0];
                    $product['amasty_label_name'] = $AmastyProductLabelsLookupTable[$product['sku']][1];
                }
            }

            // Get flat table information
            if ($CatalogProductFlatTableExists === true) {
                $query = "SELECT url_key,url_path FROM $CatalogProductFlatTableName WHERE entity_id=" . $entity_id;
                $flatTableRow = $this->_dbi->fetchRow($query);

                if (empty($flatTableRow) === false) {
                    $product['flat_url_key'] = $flatTableRow[0];
                    $product['flat_url_path'] = $flatTableRow[1];
                }
            }

            // Fill the master query with the entity ID
            $query = str_replace('@ENTITY_ID', $entity_id, $MasterProductQuery);
            $result = $this->_dbi->query($query);

            // Escape the SKU (it may contain double-quotes)
            $product['sku'] = $product['sku'];

            // Loop through each field in the row and get the value
            while (true) {
                // Get next column
                // $column[0] = value
                // $column[1] = attribute_id
                $column = $result->fetch(\ZEND_DB::FETCH_NUM);

                // Break if no more rows
                if (empty($column)) {
                    break;
                }
                // Skip attributes that don't exist in eav_attribute
                if (!isset($attributeCodes[$column[1]])) {
                    continue;
                }

                // Save color attribute ID (for CJM automatic color swatches extension)
                //  NOTE: do this prior to translating option_id to option_value below
                if ($attributeCodes[$column[1]] == 'color') {
                    $product['nxt_color_attribute_id'] = $column[0];
                }

                // Translate the option option_id to a value.
                if (isset($attributeOptions[$column[1]]) == true) {
                    // Convert all option values
                    $optionValues = explode(',', $column[0]);
                    $convertedOptionValues = array();
                    foreach ($optionValues as $optionValue) {
                        if (isset($attributeOptions[$column[1]][$optionValue]) == true) {
                            // If a option_id is found, translate it
                            $convertedOptionValues[] = $attributeOptions[$column[1]][$optionValue];
                        }
                    }
                    // Erase values that are set to zero
                    if ($column[0] == '0') {
                        $column[0] = '';
                    } elseif (empty($convertedOptionValues) == false) {
                        // Use convert values if any conversions exist
                        $column[0] = implode(',', $convertedOptionValues);
                    }
                    // Otherwise, leave value as-is					
                }

                // Escape double-quotes and add to product array
                $product[$attributeCodes[$column[1]]] = $column[0];
            }

            $result = null;

            // Skip product that are disabled or have no status
            //  if the checkbox is not checked (this is the default setting)
            if ($this->IncludeDisabled == false) {
                if (empty($product['status']) || $product['status'] == $aInfos['STATUS_DISABLED_CONST']) {
                    continue;
                }
            }

            // Get category information, if table exists
            if ($CatalogCategoryFlatTableExists == true) {
                $query = "
                        SELECT fs.entity_id, fs.path, fs.name
                        FROM PFX_catalog_category_product_index AS pi
                                INNER JOIN PFX_catalog_category_flat_store_" . $storeId . " AS fs
                                        ON pi.category_id = fs.entity_id
                        WHERE pi.product_id = " . $entity_id . "
                ";
                $query = $this->_applyTablePrefix($query);
                $categoriesTable = $this->_dbi->fetchAll($query);
            }

            // Get stock quantity
            // NOTE: stock_id = 1 is the 'Default' stock
            $query = "
                    SELECT qty, stock_status
                    FROM PFX_cataloginventory_stock_status
                    WHERE product_id=" . $entity_id . "
                            AND website_id=" . $aInfos['websiteId'] . "
                            AND stock_id = 1";
            $query = $this->_applyTablePrefix($query);
            $stockInfoResult = $this->_dbi->query($query);
            $stockInfo = $stockInfoResult->fetch();
            if (empty($stockInfo) == true) {
                $product['qty'] = '0';
                $product['stock_status'] = '';
            } else {
                $product['qty'] = $stockInfo[0];
                $product['stock_status'] = $stockInfo[1];
            }

            $stockInfoResult = null;

            // Get additional image URLs
            $galleryImagePrefix = $this->_dbi->quote($aInfos['mediaBaseUrl'] . 'catalog/product');

            $query = "
                    SELECT
                             GROUP_CONCAT(mg.value_id SEPARATOR ',') AS value_id
                            ,GROUP_CONCAT(CONCAT(" . $galleryImagePrefix . ", mg.value) SEPARATOR ',') AS value
                    FROM PFX_catalog_product_entity_media_gallery_value_to_entity AS mgvte
                            INNER JOIN PFX_catalog_product_entity_media_gallery AS mg
                                    ON mgvte.value_id = mg.value_id
                            INNER JOIN PFX_catalog_product_entity_media_gallery_value AS mgv
                                    ON mg.value_id = mgv.value_id
                    WHERE   mgv.store_id IN (" . $storeId . ", 0)
                            AND mgv.disabled = 0
                            AND " . ($StagingModuleEnabled ? "mgvte.row_id=" . $row_id : "mgvte.entity_id=" . $entity_id) . "
                            AND mg.attribute_id = " . $MEDIA_GALLERY_ATTRIBUTE_ID . "
                    ORDER BY mgv.position ASC";

            $query = $this->_applyTablePrefix($query);
            $galleryValues = $this->_dbi->fetchAll($query);
            if (empty($galleryValues) != true) {
                // Save value IDs for CJM automatic color swatches extension support
                $product['nxt_additional_image_value_id'] = $galleryValues[0][0];
                $product['nxt_additional_image_url'] = $galleryValues[0][1];
            }

            // Get parent ID
            $query = "
                    SELECT GROUP_CONCAT(parent_id SEPARATOR ',') AS parent_id
                    FROM PFX_catalog_product_super_link AS super_link
                    WHERE super_link.product_id=" . $entity_id . "";
            $query = $this->_applyTablePrefix($query);
            $parentId = $this->_dbi->fetchAll($query);
            if (empty($parentId) != true) {
                // Save value IDs for CJM automatic color swatches extension support
                $product['parent_id'] = $parentId[0][0];
                $product['parent'] = $product['parent_id'];
                $product['item_group_id'] = $product['parent_id'];
            }
            if (empty($product['parent_id'])) {
                $product['is_parent'] = 1;
                $product['item_group_id'] = $product['entity_id'];
            }

            // Skip product if not visible and no parent Id (Magento 2 store search
            // doesn't display this product for some reason @TODO: Make research on this point)
            if (empty($product['parent_id']) && $product['visibility'] < 3) {
                $this->_logger->debug("Skip: $entity_id");
                continue;
            }

            // Get the regular price (before any catalog price rule is applied)
            $product['nxt_regular_price'] = $product['price'];

            // Override price with catalog price rule, if found
            $query = "
                    SELECT crpp.rule_price
                    FROM PFX_catalogrule_product_price AS crpp
                    WHERE crpp.rule_date = CURDATE()
                            AND crpp.product_id = " . $entity_id . "
                            AND crpp.customer_group_id = 1
                            AND crpp.website_id = " . $aInfos['websiteId'];
            $query = $this->_applyTablePrefix($query);
            $rule_price = $this->_dbi->fetchAll($query);

            if (empty($rule_price) != true) {
                // Override price with catalog rule price
                $product['price'] = $rule_price[0][0];
            }

            // Calculate product URL
            if (empty($product['url_path']) === false && $product['visibility'] >= 3) {
                $product['nxt_product_url'] = $this->_urlPathJoin($aInfos['webBaseUrl'], $product['url_path']);
            } else if (empty($product['flat_url_path']) === false && $product['visibility'] >= 3) {
                $product['nxt_product_url'] = $this->_urlPathJoin($aInfos['webBaseUrl'], $product['flat_url_path']);
            } else if (empty($product['flat_url_key']) === false && $product['visibility'] >= 3) {
                $product['nxt_product_url'] = $this->_urlPathJoin($aInfos['webBaseUrl'], $product['flat_url_key'] . $productUrlSuffix);
            }
            // @TODO: Doesn't work for all products (Because of the url_key sometimes empty)
            else if (empty($product['url_key']) === false && $product['visibility'] >= 3) {
                $product['nxt_product_url'] = $this->_urlPathJoin($aInfos['webBaseUrl'], $product['url_key'] . $productUrlSuffix);
            } else {
                // Quick fix
                $product['nxt_product_url'] = $this->_urlPathJoin($aInfos['webBaseUrl'], $this->_slugify($product['name']) . $productUrlSuffix);
            }

            // Calculate image URL
            if (empty($product['image']) === false) {
                $product['nxt_image_url'] = $this->_urlPathJoin($aInfos['mediaBaseUrl'], 'catalog/product');
                $product['nxt_image_url'] = $this->_urlPathJoin($product['nxt_image_url'], $product['image']);
            }

            foreach (self::FIELDS_TO_REMOVE as $field) {
                unset($product[$field]);
            }

            // Update MultiValue Fields
            $product = $this->updateMultiValueFields($product, $multivalueFields);
            $product["description"] = str_replace(array("\n", "\t", "\x0D"), " ", $product["description"]);
            $product["short_description"] = str_replace(array("\n", "\t", "\x0D"), " ", $product["short_description"]);

            // Add currency on prices 
            $product = $this->updatePriceCurrency($product, $currency);

            // Print out the line in CSV format
            $this->_writeCSVLine($product);
        }
    }

    // Write line as CSV, quoting fields if needed
    private function _writeCSVLine(&$row) {

        fputcsv($this->_fp, $row, "\t");
    }

    public function updateMultiValueFields($data, $multivalueFields) {

        foreach ($data as $field => $value) {
            foreach ($multivalueFields as $multivalue_field) {
                if (strpos($field, $multivalue_field) !== false && !empty($data[$field])) {
                    $data[$field] = str_replace(",", self::DOUBLE_PIPE, $data[$field]);
                    $allValuesExploded = explode(self::DOUBLE_PIPE, $data[$field]);
                    $allValuesTrimmed = array_map('trim', $allValuesExploded);
                    $data[$field] = implode(self::DOUBLE_PIPE, $allValuesTrimmed);
                }
            }
        }

        return $data;
    }

    public function updatePriceCurrency($data, $currency) {

        foreach ($data as $field => $value) {
            foreach (self::PRICE_FIELDS as $price_field) {
                if ($field === $price_field && !empty($data[$field])) {
                    $data[$field] = $data[$field] . " " . $currency;
                }
            }
        }

        return $data;
    }

    // Return Magento product version
    private function GetMagentoVersion() {

        $productMetadata = $this->_objectManager
                ->get('Magento\Framework\App\ProductMetadataInterface');
        return $productMetadata->getVersion();
    }

    // Get category flat table enabled or disabled
    private function CategoryFlatIsEnabled() {

        $config = $this->_objectManager->get('Magento\Framework\App\Config');
        $result = $config->getValue('catalog/frontend/flat_catalog_category');

        return ($result == '1');
    }

    /**
     * 
     * @param String $storeId
     * @return type
     */
    private function _getStoreInformation($storeId) {

        if (0 == preg_match('|^\d+$|', $storeId)) {
            NxtExporter::LogError(
                    'ERROR: The specified Store is not formatted correctly: ' . $storeId);
        }

        try {
            $storeManager = $this->_objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            $store = $storeManager->getStore($storeId);
            // Load the store information
            $websiteId = $store->getWebsiteId();
            $webBaseUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_WEB);
            $mediaBaseUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            $STATUS_DISABLED_CONST = Status::STATUS_DISABLED;
            // LOgs
            $this->_logger->info("Media Base Url : " . $mediaBaseUrl);
            $this->_logger->info("Website Id : " . $websiteId);
            $this->_logger->info("Web Base Url : " . $webBaseUrl);

            return array('websiteId' => $websiteId
                , 'webBaseUrl' => $webBaseUrl
                , 'mediaBaseUrl' => $mediaBaseUrl
                , 'STATUS_DISABLED_CONST' => $STATUS_DISABLED_CONST
            );
        } catch (\Exception $e) {
            NxtExporter::LogError(
                    'ERROR: Error getting store information for Store=' . $storeId .
                    ". The store probably does not exist. " . get_class($e) . " " . $e->getMessage());
        }
    }

    // Return true if table exists in the current schema.
    // Optionally, specify column names to verify table exists with those columns.
    private function _tableExists($TableName, $ColumnNames = null) {

        // Convert table prefix
        $TableName = $this->_applyTablePrefix($TableName);

        // Check if table exists in the current schema
        // NOTE: Used constant TABLE_SCHEMA and TABLE_NAME to avoid directory scans
        $query = "SELECT COUNT(*)
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_SCHEMA=DATABASE()
				AND TABLE_NAME='$TableName'";

        // Optionally check for columns
        $MinimumColumnCount = 1;
        if (isset($ColumnNames) && is_array($ColumnNames) && empty($ColumnNames) == false) {
            $query .= " AND COLUMN_NAME IN ('" . implode("','", $ColumnNames) . "')";
            $MinimumColumnCount = count($ColumnNames);
        }

        // Get the number of matching columns
        $CountColumns = $this->_dbi->fetchOne($query);

        // Return result
        return ($CountColumns >= $MinimumColumnCount);
    }

    // Apply prefix to table names in the query
    private function _applyTablePrefix($query) {

        return str_replace('PFX_', $this->_tablePrefix, $query);
    }

    public static function LogError($sMessage) {

        $this->_logger->err($sMessage);
        $this->_logger->debug($sMessage);

        return;
    }

    // Join two URL paths and handle forward slashes
    private function _urlPathJoin($part1, $part2) {

        return rtrim($part1, '/') . '/' . ltrim($part2, '/');
    }

    private function _slugify($text) {
        
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
