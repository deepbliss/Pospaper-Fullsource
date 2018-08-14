<?php
namespace Emipro\Importexportattributeoption\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class Import extends \Magento\Backend\App\Action
{
    private $eavConfig;
    private $uploaderFactory;
    private $basepath;
    private $attributeProcess;
    private $attributeOptions;
    private $directoryList;
    private $entityAttribute;
    private $sourceTable;
    private $coreRegistry = null;
    private $eavAttribute;
    private $csvValues = [];

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Eav\Model\Config $attribute,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Eav\Model\Entity\Attribute $entityAttribute,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $eavAttribute,
        \Magento\Eav\Model\Entity\Attribute\Source\Table $sourceTable,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->eavConfig = $attribute;
        $this->uploaderFactory = $uploaderFactory;
        $this->coreRegistry = $registry;
        $this->directoryList = $directoryList;
        $this->entityAttribute = $entityAttribute;
        $this->eavAttribute = $eavAttribute;
        $this->sourceTable = $sourceTable;
        $this->_directory = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
        parent::__construct($context);
        $this->basepath = $this->getBaseDir();
    }

    public function execute()
    {
        $attribute_id = $this->getRequest()->getParam("attribute");
        $resultRedirect = $this->resultRedirectFactory->create();
        $post = $this->getRequest()->getPost();
        $files = $this->getRequest()->getFiles();
        try {
            if (isset($files['fileToUpload']['name']) && $files['fileToUpload']['name'] != '') {
                $fileName = $files['fileToUpload']['name'];
                $fileExt = strtolower(substr(strrchr($fileName, "."), 1));
                $fileNamewoe = rtrim($fileName, $fileExt);
                $fileName = "attribute_options_import_" . date("Y-m-d") . ".csv";

                $status = $this->checkDelimeter($fileName, $post["delimiter"]);
                if ($status == 1) {
                    $_result = $this->callFile($post["attribute"], $fileName, $post["delimiter"]);
                    if ($_result == 1) {
                        $this->messageManager->addSuccess(__("Attribute options has been imported successfully...!!!"));
                        return $resultRedirect->setPath('importexportattributeoption/index/index');
                    }
                } else {
                    $tmpMsg = "Please Select Valid Delimeter For Your CSV File. (";
                    $tmpMsg .= $post["delimiter"] . ") Is Not Your File Delimeter...!";
                    $this->messageManager->addError(__($tmpMsg));
                    return $resultRedirect->setPath('importexportattributeoption/index/index');
                }
            } else {
                $this->messageManager->addError(__("Please select correct csv file."));
                return $resultRedirect->setPath('importexportattributeoption/index/index');
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError(__("Attribute options has not been imported successfully...!!!"));
            return $resultRedirect->setPath('importexportattributeoption/index/index');
        }
    }
    private function checkDelimeter($fileName, $tmpDelimeter)
    {
        $delimiters = [
            ',' => 0,
            ';' => 0,
            ":" => 0,
            "|" => 0,
        ];
        $test_count = 0;
        $handle = fopen($this->basepath . $fileName, "r");
        $firstLine = fgets($handle);
        foreach ($delimiters as $delimiter => &$count) {
            $count = $this->getCount(str_getcsv($firstLine, $delimiter));
        }
        $_delimiters = array_search(max($delimiters), $delimiters);
        $fileDelimeter = "";
        if ($_delimiters == ",") {
            $fileDelimeter = "comma";
        } elseif ($_delimiters == ";") {
            $fileDelimeter = "semicolon";
        } elseif ($_delimiters == ":") {
            $fileDelimeter = "colon";
        } elseif ($_delimiters == "|") {
            $fileDelimeter = "pipe";
        }

        if ($fileDelimeter == $tmpDelimeter) {
            return 1;
        } else {
            return 0;
        }
        fclose($handle);
    }

    private function getCount($count)
    {
        return count($count);
    }

    private function getBaseDir()
    {
        return $this->directoryList->getRoot() . "/var/import/";
    }

    /*
     *  getData form csv file
     */
    private function callFile($name, $fileName, $delimiter)
    {
        $firstColumn = 0;
        $data = [];
        $row = 1;
        $errorCount = 0;
        $checkOnce = 0;
        try {
            $file = fopen($this->basepath . $fileName, "r");
            $fp = file($this->basepath . $fileName, FILE_SKIP_EMPTY_LINES);
            while (!feof($file)) {
                if ($row <= $this->getCount($fp)) {
                    $row++;
                    if ($delimiter == "comma") {
                        $data = fgetcsv($file, 0, ",");
                    } elseif ($delimiter == "semicolon") {
                        $data = fgetcsv($file, 0, ";");
                    } elseif ($delimiter == "colon") {
                        $data = fgetcsv($file, 0, ":");
                    } elseif ($delimiter == "pipe") {
                        $data = fgetcsv($file, 0, "|");
                    }
                    $storecode = $this->getCount($this->getStoresid()) * 2 + 1;
                    $positionCode = $storecode + 1;
                    if (!empty($data[$positionCode])) {
                        if (is_numeric($data[$positionCode])) {
                            $position = $data[$positionCode];
                            array_pop($data);
                        } else {
                            $position = 0;
                        }
                    } else {
                        $position = 0;
                    }
                    if ($firstColumn != 0) {
                        $this->addAttributeValue($name, $data, $position, $storecode);
                    }
                    $firstColumn = 1;
                } else {
                    break;
                }
            }
            return 1;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->redirectErrorPage();
        }
    }

    /*
     *  check attribute options is exists then update either add new options
     */
    private function addAttributeValue($arg_attribute, $arg_value, $position, $storecode)
    {
        $argValueTemp = [];
        $attribute = $this->entityAttribute->load($arg_attribute);

        try {
            if (empty($attribute->getData()) || in_array(trim($arg_value[1]), $this->csvValues)) {
                return;
            }
            $this->csvValues[] = trim($arg_value[1]);
            $attribute_option = $this->sourceTable->setAttribute($attribute);

            if (!$this->attributeValueExists($arg_attribute, $arg_value, $position, $storecode)) {
                $tmpArg_value = count($arg_value);
                for ($i = 0; $i < $tmpArg_value; $i++) {
                    $i++;
                    if (isset($arg_value[$i]) && $arg_value[$i] != "") {
                        $argValueTemp[] = trim($arg_value[$i]); /*trim*/
                    }
                }

                $value['option'] = $argValueTemp;
                $result = ['value' => $value];
                $attribute->setData('option', $result);
                $attribute->save();
                $val_no = $arg_value[1];
                $this->updateValue($arg_attribute, $val_no, $arg_value, $position, $storecode);
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->redirectErrorPage();
        }
    }

    private function attributeValueExists($arg_attribute, $arg_value, $position, $storecode)
    {
        try {
            $attribute = $this->eavAttribute->load($arg_attribute);
            $attribute_option = $this->sourceTable->setAttribute($attribute);
            $options = $attribute_option->getAllOptions(false, true);
            foreach ($options as $option) {
                if ($option['label'] == trim($arg_value[1])) {
                    $this->updateValue($arg_attribute, $option['label'], $arg_value, $position, $storecode);
                    return $option['value'];
                }
            }
            return false;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->redirectErrorPage();
        }
    }

    /*
     *  check atrrbute option is DropDown, TextSwatch, or VisualSwatch
     */
    private function updateValue($arg_attribute, $option_val, $arg_value, $position, $storecode)
    {
        $productMetadata = $this->_objectManager->get('Magento\Framework\App\ProductMetadataInterface');
        $version = $productMetadata->getVersion();

        $resultRedirect = $this->resultRedirectFactory->create();
        $allStores = $this->getStoresid();

        $attr_model = $this->eavAttribute->load($arg_attribute);
        try {
            $attribute_option = $this->_objectManager->create("\Magento\Eav\Model\Entity\Attribute\Source\Table");
            $attribute_option->setAttribute($attr_model);
            $options = $attribute_option->getAllOptions(false, true);
            $val_no = 0;

            foreach ($options as $option) {
                if ($option['label'] == $option_val) {
                    $val_no = $option['value'];
                    break;
                }
            }
            if ($attr_model->getAdditionalData() != '') {
                if ($version < '2.2.0') {
                    $unserialData = unserialize($attr_model->getAdditionalData());
                } else {
                    $serializer = $this->_objectManager->get('Magento\Framework\Serialize\Serializer\Json');
                    $unserialData = $serializer->unserialize($attr_model->getAdditionalData());
                }

                if (isset($unserialData['swatch_input_type'])) {
                    if ($unserialData['swatch_input_type'] == 'text') {
                        $value_final = $this->textOption($allStores, $option_val, $arg_value, $position, $val_no);
                    }
                    if ($unserialData['swatch_input_type'] == 'visual') {
                        $value_final = $this->visualOption($allStores, $option_val, $arg_value, $position, $val_no);
                    }
                } else {
                    $value_final = $this->selectOption($allStores, $option_val, $arg_value, $position, $val_no);
                }
            } else {
                $value_final = $this->selectOption($allStores, $option_val, $arg_value, $position, $val_no);
            }
            $attr_model->addData($value_final);
            $attr_model->save();
            return true;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->redirectErrorPage();
        }
    }

    /*
     *  return data for VisualSwatch
     */
    private function visualOption($allStores, $option_val, $arg_value, $position, $val_no)
    {
        $value = [];
        $value[0] = trim($option_val);
        $swatchVisual = '';

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        try {
            $tmpAllStores = count($allStores);
            for ($i = 0; $i < ($tmpAllStores * 2); $i++) {
                if ($i == 0) {
                    $swatchVisual = $arg_value[$i];
                }
                $i++;
                if (isset($arg_value[$i])) {
                    if (isset($arg_value[$i + 2])) {
                        $value[] = $arg_value[$i + 2];
                    }
                }
            }
            $value = [$val_no => $value];
            $value = ['value' => $value];
            $value1['order'][$val_no] = $position;
            $value_final = array_merge($value, $value1);
            $dstPath = 'pub/media/tmp/catalog/product';
            $tmpPath = 'pub/media/import/' . $swatchVisual;

            if (file_exists($tmpPath) && $swatchVisual != '') {
                $swatchVisual = '/' . $swatchVisual;
                if (!file_exists($this->_directory->getAbsolutePath($dstPath))) {
                    mkdir($this->_directory->getAbsolutePath($dstPath), 0777, true);
                }
                copy($tmpPath, $dstPath . $swatchVisual);

                $media_dir = $objectManager->get('Magento\Store\Model\StoreManagerInterface')
                    ->getStore()
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

                $path = $media_dir . 'tmp/catalog/product' . $swatchVisual;
                $result['url'] = $path;
                $result['file'] = $swatchVisual . '.tmp';

                $swatchHelper = $objectManager->create('Magento\Swatches\Helper\Media');

                $newFile = $swatchHelper->moveImageFromTmp($result['file']);
                $swatchHelper->generateSwatchVariations($newFile);

                $value_final = ['optionvisual' => $value_final, 'swatchvisual' => ['value' => [$val_no => $newFile]]];
            } else {
                $value_final = ['optionvisual' => $value_final,
                    'swatchvisual' => ['value' => [$val_no => $swatchVisual]]];
            }

            return $value_final;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->redirectErrorPage();
        }
    }

    /*
     *  return data for DropDown
     */
    private function selectOption($allStores, $option_val, $arg_value, $position, $val_no)
    {
        $value = [];

        $value[0] = trim($option_val);

        try {
            $tmpAllStores = count($allStores);
            for ($i = 0; $i < ($tmpAllStores * 2); $i++) {
                $i++;
                if (isset($arg_value[($i + 2)])) {
                    $value[] = trim($arg_value[($i + 2)]); /*trim*/

                }
            }

            $value = [$val_no => $value];
            $value = ['value' => $value];
            $value1['order'][$val_no] = $position;

            $value_final = array_merge($value, $value1);
            $value_final = ['option' => $value_final];

            return $value_final;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->redirectErrorPage();
        }
    }

    /*
     *  return data for TextSwatch
     */
    private function textOption($allStores, $option_val, $arg_value, $position, $val_no)
    {

        $value = [];
        $value[0] = trim($option_val);
        $swatchValue = [];
        $j = 0;
        try {
            $tmpAllStores = count($allStores);
            for ($i = 0; $i < ($tmpAllStores * 2); $i++) {
                $i++;
                $j++;
                if (isset($arg_value[$i])) {
                    if ($arg_value[$i + 2]) {
                        $value[$j] = $arg_value[$i + 2];
                    }
                    if ($arg_value[$i + 1]) {
                        $swatchValue[$j] = $arg_value[$i + 1];
                    }
                }
            }

            $swatchValue[0] = $arg_value[0];

            $value = [$val_no => $value];
            $value = ['value' => $value];

            $value1['order'][$val_no] = $position;
            $value_final = array_merge($value, $value1);
            $swatchValue = [$val_no => $swatchValue];

            $swatchValue = ['value' => $swatchValue];
            $value_final = ['optiontext' => $value_final, 'swatchtext' => $swatchValue];
            return $value_final;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->redirectErrorPage();
        }
    }
    /*
     *  return data for number if storeview
     */
    private function getStoresid()
    {
        $isAvailable = $this->coreRegistry->registry('import_exportstore');
        if ($isAvailable) {
            return $isAvailable;
        } else {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $stores = $objectManager->create('Magento\Store\Model\Store');
            $store_id = [];
            foreach ($stores->getCollection() as $store) {
                array_push($store_id, $store->getStoreId());
            }
            $this->coreRegistry->register("import_exportstore", $store_id);
            return $store_id;
        }
    }

    private function redirectErrorPage()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $this->messageManager->addError(__("Please select correct csv file."));
        return $resultRedirect->setPath('importexportattributeoption/index/index');
    }
}
