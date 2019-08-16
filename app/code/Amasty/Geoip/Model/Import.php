<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */


namespace Amasty\Geoip\Model;

use Amasty\Geoip\Helper\Data;
use Exception;
use Magento\Backend\Model\Session;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Config\ConfigOptionsListConstants;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class Import
 */
class Import extends AbstractModel
{
    /**
     * @var string
     */
    protected $_tablePrefix;

    /**
     * @var int
     */
    protected $_rowsPerTransaction = 10000;

    /**
     * Resource model of config data
     *
     * @var ConfigInterface
     */
    protected $configInterface;

    /**
     * Database write connection
     *
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Core Date
     *
     * @var DateTime
     */
    protected $coreDate;

    /**
     * @var Session
     */
    protected $backendSession;

    /**
     * @var DeploymentConfig $_deploymentConfig
     */
    protected $_deploymentConfig;

    /**
     * @var ReinitableConfigInterface $reinitableInterface
     */
    protected $reinitableInterface;

    /**
     * @var Data $helper
     */
    protected $helper;

    /**
     * @var bool
     */
    protected $_firstTime = true;

    /**
     * @var File
     */
    private $driverFile;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * Import constructor.
     *
     * @param ConfigInterface $configInterface
     * @param ReinitableConfigInterface $reinitableConfig
     * @param ResourceConnection $resource
     * @param ScopeConfigInterface $scopeConfig
     * @param DateTime $coreDate
     * @param Session $backendSession
     * @param DeploymentConfig $deploymentConfig
     * @param Data $helper
     * @param File $driverFile
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        ConfigInterface $configInterface,
        ReinitableConfigInterface $reinitableConfig,
        ResourceConnection $resource,
        ScopeConfigInterface $scopeConfig,
        DateTime $coreDate,
        Session $backendSession,
        DeploymentConfig $deploymentConfig,
        Data $helper,
        File $driverFile,
        ManagerInterface $messageManager
    ) {
        $this->configInterface = $configInterface;
        $this->reinitableInterface = $reinitableConfig;
        $this->resource = $resource;
        $this->scopeConfig = $scopeConfig;
        $this->coreDate = $coreDate;
        $this->backendSession = $backendSession;
        $this->_deploymentConfig = $deploymentConfig;
        $this->helper = $helper;
        $this->driverFile = $driverFile;
        $this->messageManager = $messageManager;
    }

    /**
     * @param $table
     * @param $filePath
     * @param $action
     * @param int $ignoredLines
     *
     * @return array
     *
     * @throws Exception
     */
    public function startProcess($table, $filePath, $action, $ignoredLines = 0)
    {
        $allowedTableTypes = ['block', 'location', 'block_v6'];
        if (!in_array($table, $allowedTableTypes)) {
            throw new \Magento\Framework\Exception\LocalizedException('Invalid table type');
        }

        $importProcess = [
            'position'    => 0,
            'tmp_table'   => null,
            'rows_count'  => $this->_getRowsCount($filePath),
            'current_row' => 0
        ];
        $write = $this->resource->getConnection();
        $columns = $write->getTables('amasty_geoip_' . $table . '_');
        foreach ($columns as $key => $column) {
            if ($table == 'block' && preg_match('/.+_block_v6/', $column)) {
                unset($columns[$key]);
            }
        }
        $oldTemporary = implode(', ', $columns);

        if (!empty($oldTemporary)) {
            $write->dropTable($oldTemporary);
        }

        if (($handle = $this->driverFile->fileOpen($filePath, "r")) !== false) {
            $tmpTableName = $this->_prepareImport($table);

            while ($ignoredLines > 0 && ($data = $this->driverFile->fileGetCsv($handle, 0, ",")) !== false) {
                $ignoredLines--;
            }

            $importProcess['position'] = ftell($handle);
            $importProcess['tmp_table'] = $tmpTableName;
        }

        $write->truncateTable($this->resource->getTableName('amasty_geoip_block'));
        $write->truncateTable($this->resource->getTableName('amasty_geoip_block_v6'));
        $write->truncateTable($this->resource->getTableName('amasty_geoip_location'));

        $this->helper->flushConfigCache();
        $this->_saveInDb($table, $importProcess);

        return $importProcess;
    }

    /**
     * @return bool
     */
    public function importTableHasData()
    {
        $write = $this->resource->getConnection();
        $blockTable = $this->resource->getTableName('amasty_geoip_block');
        $blockV6Table = $this->resource->getTableName('amasty_geoip_block_v6');
        $locationTable = $this->resource->getTableName('amasty_geoip_location');

        $countBlock = $write->select('count(block_id)')->from($blockTable)->limit(1);
        $countBlockV6 = $write->select('count(block_id)')->from($blockV6Table)->limit(1);
        $countLocation = $write->select('count(location_id)')->from($locationTable)->limit(1);

        $block = $write->fetchCol($countBlock);
        $blockV6 = $write->fetchCol($countBlockV6);
        $location = $write->fetchCol($countLocation);

        return array_key_exists(0, $block)
            && array_key_exists(0, $blockV6)
            && array_key_exists(0, $location)
            && $block[0]
            && $location[0];
    }

    /**
     * @param $filePath
     *
     * @return int
     */
    protected function _getRowsCount($filePath)
    {
        $lineCount = 0;
        $handle = $this->driverFile->fileOpen($filePath, "r");
        while (!feof($handle)) {
            $this->driverFile->fileGetCsv($handle);
            $lineCount++;
        }
        $this->driverFile->fileClose($handle);

        return $lineCount;
    }

    /**
     * @param $table
     *
     * @return string
     */
    protected function _prepareImport($table)
    {
        $write = $this->resource->getConnection();

        $targetTable = $this->resource->getTableName('amasty_geoip_' . $table);

        $tmpTableName = uniqid($targetTable . '_');

        //@codingStandardsIgnoreStart
        $query = 'create table ' . $tmpTableName . ' like ' . $targetTable;
        //@codingStandardsIgnoreEnd
        $write->query($query);

        $write->changeTableEngine($tmpTableName, 'innodb');

        return $tmpTableName;
    }

    /**
     * @param $table
     * @param $filePath
     * @param $action
     *
     * @return array
     * @throws Exception
     */
    public function doProcess($table, $filePath, $action)
    {
        $ret = [];
        if (($handle = $this->driverFile->fileOpen($filePath, "r")) !== false) {
            $this->helper->flushConfigCache();
            $importProcess = $this->_getFromDb($table);

            if (!$importProcess) {
                throw new \Magento\Framework\Exception\LocalizedException('run start before');
            }
            $tmpTableName = $importProcess['tmp_table'];
            try {
                $position = $importProcess['position'];
                fseek($handle, $position);
                $transactionIterator = 0;
                if ($action != 'import') {
                    $this->_tablePrefix = (string)$this->_deploymentConfig->get(
                        ConfigOptionsListConstants::CONFIG_PATH_DB_PREFIX
                    );
                }
                $dataForImport = [];
                while (($data = $this->driverFile->fileGetCsv($handle, 0, ",")) !== false) {
                    $dataForImport[] = $data;
                    $transactionIterator++;

                    if ($transactionIterator >= $this->_rowsPerTransaction) {
                        $this->_importItem($table, $tmpTableName, $dataForImport);
                        break;
                    }
                }
                if ($this->_rowsPerTransaction > $importProcess['rows_count']) {
                    $importProcess['current_row'] = $importProcess['rows_count'];
                }
                $importProcess['current_row'] += $transactionIterator;
                $importProcess['position'] = ftell($handle);
                $this->_saveInDb($table, $importProcess);
                $ret = $importProcess;
            } catch (Exception $e) {
                if ($action == 'import') {
                    $this->_destroyImport($tmpTableName);
                }
                throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
            }
        }

        return $ret;
    }

    /**
     * @param $table
     * @param bool $isDownload
     *
     * @return bool
     * @throws Exception
     */
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
                    ->saveConfig('amgeoip/import/' . $configDate, $this->coreDate->gmtDate(), 'default', 0);

                $this->reinitableInterface->reinit();

                $this->_doneImport($table, $tmpTableName);

            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }

            $ret = true;
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(__('run start before'));
        }

        return $ret;
    }

    /**
     * @param $table
     * @param $tmpTableName
     */
    protected function _doneImport($table, $tmpTableName)
    {
        $write = $this->resource->getConnection();

        $targetTable = $this->resource->getTableName('amasty_geoip_' . $table);

        if ($write->isTableExists($tmpTableName)) {
            $write->dropTable($targetTable);

            $write->renameTable($tmpTableName, $targetTable);
        }
    }

    /**
     * @param $table
     * @param $tmpTableName
     * @param $dataForImport
     */
    protected function _importItem($table, $tmpTableName, &$dataForImport)
    {
        $dataForInsert = [];
        $write = $this->resource->getConnection();

        foreach ($dataForImport as $data) {
            if ($table == 'block' && is_array($data) && isset($data[0])) {
                list($ip, $mask) = explode('/', $data[0]);
                $ip2long = ip2long($ip);
                $min = ($ip2long >> (32 - $mask)) << (32 - $mask);
                $max = $ip2long | ~(-1 << (32 - $mask));

                $dataForInsert[] =  [
                    'start_ip_num' => $min,
                    'end_ip_num'   => $max,
                    'geoip_loc_id' => $data[1],
                    'postal_code'  => $data[6],
                    'latitude'     => $data[7],
                    'longitude'    => $data[8]
                ];
            } elseif ($table == 'location' && is_array($data)) {
                $dataForInsert[] = [
                    'geoip_loc_id' => $data[0],
                    'country'      => $data[4],
                    'city'         => $data[10]
                ];
            } elseif ($table == 'block_v6' && is_array($data) && isset($data[0])) {
                list($ip, $mask) = explode('/', $data[0]);

                $firstAddrBin = inet_pton($ip);
                //@codingStandardsIgnoreStart
                $elem = unpack('H*', $firstAddrBin);
                //@codingStandardsIgnoreEnd
                $firstAddrHex = reset($elem);
                $firstAddrStr = inet_ntop($firstAddrBin);
                $flexBits = 128 - $mask;
                $lastAddrHex = $firstAddrHex;
                $pos = 31;

                while ($flexBits > 0) {
                    $orig = substr($lastAddrHex, $pos, 1);
                    $origVal = hexdec($orig);
                    $newVal = $origVal | (pow(2, min(4, $flexBits)) - 1);
                    $new = dechex($newVal);
                    $lastAddrHex = substr_replace($lastAddrHex, $new, $pos, 1);
                    $flexBits -= 4;
                    $pos--;
                }

                $lastAddrBin = pack('H*', $lastAddrHex);
                $lastAddrStr = inet_ntop($lastAddrBin);

                $dataForInsert[] = [
                    'start_ip_num' => $this->helper->getLongIpV6($firstAddrStr),
                    'end_ip_num'   => $this->helper->getLongIpV6($lastAddrStr),
                    'geoip_loc_id' => $data[1],
                    'postal_code'  => $data[6],
                    'latitude'     => $data[7],
                    'longitude'    => $data[8]
                ];
            }
        }

        $write->insertMultiple($tmpTableName, $dataForInsert);
    }

    /**
     * @param $tmpTableName
     */
    protected function _destroyImport($tmpTableName)
    {
        $write = $this->resource->getConnection();

        $write->dropTable($tmpTableName);

        $this->_clearDb();
    }

    /**
     * @param $table
     * @param $importProcess
     */
    protected function _saveInDb($table, $importProcess)
    {
        $this->configInterface->saveConfig(
            'amgeoip/import/position/' . $table,
            $importProcess['position'],
            'default',
            0
        );
        $this->configInterface->saveConfig(
            'amgeoip/import/tmp_table/' . $table,
            $importProcess['tmp_table'],
            'default',
            0
        );
        $this->configInterface->saveConfig(
            'amgeoip/import/rows_count/' . $table,
            $importProcess['rows_count'],
            'default',
            0
        );
        $this->configInterface->saveConfig(
            'amgeoip/import/current_row/' . $table,
            $importProcess['current_row'],
            'default',
            0
        );
    }

    /**
     * @param $table
     *
     * @return array
     */
    protected function _getFromDb($table)
    {
        $importProcess = [];
        $importProcess['position'] = $this->scopeConfig->getValue(
            'amgeoip/import/position/' . $table,
            'default',
            0
        );
        $importProcess['tmp_table'] = $this->scopeConfig->getValue(
            'amgeoip/import/tmp_table/' . $table,
            'default',
            0
        );
        $importProcess['rows_count'] = $this->scopeConfig->getValue(
            'amgeoip/import/rows_count/' . $table,
            'default',
            0
        );
        $importProcess['current_row'] = $this->scopeConfig->getValue(
            'amgeoip/import/current_row/' . $table,
            'default',
            0
        );

        return $importProcess;
    }

    /**
     *
     */
    protected function _clearDb()
    {
        $this->configInterface->deleteConfig('amgeoip/import/position/location', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/position/block', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/position/block_v6', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/tmp_table/location', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/tmp_table/block', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/tmp_table/block_v6', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/rows_count/location', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/rows_count/block', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/rows_count/block_v6', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/current_row/location', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/current_row/block', 'default', 0);
        $this->configInterface->deleteConfig('amgeoip/import/current_row/block_v6', 'default', 0);
    }
}
