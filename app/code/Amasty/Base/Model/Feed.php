<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model;

use Amasty\Base\Model\Source\NotificationType;
use Magento\AdminNotification\Model\Feed as MagentoFeed;
use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\Notification\MessageInterface;
use Magento\Store\Model\ScopeInterface;

class Feed
{
    const MIN_SEC_VALUE = 3600;

    const XML_LAST_UPDATE = 'amasty_base/system_value/last_update';

    const XML_FIRST_MODULE_RUN = 'amasty_base/system_value/first_module_run';

    const URL_NEWS = 'http://amasty.com/feed-news.xml';

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

    public function __construct(
        \Magento\Backend\App\ConfigInterface $config,
        \Magento\Framework\App\Config\ReinitableConfigInterface $reinitableConfig,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory,
        \Magento\AdminNotification\Model\InboxFactory $inboxFactory,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->config = $config;
        $this->reinitableConfig = $reinitableConfig;
        $this->configWriter = $configWriter;
        $this->curlFactory = $curlFactory;
        $this->productMetadata = $productMetadata;
        $this->inboxFactory = $inboxFactory;
        $this->scopeConfig = $scopeConfig;
    }

    public function checkUpdate()
    {
        if ($this->getFrequency() + $this->getLastUpdate() > time()) {
            return $this;
        }

        $allowedNotifications = $this->getModuleConfig('notifications/type');
        $allowedNotifications = str_replace('/', ',', $allowedNotifications);//one value has double type
        $allowedNotifications = explode(',', $allowedNotifications);

        if(empty($allowedNotifications) && in_array(NotificationType::UNSUBSCRIBE_ALL, $allowedNotifications)) {
            return $this;
        }

        $feedData = [];
        $feedXml = $this->getFeedData();

        $installDate = $this->getFirstModuleRun();

        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            foreach ($feedXml->channel->item as $item) {
                if (!in_array((string)$item->type, $allowedNotifications)) {
                    continue;
                }

                $date = strtotime((string)$item->pubDate);
                if ($installDate <= $date) {
                    $feedData[] = [
                        'severity' => MessageInterface::SEVERITY_NOTICE,
                        'date_added' => date('Y-m-d H:i:s', $date),
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
                $inbox->parse(array_reverse($feedData));
            }
        }
        $this->setLastUpdate();

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

        if ($result === false) {
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
        return $this->config->getValue(MagentoFeed::XML_FREQUENCY_PATH) * self::MIN_SEC_VALUE;
    }

    /**
     * @return string
     */
    private function getFeedUrl()
    {
        return self::URL_NEWS;
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
}
