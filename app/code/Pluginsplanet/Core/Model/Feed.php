<?php
/**
 * Pluginsplanet
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the pluginsplanet.com license that is
 * available through the world-wide-web at this URL:
 * https://www.pluginsplanet.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Pluginsplanet
 * @package     Pluginsplanet_Core
 * @copyright   Copyright (c) 2016 Pluginsplanet (http://www.pluginsplanet.com/)
 * @license     https://www.pluginsplanet.com/LICENSE.txt
 */
namespace Pluginsplanet\Core\Model;

/**
 * Class Feed
 * @package Pluginsplanet\Core\Model
 */
class Feed extends \Magento\AdminNotification\Model\Feed
{
    /**
     * @inheritdoc
     */
    const Pluginsplanet_FEED_URL = 'www.pluginsplanet.com/notifications.xml';

    /**
     * @inheritdoc
     */
    public function getFeedUrl()
    {
        $httpPath = $this->_backendConfig->isSetFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://';
        if ($this->_feedUrl === null) {
            $this->_feedUrl = $httpPath . self::Pluginsplanet_FEED_URL;
        }
        return $this->_feedUrl;
    }

    /**
     * @inheritdoc
     */
    public function getLastUpdate()
    {
        return $this->_cacheManager->load('Pluginsplanet_notifications_lastcheck');
    }

    /**
     * @inheritdoc
     */
    public function setLastUpdate()
    {
        $this->_cacheManager->save(time(), 'Pluginsplanet_notifications_lastcheck');
        return $this;
    }

}
