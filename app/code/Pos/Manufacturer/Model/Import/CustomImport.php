<?php
namespace Pos\Manufacturer\Model\Import;
 
use Pos\Manufacturer\Model\Import\CustomImport\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
 
class CustomImport extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{
   // const ID = 'id';
     const ID = 'id';
    const MANUFACTURER = 'manufacturer';
    const MODEL = 'model';
    const PRODUCT_IDS = 'product_ids';
    const TABLE_ENTITY = 'manufacturer_manufacturer';
 
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_ID_IS_EMPTY => 'Empty',
    ];
 
  //  protected $_permanentAttributes = [self::ID];
     
    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;
     
    /**
     * Valid column names
     *
     * @array
     */
    protected $validColumnNames = [
        self::ID,
        self::MANUFACTURER,
        self::MODEL,
        self::PRODUCT_IDS,
    ];
 
    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;
     
    protected $_validators = [];
     
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_connection;
     
    protected $_resource;
     
    /**
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
    }
 
    public function getValidColumnNames() {
        return $this->validColumnNames;
    }
 
    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode() {
        return 'manu_import';
    }
 
    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum) {
        $title = false;
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }
         
        $this->_validatedRows[$rowNum] = true;
 
        if (!isset($rowData[self::ID]) || empty($rowData[self::ID])) {
            $this->addRowError(ValidatorInterface::ERROR_MESSAGE_IS_EMPTY, $rowNum);
            return false;
        }
        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }
 
    /**
     * Create advanced question data from raw data.
     *
     * @throws \Exception
     * @return bool Result of operation.
     */
    protected function _importData() {
        if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->deleteEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->replaceEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $this->getBehavior()) {
            $this->saveEntity();
        }
        return true;
    }
 
    /**
     * Save question
     *
     * @return $this
     */
    public function saveEntity() {
        $this->saveAndReplaceEntity();
        return $this;
    }
 
    /**
     * Replace question
     *
     * @return $this
     */
    public function replaceEntity() {
        $this->saveAndReplaceEntity();
        return $this;
    }
 
    /**
     * Deletes question data from raw data.
     *
     * @return $this
     */
    public function deleteEntity() {
        $ids = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $this->validateRow($rowData, $rowNum);
                if (!$this->getErrorAggregator()->isRowInvalid($rowNum)) {
                    $rowId = $rowData[self::ID];
                    $ids[] = $rowId;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                }
            }
        }
        if ($ids) {
            $this->deleteEntityFinish(array_unique($ids),self::TABLE_ENTITY);
        }
        return $this;
    }
 
    /**
     * Save and replace question
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function saveAndReplaceEntity() {
        $behavior = $this->getBehavior();
        $ids = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError(ValidatorInterface::ERROR_MESSAGE_IS_EMPTY, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }
                $rowId= $rowData[self::ID];
                $ids[] = $rowId;
                $entityList[$rowId][] = [
                self::ID => $rowData[self::ID],
                  self::MANUFACTURER => $rowData[self::MANUFACTURER],
                  self::MODEL => $rowData[self::MODEL],
                  self::PRODUCT_IDS => $rowData[self::PRODUCT_IDS], 
                ];
            }
            if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior) {
                if ($ids) {
                    if ($this->deleteEntityFinish(array_unique(  $ids), self::TABLE_ENTITY)) {
                        $this->saveEntityFinish($entityList, self::TABLE_ENTITY);
                    }
                }
            } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior) {

                $this->saveEntityFinish($entityList, self::TABLE_ENTITY);
            }
        }
        return $this;
    }
 
    /**
     * Save question
     *
     * @param array $priceData
     * @param string $table
     * @return $this
     */
    protected function saveEntityFinish(array $entityData, $table) {
        if ($entityData) {
            $tableName = $this->_connection->getTableName($table);
            $entityIn = [];
            foreach ($entityData as $id => $entityRows) {
                    foreach ($entityRows as $row) {
                        $entityIn[] = $row;
                    }
            }
            if ($entityIn) {
                $this->_connection->insertOnDuplicate($tableName, $entityIn,[
                self::ID,
                self::MANUFACTURER,
                self::MODEL,
                self::PRODUCT_IDS
            ]);
            }
        }
        return $this;
    }
 
    protected function deleteEntityFinish(array $ids, $table) {
 
        if ($table && $ids) {
            try {
                $this->countItemsDeleted += $this->_connection->delete(
                    $this->_connection->getTableName($table),
                    $this->_connection->quoteInto('entity_id IN (?)', $ids)
                );
                return true;
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }
}