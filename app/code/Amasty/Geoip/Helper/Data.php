<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */


namespace Amasty\Geoip\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const BLOCK_FILE = 'amgeoip/general/block_file_url';
    const LOCATION_FILE = 'amgeoip/general/location_file_url';
    const BLOCK_HASH = 'amgeoip/general/block_hash_url';
    const LOCATION_HASH = 'amgeoip/general/location_hash_url';

    public $_geoipRequiredFiles = [
        'block'    => 'amasty_geoip_block.sql',
        'location' => 'amasty_geoip_location.sql'
    ];

    protected $_geoipCsvFiles = [
        'block'    => 'GeoLite2-City-Blocks-IPv4.csv',
        'location' => 'GeoLite2-City-Locations-en.csv'
    ];

    public $_geoipIgnoredLines = [
        'block'    => 2,
        'location' => 2
    ];

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * Resource model of config data
     *
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface
     */
    protected $_resource;

    /**
     * @var \Magento\Framework\App\Cache\StateInterface $_state
     */
    protected $_state;

    /**
     * @var bool
     */
    protected $_cacheEnabled;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $fileDriver;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context                        $context
     * @param \Magento\Framework\App\Filesystem\DirectoryList              $directoryList
     * @param \Magento\Framework\App\Config\ConfigResource\ConfigInterface $_resource
     * @param \Magento\Framework\App\Cache\StateInterface                  $state
     * @param \Magento\Framework\Filesystem\Driver\File                    $fileDriver
     * @param \Magento\Framework\ObjectManagerInterface                    $objectManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $_resource,
        \Magento\Framework\App\Cache\StateInterface $state,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        parent::__construct($context);
        $this->directoryList = $directoryList;
        $this->_resource = $_resource;
        $this->fileDriver = $fileDriver;
        $this->_state = $state;
        $this->objectManager = $objectManager;
    }

    /**
     * @return string
     */
    public function getUrlBlockFile()
    {
        return $this->scopeConfig->getValue(self::BLOCK_FILE);
    }

    /**
     * @return string
     */
    public function getUrlLocationFile()
    {
        return $this->scopeConfig->getValue(self::LOCATION_FILE);
    }

    /**
     * @return string
     */
    public function getHashUrlBlock()
    {
        return $this->scopeConfig->getValue(self::BLOCK_HASH);
    }

    /**
     * @return string
     */
    public function getHashUrlLocation()
    {
        return $this->scopeConfig->getValue(self::LOCATION_HASH);
    }

    /**
     * @param bool $flushCache
     * @return bool
     */
    public function isDone($flushCache = true)
    {
        if ($flushCache) {
            $this->flushConfigCache();
        }

        return ($this->scopeConfig->getValue('amgeoip/import/location')
            && $this->scopeConfig->getValue('amgeoip/import/block'));
    }

    public function resetDone()
    {
        $this->_resource->saveConfig('amgeoip/import/block', 0, 'default', 0);
        $this->_resource->saveConfig('amgeoip/import/location', 0, 'default', 0);
    }

    public function getDirPath($action)
    {
        $varDir = $this->directoryList->getPath('var');

        if ($action == 'download_and_import') {
            $dir = $varDir . DIRECTORY_SEPARATOR . 'amasty' . DIRECTORY_SEPARATOR . 'geoip' . DIRECTORY_SEPARATOR
                . 'amasty_files';
        } else {
            $dir = $varDir . DIRECTORY_SEPARATOR . 'amasty' . DIRECTORY_SEPARATOR . 'geoip';
        }

        return $dir;
    }

    public function getCsvFilePath($type, $action)
    {
        $dir  = $this->getDirPath($action);
        $file = $dir . DIRECTORY_SEPARATOR . $this->_geoipCsvFiles[$type];

        return $file;
    }

    public function getFilePath($type, $action)
    {
        $dir = $this->getDirPath($action);
        $file = $dir . DIRECTORY_SEPARATOR . $this->_geoipRequiredFiles[$type];
        return $file;
    }

    /**
     * is file exist
     *
     * @param string $filePath
     *
     * @return bool
     */
    public function isFileExist($filePath)
    {
        try {
            return $this->fileDriver->isExists($filePath);
        } catch (\Magento\Framework\Exception\FileSystemException $exception) {
            return false;
        }
    }

    public function flushConfigCache()
    {
        if (class_exists(\Magento\Config\App\Config\Type\System::class)) {
            $this->objectManager->get(\Magento\Config\App\Config\Type\System::class)->clean();
        } else {
            $this->objectManager->get(\Magento\Framework\App\Cache\Type\Config::class)
                ->clean(
                \Zend_Cache::CLEANING_MODE_MATCHING_TAG,
                ['config_scopes']
            );
        }
    }

    public function isCacheEnabled($type)
    {
        if (!isset($this->_cacheEnabled)) {
            $this->_cacheEnabled = $this->_state->isEnabled($type);
        }

        return $this->_cacheEnabled;
    }
}
