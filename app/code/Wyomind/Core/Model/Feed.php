<?php

/**
 * Copyright © 2017 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\Core\Model;

/**
 * Notifications in the backend management
 */
class Feed extends \Magento\AdminNotification\Model\Feed
{

    /**
     * Url for the feed
     */
    const FEED_URL = 'rss.wyomind.com';

    /**
     * Fetching frequency
     */
    const FREQUENCY = 1; // day(s)

    /**
     * @var \Wyomind\Core\Helper\Data
     */

    public $coreHelper = null;

    /**
     * @var \Magento\Framework\Module\ModuleList
     */
    public $moduleList = null;

    /**
     * Class constructor
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\App\ConfigInterface $backendConfig
     * @param \Magento\AdminNotification\Model\InboxFactory $inboxFactory
     * @param \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory
     * @param \Magento\Framework\App\DeploymentConfig $deploymentConfig
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Wyomind\Core\Helper\Data $coreHelper
     * @param \Magento\Framework\Module\ModuleList $moduleList
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
    \Magento\Framework\Model\Context $context,
            \Magento\Framework\Registry $registry,
            \Magento\Backend\App\ConfigInterface $backendConfig,
            \Magento\AdminNotification\Model\InboxFactory $inboxFactory,
            \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory,
            \Magento\Framework\App\DeploymentConfig $deploymentConfig,
            \Magento\Framework\App\ProductMetadataInterface $productMetadata,
            \Magento\Framework\UrlInterface $urlBuilder,
            \Wyomind\Core\Helper\Data $coreHelper,
            \Magento\Framework\Module\ModuleList $moduleList,
            \Magento\Framework\Stdlib\DateTime\DateTime $coreDate,
            \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
            \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
            array $data = []
    )
    {
        parent::__construct($context, $registry, $backendConfig, $inboxFactory, $curlFactory, $deploymentConfig, $productMetadata, $urlBuilder, $resource, $resourceCollection, $data);
        $this->coreHelper = $coreHelper;
        $this->moduleList = $moduleList;
        $this->coreDate = $coreDate;
    }

    /**
     * Get the notifications feed url
     * @return string
     */
    public function getFeedUrl()
    {
        $httpPath = 'http://';
        if ($this->_feedUrl === null) {
            $this->_feedUrl = $httpPath . self::FEED_URL;
        }

        $url = $this->coreHelper->getDefaultConfig("web/secure/base_url");
        $version = $this->moduleList->getOne("Wyomind_Core")['setup_version'];
        $lastcheck = $this->getLastUpdate();
        
        return $this->_feedUrl . "/?domain=$url&version=$version&lastcheck=$lastcheck&now=" . $this->coreDate->date('U');
    }

    /**
     * Get the fetch frequency in seconds
     * @return integer
     */
    public function getFrequency()
    {
        return self::FREQUENCY * 3600 * 24; // 24h
    }

    /**
     * Get the last update date
     * @return string | integer
     */
    public function getLastUpdate()
    {
        return $this->_cacheManager->load('wyomind_notifications_lastcheck');
    }

    /**
     * Set last update time (now)
     * @return \Wyomind\Core\Model\Feed
     */
    public function setLastUpdate()
    {
        $this->_cacheManager->save($this->coreDate->date('U'), 'wyomind_notifications_lastcheck');
        return $this;
    }

    /**
     * Check if the feed must be fetch and update the notifications
     * @return \Wyomind\Core\Model\Feed
     */
    public function checkUpdate()
    {
        if ($this->coreHelper->getDefaultConfig("core/settings/notification") === "0") {
            return $this;
        }
        
        if ($this->getFrequency() + $this->getLastUpdate() > $this->coreDate->date('U')) {
            return $this;
        }

        $feedData = [];

        $feedXml = $this->getFeedData();

        $installDate = strtotime($this->_deploymentConfig->get(\Magento\Framework\Config\ConfigOptionsListConstants::CONFIG_PATH_INSTALL_DATE));

        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            foreach ($feedXml->channel->item as $item) {
                $itemPublicationDate = (string) $item->pubDate;
                if ($installDate <= $itemPublicationDate) {
                    $feedData[] = [
                        'severity' => (int) $item->severity,
                        'date_added' => $this->coreDate->date('Y-m-d H:i:s', $itemPublicationDate),
                        'title' => (string) $item->title,
                        'description' => (string) $item->description,
                        'url' => (string) $item->link,
                    ];
                }
            }

            if ($feedData) {
                $this->_inboxFactory->create()->parse(array_reverse($feedData));
            }
        }
        $this->setLastUpdate();
        return $this;
    }

}
