<?php
namespace Emipro\Importexportattributeoption\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Filesystem\Io\File;

class Checkvalidation extends \Magento\Framework\App\Action\Action
{
    private $eavConfig;
    private $uploaderFactory;
    private $basepath;
    private $attributeProcess;
    private $attributeOptions;
    private $directoryList;
    private $entityAttribute;
    private $fileFactory;
    private $sourceTable;
    private $coreRegistry = null;
    private $eavAttribute;
    private $fileId;
    private $resultRawFactory;
    private $csvWriter;
    private $file;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Eav\Model\Config $attribute,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Eav\Model\Entity\Attribute $entityAttribute,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $eavAttribute,
        \Magento\Eav\Model\Entity\Attribute\Source\Table $sourceTable,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\Csv $csvWriter,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        FileFactory $fileFactory,
        File $file
    ) {
        $this->file = $file;
        $this->eavConfig = $attribute;
        $this->fileFactory = $fileFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->csvWriter = $csvWriter;
        $this->resultFactory = $resultFactory;
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

    private function getBaseDir()
    {
        return $this->directoryList->getRoot() . "/var/import/";
    }

    public function execute()
    {

        $files = $this->getRequest()->getFiles();
        if (isset($files['fileToUpload'])) {
            $fileName = $files['fileToUpload']['name'];
            $fileExt = strtolower(substr(strrchr($fileName, "."), 1));
            $fileNamewoe = rtrim($fileName, $fileExt);
            $fileName = "attribute_options_import_" . date("Y-m-d") . ".csv";
            $uploader = $this->uploaderFactory->create(['fileId' => $files['fileToUpload']]);
            $this->fileId = $files['fileToUpload'];
            $uploader->setAllowedExtensions(['csv']);
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);
            if (!is_dir($this->basepath)) {
                mkdir($this->basepath, 0777, true);
            }

            $uploader->save($this->basepath . "/", $fileName);
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
            fclose($handle);
            $_result = $this->callFile($fileName, $_delimiters);
        }
    }

    private function getCount($count)
    {
        return count($count);
    }

    private function callFile($fileName, $_delimiters)
    {
        $errorCount = $this->checkCsvData($fileName, $_delimiters);
        if ($errorCount == 0) {
            $data = "valid";
            print_r($data);
        } else {
            print_r($errorCount);
        }
    }

    private function checkCsvData($fileName, $_delimiters)
    {
        $row = 1;

        $file = fopen($this->basepath . $fileName, "r");
        $fp = file($this->basepath . $fileName, FILE_SKIP_EMPTY_LINES);

        $csv = array_map("str_getcsv", file($this->basepath . $fileName, FILE_SKIP_EMPTY_LINES));
        $keys = array_shift($csv);
        $cnt_keys = count($keys);

        while (!feof($file)) {
            $tmpCount = $this->getCount($fp);
            if ($row <= $tmpCount) {
                $data = fgetcsv($file, 0, $_delimiters);
                if (!isset($data[1]) || $data[1] == "") {
                    return $row;
                }
                $row++;
            } else {
                break;
            }
        }
        if ($cnt_keys == 6) {
            $this->createCsv($fileName, $_delimiters);
        }
        return 0;
    }
    private function createCsv($fileName, $_delimiters)
    {
        $fileName1 = "tmp.csv";
        $file = fopen($this->basepath . $fileName1, "w");
        $row = 1;
        $tmpdata = "";
        if (($handle = fopen($this->basepath . $fileName, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $num = $this->getCount($data);
                $row++;
                for ($c = 1; $c < $num; $c++) {
                    $tmpdata .= $data[$c] . $_delimiters;
                    $tmpcsvData = rtrim($tmpdata, $_delimiters);
                }
                fputcsv($file, explode($_delimiters, $tmpcsvData));
                $tmpdata = "";
            }
            fclose($handle);
        }
        fclose($file);
        rename($this->basepath . $fileName1, $this->basepath . $fileName);
    }
}
