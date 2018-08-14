<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */

namespace Amasty\Geoip\Model;

use Magento\Framework\App\Config\ReinitableConfigInterface;

class Import extends \Magento\Framework\Model\AbstractModel
{
    protected static $_sessionKey = 'am_geoip_import_process_%key%';

    protected $_tablePrefix;

    protected $_modelsCols = [
        'block'    => [
            'start_ip_num',
            'end_ip_num',
            'geoip_loc_id',
            'postal_code',
            'latitude',
            'longitude'
        ],
        'location' => [
            'geoip_loc_id',
            'country',
            'city'
        ]
    ];

    protected $_rowsPerCsvTransaction = 10000;
    protected $_rowsPerTransaction = 250;

    /**
     * Resource model of config data
     *
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface
     */
    protected $configInterface;

    /**
     * Database write connection
     *
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Core Date
     *
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $coreDate;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Magento\Framework\App\DeploymentConfig $_deploymentConfig
     */
    protected $_deploymentConfig;

    /**
     * @var ReinitableConfigInterface $reinitableInterface
     */
    protected $reinitableInterface;

    /**
     * @var \Amasty\Geoip\Helper\Data $helper
     */
    protected $helper;

    protected $_firstTime = true;

    /**
     * Import constructor.
     * @param \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configInterface
     * @param ReinitableConfigInterface $reinitableConfig
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $coreDate
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Magento\Framework\App\DeploymentConfig $deploymentConfig
     * @param \Amasty\Geoip\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configInterface,
        \Magento\Framework\App\Config\ReinitableConfigInterface $reinitableConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTime $coreDate,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Framework\App\DeploymentConfig $deploymentConfig,
        \Amasty\Geoip\Helper\Data $helper
    ) {
        $this->configInterface = $configInterface;
        $this->reinitableInterface = $reinitableConfig;
        $this->resource = $resource;
        $this->scopeConfig = $scopeConfig;
        $this->coreDate = $coreDate;
        $this->backendSession = $backendSession;
        $this->_deploymentConfig = $deploymentConfig;
        $this->helper = $helper;
    }

    protected static function getSessionKey($table)
    {
        return strtr(self::$_sessionKey, ['%key%' => $table]);
    }

    public function startProcess($table, $filePath, $ignoredLines = 0, $action)
    {
        $allowedTableTypes = ['block', 'location'];
        if (!in_array($table, $allowedTableTypes)) {
            throw new \Exception('Invalid table type');
        }

        $importProcess = [
            'position'    => 0,
            'tmp_table'   => null,
            'rows_count'  => $this->_getRowsCount($filePath),
            'current_row' => 0
        ];

        if ($action == 'import') {
            $write = $this->resource->getConnection();
            $query = 'SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME LIKE \'%amasty_geoip_'. $table .'_%\'';
            $columns = $write->fetchCol($query);
            $oldTemporary = implode(', ', $columns);
            if (!empty($oldTemporary)) {
                $delete = "DROP TABLE IF EXISTS $oldTemporary";
                $write->query($delete);
            }

            if (($handle = fopen($filePath, "r")) !== false) {
                $tmpTableName = $this->_prepareImport($table);

                while ($ignoredLines > 0 && ($data = fgetcsv($handle, 0, ",")) !== false) {
                    $ignoredLines--;
                }

                $importProcess['position'] = ftell($handle);
                $importProcess['tmp_table'] = $tmpTableName;
            }
        }

        $this->_truncateTable('amasty_geoip_block');
        $this->_truncateTable('amasty_geoip_location');

        $this->helper->flushConfigCache();
        $this->_saveInDb($table, $importProcess);

        return $importProcess;
    }

    public function importTableHasData()
    {
        $write         = $this->resource->getConnection();
        $blockTable    = $this->resource->getTableName('amasty_geoip_block');
        $locationTable = $this->resource->getTableName('amasty_geoip_location');
        $countBlock    = "SELECT count(block_id) as count_block_id FROM $blockTable";
        $countLocation = "SELECT count(location_id) as count_location_id FROM $locationTable";
        $block         = $write->fetchCol($countBlock);
        $location      = $write->fetchCol($countLocation);
        if (array_key_exists(0, $block) && array_key_exists(0, $location)) {
            if ($block[0] && $location[0]) {
                return true;
            }
        }

        return false;
    }

    protected function _getRowsCount($filePath)
    {
        $linecount = 0;
        $handle = fopen($filePath, "r");
        while (!feof($handle)) {
            $line = fgets($handle);
            $linecount++;
        }
        fclose($handle);

        return $linecount;
    }

    protected function _prepareImport($table)
    {
        $write = $this->resource->getConnection();

        $targetTable = $this->resource->getTableName('amasty_geoip_' . $table);

        $tmpTableName = uniqid($targetTable . '_');

        $query = 'create table ' . $tmpTableName . ' like ' . $targetTable;
        $write->query($query);

        $query = 'alter table ' . $tmpTableName . ' engine innodb';
        $write->query($query);

        return $tmpTableName;
    }

    public function doProcess($table, $filePath, $action)
    {
        $ret = [];
        if (($handle = fopen($filePath, "r")) !== false) {
            $this->helper->flushConfigCache();
            $importProcess = $this->_getFromDb($table);
            $write = $this->resource->getConnection();

            if (!$importProcess) {
                throw new \Exception('run start before');
            }
            $tmpTableName = $importProcess['tmp_table'];
            try {
                $position = $importProcess['position'];
                fseek($handle, $position);
                $transactionIterator = 0;
                $write->beginTransaction();
                if ($action == 'import') {
                    while (($data = fgetcsv($handle, 0, ",")) !== false) {
                        $this->_importItem($table, $tmpTableName, $data, $action);
                        $transactionIterator++;
                        if ($transactionIterator >= $this->_rowsPerCsvTransaction) {
                            break;
                        }
                    }
                } else {
                    $this->_tablePrefix = (string)$this->_deploymentConfig->get(
                        \Magento\Framework\Config\ConfigOptionsListConstants::CONFIG_PATH_DB_PREFIX
                    );
                    while (($data = fgets($handle)) !== false) {
                        $this->_importItem($table, $tmpTableName, $data, $action);
                        $transactionIterator++;
                        if ($transactionIterator >= $this->_rowsPerTransaction) {
                            break;
                        }
                    }
                }
                $write->commit();
                if ($this->_rowsPerTransaction > $importProcess['rows_count']) {
                    $importProcess['current_row'] = $importProcess['rows_count'];
                }
                $importProcess['current_row'] += $transactionIterator;
                $importProcess['position'] = ftell($handle);
//                $this->helper->flushConfigCache();
                $this->_saveInDb($table, $importProcess);
                $ret = $importProcess;
            } catch (\Exception $e) {
                $write->rollback();
                if ($action == 'import') {
                    $this->_destroyImport($table, $tmpTableName);
                }
                throw new \Exception($e->getMessage());
            }
        }

        return $ret;
    }

    public function commitProcess($table, $isDownload = false)
    {
        $ret = false;
        $this->helper->flushConfigCache();
        $importProcess = $this->_getFromDb($table);

        if ($importProcess) {
            $tmpTableName = $importProcess['tmp_table'];

            if ($isDownload) {
                $configDate = 'date_download';
            } else {
                $configDate = 'date';
            }

            try {
                $this->configInterface
                    ->saveConfig('amgeoip/import/' . $table, 1, 'default', 0)
                    ->saveConfig('amgeoip/import/' . $configDate, $this->coreDate->gmtDate(), 'default', 0)
                ;

                $this->reinitableInterface->reinit();

                if (!$isDownload) {
                    $this->_doneImport($table, $tmpTableName);
                }

            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }

            $ret = true;
        } else {
            throw new \Exception('run start before');
        }

        return $ret;
    }

    protected function _doneImport($table, $tmpTableName)
    {
        $write = $this->resource->getConnection();

        $targetTable = $this->resource
            ->getTableName('amasty_geoip_' . $table)
        ;

        $query = 'delete from ' . $targetTable;
        $write->query($query);

        $query = 'insert into ' . $targetTable . ' select * from ' . $tmpTableName;
        $write->query($query);

        $query = 'drop table if exists ' . $tmpTableName;
        $write->query($query);
    }

    protected function _truncateTable($table)
    {
        $write = $this->resource->getConnection();
        $write->truncateTable($this->resource->getTableName($table));
    }

    protected function _importItem($table, $tmpTableName, &$data, $action)
    {
        $write = $this->resource->getConnection();

        $query = $data;

        if ($action == 'import') {
            if ($table == 'block' && is_array($data) && isset($data[0])) {
                list($ip, $mask) = explode('/', $data[0]);
                $ip2long = ip2long($ip);
                $min     = ($ip2long >> (32 - $mask)) << (32 - $mask);
                $max     = $ip2long | ~(-1 << (32 - $mask));
                $newData = [$min, $max, $data[1], $data[6], $data[7], $data[8]];
                $data    = $newData;
            } elseif ($table == 'location' && is_array($data)) {
                $newData = [$data[0], $data[4], $data[10]];
                $data    = $newData;
            }

            $query = 'insert into `' . $tmpTableName . '`' .
                '(`' . implode('`, `', $this->_modelsCols[$table]) . '`) VALUES ' .
                '(?)';

            $query = $write->quoteInto($query, $data);
        } elseif ($this->_tablePrefix) { //fix for tables with prefixes
            $query = "INSERT INTO `" . $this->_tablePrefix . substr($query, 13);
        }

        $write->query($query);
    }

    protected function _destroyImport($table, $tmpTableName)
    {
        $write = $this->resource->getConnection();

        $query = 'drop table if exists ' . $tmpTableName;
        $write->query($query);

        $this->_clearDb();
    }

    protected function _saveInDb($table, $importProcess)
    {
        $this->configInterface->saveConfig('amgeoip/import/position/' . $table, $importProcess['position'], 'default', 0);
        $this->configInterface->saveConfig('amgeoip/import/tmp_table/' . $table, $importProcess['tmp_table'], 'default', 0);
        $this->configInterface->saveConfig('amgeoip/import/rows_count/' . $table, $importProcess['rows_count'], 'default', 0);
        $this->configInterface->saveConfig('amgeoip/import/current_row/' . $table, $importProcess['current_row'], 'default', 0);
    }

    protected function _getFromDb($table)
    {
        $importProcess = [];
        $importProcess['position'] = $this->scopeConfig->getValue('amgeoip/import/position/' . $table, 'default', 0);
        $importProcess['tmp_table'] = $this->scopeConfig->getValue('amgeoip/import/tmp_table/' . $table, 'default', 0);
        $importProcess['rows_count'] = $this->scopeConfig->getValue('amgeoip/import/rows_count/' . $table, 'default', 0);
        $importProcess['current_row'] = $this->scopeConfig->getValue('amgeoip/import/current_row/' . $table, 'default', 0);
        return $importProcess;
    }

    protected function _clearDb()
    {
        $this->configInterface->deleteConfig('amgeoip/import/position/location', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/position/block', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/tmp_table/location', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/tmp_table/block', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/rows_count/location', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/rows_count/block', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/current_row/location', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/current_row/block', 'default', 0);
    }
}
