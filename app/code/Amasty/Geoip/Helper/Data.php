<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */


namespace Amasty\Geoip\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Config\App\Config\Type\System;
use Magento\Framework\App\Cache\Type\Config;

/**
 * Class Data
 */
class Data extends AbstractHelper
{
    const BLOCK_FILE = 'amgeoip/general/block_file_url';
    const BLOCK_V6_FILE = 'amgeoip/general/block_v6_file_url';
    const LOCATION_FILE = 'amgeoip/general/location_file_url';
    const BLOCK_HASH = 'amgeoip/general/block_hash_url';
    const BLOCK_V6_HASH = 'amgeoip/general/block_v6_hash_url';
    const LOCATION_HASH = 'amgeoip/general/location_hash_url';

    /**
     * @var array
     */
    public $_geoipCsvFiles = [
        'block' => 'GeoLite2-City-Blocks-IPv4.csv',
        'block_v6' => 'GeoLite2-City-Blocks-IPv6.csv',
        'location' => 'GeoLite2-City-Locations-en.csv'
    ];

    /**
     * @var array
     */
    public $_geoipIgnoredLines = [
        'block' => 1,
        'block_v6' => 1,
        'location' => 1
    ];

    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * Resource model of config data
     *
     * @var ConfigInterface
     */
    protected $_resource;

    /**
     * @var StateInterface $_state
     */
    protected $_state;

    /**
     * @var bool
     */
    protected $_cacheEnabled;

    /**
     * @var File
     */
    private $fileDriver;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param DirectoryList $directoryList
     * @param ConfigInterface $_resource
     * @param StateInterface $state
     * @param File $fileDriver
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        Context $context,
        DirectoryList $directoryList,
        ConfigInterface $_resource,
        StateInterface $state,
        File $fileDriver,
        ObjectManagerInterface $objectManager
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
    public function getUrlBlockV6File()
    {
        return $this->scopeConfig->getValue(self::BLOCK_V6_FILE);
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
    public function getHashUrlBlockV6()
    {
        return $this->scopeConfig->getValue(self::BLOCK_V6_HASH);
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

        return $this->scopeConfig->getValue('amgeoip/import/location')
            && $this->scopeConfig->getValue('amgeoip/import/block')
            && $this->scopeConfig->getValue('amgeoip/import/block_v6');
    }

    /**
     *
     */
    public function resetDone()
    {
        $this->_resource->saveConfig('amgeoip/import/block', 0, 'default', 0);
        $this->_resource->saveConfig('amgeoip/import/block_v6', 0, 'default', 0);
        $this->_resource->saveConfig('amgeoip/import/location', 0, 'default', 0);
    }

    /**
     * @return string
     * @throws FileSystemException
     */
    public function getDirPath()
    {
        $varDir = $this->directoryList->getPath('var');

        $dir = $varDir . DIRECTORY_SEPARATOR . 'amasty' . DIRECTORY_SEPARATOR . 'geoip';

        return $dir;
    }

    /**
     * @param $type
     * @return string
     * @throws FileSystemException
     */
    public function getCsvFilePath($type)
    {
        $dir = $this->getDirPath();
        $file = $dir . DIRECTORY_SEPARATOR . $this->_geoipCsvFiles[$type];

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
        } catch (FileSystemException $exception) {
            return false;
        }
    }

    /**
     *
     */
    public function flushConfigCache()
    {
        if (class_exists(System::class)) {
            $this->objectManager->get(System::class)->clean();
        } else {
            $this->objectManager->get(Config::class)
                ->clean(
                    \Zend_Cache::CLEANING_MODE_MATCHING_TAG,
                    ['config_scopes']
                );
        }
    }

    /**
     * @param $type
     * @return bool
     */
    public function isCacheEnabled($type)
    {
        if (!isset($this->_cacheEnabled)) {
            $this->_cacheEnabled = $this->_state->isEnabled($type);
        }

        return $this->_cacheEnabled;
    }

    /**
     * @param $ip
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getLongIpV6($ip)
    {
        $ipN = inet_pton($ip);
        $binary = '';
        for ($bit = strlen($ipN) - 1; $bit >= 0; $bit--) {
            $binary = sprintf('%08b', ord($ipN[$bit])) . $binary;
        }

        if (function_exists('gmp_init')) {
            return gmp_strval(gmp_init($binary, 2), 10);
        } elseif (function_exists('bcadd')) {
            $decimal = '0';
            $strLength = strlen($binary);
            for ($i = 0; $i < $strLength; $i++) {
                $decimal = bcmul($decimal, '2', 0);
                $decimal = bcadd($decimal, $binary[$i], 0);
            }

            return $decimal;
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(__('GMP or BCMATH extension not installed!'));
        }
    }
}
