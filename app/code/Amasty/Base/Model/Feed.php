<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model;

use Amasty\Base\Model\AdminNotification\Model\ResourceModel\Inbox\Collection\ExistsFactory;
use Amasty\Base\Model\Source\NotificationType;
use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\Notification\MessageInterface;
use Magento\Store\Model\ScopeInterface;
use Amasty\Base\Model\AdminNotification\Model\ResourceModel\Inbox\Collection\Expired;
use Amasty\Base\Model\AdminNotification\Model\ResourceModel\Inbox\Collection\ExpiredFactory;
use Amasty\Base\Helper\Module;

class Feed
{
    const HOUR_MIN_SEC_VALUE = 60 * 60 * 24;

    const REMOVE_EXPIRED_FREQUENCY = 60 * 60 * 6;//4 times per day

    const XML_LAST_UPDATE = 'amasty_base/system_value/last_update';

    const XML_FREQUENCY_PATH = 'amasty_base/notifications/frequency';

    const XML_FIRST_MODULE_RUN = 'amasty_base/system_value/first_module_run';

    const XML_LAST_REMOVMENT = 'amasty_base/system_value/remove_date';

    const URL_NEWS = 'amasty.com/feed-news-segments.xml';//do not use https:// or http

    /**
     * @var array
     */
    private $amastyModules = [];

    /**
     * @var \Magento\Backend\App\ConfigInterface
     */
    private $config;

    /**
     * @var \Magento\Framework\App\Config\ReinitableConfigInterface
     */
    private $reinitableConfig;

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    private $configWriter;

    /**
     * @var \Magento\Framework\HTTP\Adapter\CurlFactory
     */
    private $curlFactory;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var \Magento\AdminNotification\Model\InboxFactory
     */
    private $inboxFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ExpiredFactory
     */
    private $expiredFactory;

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    private $moduleList;

    /**
     * @var AdminNotification\Model\ResourceModel\Inbox\Collection\ExistsFactory
     */
    private $inboxExistsFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Module
     */
    private $moduleHelper;

    public function __construct(
        \Magento\Backend\App\ConfigInterface $config,
        \Magento\Framework\App\Config\ReinitableConfigInterface $reinitableConfig,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory,
        \Magento\AdminNotification\Model\InboxFactory $inboxFactory,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        ExpiredFactory $expiredFactory,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        ExistsFactory $inboxExistsFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Module $moduleHelper
    ) {
        $this->config = $config;
        $this->reinitableConfig = $reinitableConfig;
        $this->configWriter = $configWriter;
        $this->curlFactory = $curlFactory;
        $this->productMetadata = $productMetadata;
        $this->inboxFactory = $inboxFactory;
        $this->scopeConfig = $scopeConfig;
        $this->expiredFactory = $expiredFactory;
        $this->moduleList = $moduleList;
        $this->inboxExistsFactory = $inboxExistsFactory;
        $this->storeManager = $storeManager;
        $this->moduleHelper = $moduleHelper;
    }

    /**
     * @return $this
     */
    public function checkUpdate()
    {
        if ($this->getFrequency() + $this->getLastUpdate() > time()) {
            return $this;
        }

        $allowedNotifications = $this->getAllowedTypes();
        if (empty($allowedNotifications) || in_array(NotificationType::UNSUBSCRIBE_ALL, $allowedNotifications)) {
            return $this;
        }

        $feedData = null;
        $maxPriority = 0;

        $feedXml = $this->getFeedData();
        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            $installDate = $this->getFirstModuleRun();
            foreach ($feedXml->channel->item as $item) {
                if (!array_intersect($this->convertToArray($item->type), $allowedNotifications)
                    || (int)$item->version == 1 // for magento One
                    || ((string)$item->edition && (string)$item->edition != $this->getCurrentEdition())
                ) {
                    continue;
                }

                $priority =(int)$item->priority ?: 1;
                if ($priority <= $maxPriority) {
                    continue; //add only one with the highest priority
                }

                if (!$this->validateByExtension((string)$item->extension)) {
                    continue;
                }

                if (!$this->validateByAmastyCount($item->amasty_module_qty)) {
                    continue;
                }

                if (!$this->validateByNotInstalled((string)$item->amasty_module_not)) {
                    continue;
                }

                if (!$this->validateByExtension((string)$item->third_party_modules, true)) {
                    continue;
                }

                if (!$this->validateByDomainZone((string)$item->domain_zone)) {
                    continue;
                }

                if ($this->isItemExists($item)) {
                    continue;
                }

                $date = strtotime((string)$item->pubDate);
                $expired =(string)$item->expirationDate ? strtotime((string)$item->expirationDate) : null;
                if ($installDate <= $date
                    && (!$expired || $expired > gmdate('U'))
                ) {
                    $maxPriority = $priority;
                    $expired = $expired ? date('Y-m-d H:i:s', $expired) : null;

                    $feedData = [
                        'severity' => MessageInterface::SEVERITY_NOTICE,
                        'date_added' => date('Y-m-d H:i:s', $date),
                        'expiration_date' => $expired,
                        'title' => $this->convertString($item->title),
                        'description' => $this->convertString($item->description),
                        'url' => $this->convertString($item->link),
                        'is_amasty' => 1
                    ];
                }
            }

            if ($feedData) {
                /** @var \Magento\AdminNotification\Model\Inbox $inbox */
                $inbox = $this->inboxFactory->create();
                $inbox->parse([$feedData]);
            }
        }
        $this->setLastUpdate();

        return $this;
    }

    /**
     * @param $value
     *
     * @return array
     */
    private function convertToArray($value)
    {
        return explode(',', (string)$value);
    }

    /**
     * @param \SimpleXMLElement $item
     * @return bool
     */
    private function isItemExists(\SimpleXMLElement $item)
    {
        return $this->inboxExistsFactory->create()->execute($item);
    }

    /**
     * @return string
     */
    protected function getCurrentEdition()
    {
        return $this->productMetadata->getEdition() == 'Community' ? 'ce' : 'ee';
    }

    /**
     * @return $this
     */
    public function removeExpiredItems()
    {
        if ($this->getLastRemovement() + self::REMOVE_EXPIRED_FREQUENCY > time()) {
            return $this;
        }

        /** @var Expired $collection */
        $collection = $this->expiredFactory->create();
        foreach ($collection as $model) {
            $model->setIsRemove(1)->save();
        }

        $this->setLastRemovement();

        return $this;
    }

    /**
     * @return \SimpleXMLElement|false
     */
    public function getFeedData()
    {
        /** @var Curl $curlObject */
        $curlObject = $this->curlFactory->create();
        $curlObject->setConfig(
            [
                'timeout'   => 2,
                'useragent' => $this->productMetadata->getName()
                    . '/' . $this->productMetadata->getVersion()
                    . ' (' . $this->productMetadata->getEdition() . ')'
            ]
        );
        $curlObject->write(\Zend_Http_Client::GET, $this->getFeedUrl(), '1.0');
        $result = $curlObject->read();

        if ($result === false || $result === '') {
            return false;
        }

        $result = preg_split('/^\r?$/m', $result, 2);
        $result = trim($result[1]);

        $curlObject->close();

        try {
            $xml = new \SimpleXMLElement($result);
        } catch (\Exception $e) {
            return false;
        }

        return $xml;
    }

    /**
     * @return array
     */
    private function getAllowedTypes()
    {
        $allowedNotifications = $this->getModuleConfig('notifications/type');
        $allowedNotifications = explode(',', $allowedNotifications);

        return $allowedNotifications;
    }

    /**
     * @param \SimpleXMLElement $data
     * @return string
     */
    private function convertString(\SimpleXMLElement $data)
    {
        $data = htmlspecialchars((string)$data);
        return $data;
    }

    /**
     * @return int
     */
    private function getFrequency()
    {
        return $this->config->getValue(self::XML_FREQUENCY_PATH) * self::HOUR_MIN_SEC_VALUE;
    }

    /**
     * @return string
     */
    private function getFeedUrl()
    {
        $scheme = $this->getCurrentScheme();
        $url = $scheme ?: 'http://';

        return $url . self::URL_NEWS;
    }

    /**
     * @return int
     */
    private function getLastUpdate()
    {
        return $this->config->getValue(self::XML_LAST_UPDATE);
    }

    /**
     * @return $this
     */
    private function setLastUpdate()
    {
        $this->configWriter->save(self::XML_LAST_UPDATE, time());
        $this->reinitableConfig->reinit();

        return $this;
    }

    /**
     * @return int|mixed
     */
    private function getFirstModuleRun()
    {
        $result = $this->config->getValue(self::XML_FIRST_MODULE_RUN);
        if (!$result) {
            $result = time();
            $this->configWriter->save(self::XML_FIRST_MODULE_RUN, $result);
            $this->reinitableConfig->reinit();
        }

        return $result;
    }

    /**
     * @param $path
     * @param int $storeId
     * @return mixed
     */
    private function getModuleConfig($path, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            'amasty_base/' . $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @return int
     */
    private function getLastRemovement()
    {
        return $this->config->getValue(self::XML_LAST_REMOVMENT);
    }

    /**
     * @return $this
     */
    private function setLastRemovement()
    {
        $this->configWriter->save(self::XML_LAST_REMOVMENT, time());
        $this->reinitableConfig->reinit();

        return $this;
    }

    /**
     * @return array|string[]
     */
    private function getInstalledAmastyExtensions()
    {
        if (!$this->amastyModules) {
            $modules = $this->moduleList->getNames();

            $dispatchResult = new \Magento\Framework\DataObject($modules);
            $modules = $dispatchResult->toArray();

            $modules = array_filter(
                $modules,
                function ($item) {
                    return strpos($item, 'Amasty_') !== false;
                }
            );
            $this->amastyModules = $modules;
        }

        return $this->amastyModules;
    }

    /**
     * @return array|string[]
     */
    private function getAllExtensions()
    {
        $modules = $this->moduleList->getNames();

        $dispatchResult = new \Magento\Framework\DataObject($modules);
        $modules = $dispatchResult->toArray();

        return $modules;
    }

    /**
     * @param string $extensions
     * @return bool
     */
    private function validateByExtension($extensions, $allModules = false)
    {
        if ($extensions) {
            $result = false;
            $extensions = $this->validateExtensionValue($extensions);

            if ($extensions) {
                $installedModules = $allModules ? $this->getAllExtensions() : $this->getInstalledAmastyExtensions();
                $intersect = array_intersect($extensions, $installedModules);
                if ($intersect) {
                    $result = true;
                }
            }
        } else {
            $result = true;
        }

        return $result;
    }

    /**
     * @param string $extensions
     * @return bool
     */
    private function validateByNotInstalled($extensions)
    {
        if ($extensions) {
            $result = false;
            $extensions = $this->validateExtensionValue($extensions);

            if ($extensions) {
                $installedModules = $this->getInstalledAmastyExtensions();
                $diff = array_diff($extensions, $installedModules);
                if ($diff) {
                    $result = true;
                }
            }
        } else {
            $result = true;
        }

        return $result;
    }

    /**
     * @param string $extensions
     *
     * @return array
     */
    private function validateExtensionValue($extensions)
    {
        $extensions = explode(',', $extensions);
        $extensions = array_filter($extensions, function ($item) {
            return strpos($item, '_1') === false;
        });

        $extensions = array_map(function ($item) {
            return str_replace('_2', '', $item);
        }, $extensions);

        return $extensions;
    }

    /**
     * @param $counts
     * @return bool
     */
    private function validateByAmastyCount($counts)
    {
        $result = true;

        $countString = (string)$counts;
        if ($countString) {
            $moreThan = null;
            $result = false;

            $position = strpos($countString, '>');
            if ($position !== false) {
                $moreThan = substr($countString, $position + 1);
                $moreThan = explode(',', $moreThan);
                $moreThan = array_shift($moreThan);
            }

            $counts = $this->convertToArray($counts);
            $amastyModules = $this->getInstalledAmastyExtensions();
            $dependModules = $this->getDependModules($amastyModules);
            $amastyModules = array_diff($amastyModules, $dependModules);

            $amastyCount = count($amastyModules);

            if ($amastyCount
                && (in_array($amastyCount, $counts)
                    || ($moreThan && $amastyCount >= $moreThan)
                )
            ) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * @param $zones
     *
     * @return bool
     */
    private function validateByDomainZone($zones)
    {
        $result = true;
        if ($zones) {
            $zones = $this->convertToArray($zones);
            $currentZone = $this->getDomainZone();

            if (!in_array($currentZone, $zones)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    private function getDomainZone()
    {
        $domain = '';
        $url = $this->storeManager->getStore()->getBaseUrl();
        $components = parse_url($url);
        if (isset($components['host'])) {
            $host = explode('.', $components['host']);
            $domain = end($host);
        }

        return $domain;
    }

    /**
     * @return string
     */
    private function getCurrentScheme()
    {
        $scheme = '';
        $url = $this->storeManager->getStore()->getBaseUrl();
        $components = parse_url($url);
        if (isset($components['scheme'])) {
            $scheme = $components['scheme'] . '://';
        }

        return $scheme;
    }

    /**
     * @param $amastyModules
     *
     * @return array
     */
    private function getDependModules($amastyModules)
    {
        $depend = [];
        $result = [];
        $dataName = [];
        foreach ($amastyModules as $module) {
            $data = $this->moduleHelper->getModuleInfo($module);
            if (isset($data['name'])) {
                $dataName[$data['name']] = $module;
            }

            if (isset($data['require']) and is_array($data['require'])) {
                foreach ($data['require'] as $requireItem => $version) {
                    if (strpos($requireItem, 'amasty') !== false) {
                        $depend[] = $requireItem;
                    }
                }
            }
        }

        $depend = array_unique($depend);
        foreach ($depend as $item) {
            if (isset($dataName[$item])) {
                $result[] = $dataName[$item];
            }
        }

        return $result;
    }
}
