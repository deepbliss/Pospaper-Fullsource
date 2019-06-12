<?php

namespace Adroll\Pixel\Helper;

use \Magento\Framework\App\Cache\Manager as CacheManager;
use \Magento\Framework\App\Config\Storage\WriterInterface;
use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Helper\Context;
use \Magento\Store\Model\StoreManagerInterface;

class Config extends AbstractHelper {
    const ADROLL_BASE_URL = 'https://app.adroll.com';
    const ADVERTISABLE_NAME_CONFIG_TEMPLATE = 'websites/store_groups/id_%s/adroll_advertisable_name';
    const ADVERTISABLE_CONFIG_TEMPLATE = 'websites/store_groups/id_%s/adroll_advertisable';
    const PIXEL_CONFIG_TEMPLATE = 'websites/store_groups/id_%s/adroll_pixel';

    public function __construct(
        Context $context,
        WriterInterface $configWriter,
        StoreManagerInterface $storeManager,
        CacheManager $cacheManager)
    {
        parent::__construct($context);
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_configWriter = $configWriter;
        $this->_storeManager = $storeManager;
        $this->_cacheManager = $cacheManager;
    }

    private function getConfigValue($path, $default = null)
    {
        $data = $this->_scopeConfig->getValue($path, 'default');
        if (!$data) {
            return $default;
        }
        return (string)$data;
    }

    private function flushCache()
    {
        $this->_cacheManager->flush(array('config', 'layout', 'block_html', 'full_page'));
    }

    private function setConfigValue($path, $value)
    {
        $this->_configWriter->save($path, $value, 'default');
        $this->flushCache();
    }

    private function deleteConfigValue($path)
    {
        $this->_configWriter->delete($path, 'default');
        $this->flushCache();
    }

    public function getGroupForAdvertisableEid($advertisableEid)
    {
        foreach ($this->_storeManager->getWebsites() as $website) {
            foreach ($website->getGroups() as $group) {
                if ($this->getAdvertisableEid($group->getId()) === $advertisableEid) {
                    return $group;
                }
            }
        }

        return null;
    }

    public function getAdvertisableName($groupId)
    {
        return self::getConfigValue(sprintf(self::ADVERTISABLE_NAME_CONFIG_TEMPLATE, $groupId));
    }

    public function setAdvertisableName($groupId, $advertisableName)
    {
        self::setConfigValue(sprintf(self::ADVERTISABLE_NAME_CONFIG_TEMPLATE, $groupId), $advertisableName);
    }

    public function getAdvertisableEid($groupId)
    {
        return self::getConfigValue(sprintf(self::ADVERTISABLE_CONFIG_TEMPLATE, $groupId));
    }

    public function setAdvertisableEid($groupId, $advertisableEid)
    {
        self::setConfigValue(sprintf(self::ADVERTISABLE_CONFIG_TEMPLATE, $groupId), $advertisableEid);
    }

    public function getPixelEid($groupId)
    {
        return self::getConfigValue(sprintf(self::PIXEL_CONFIG_TEMPLATE, $groupId));
    }

    public function setPixelEid($groupId, $pixelEid)
    {
        self::setConfigValue(sprintf(self::PIXEL_CONFIG_TEMPLATE, $groupId), $pixelEid);
    }

    public function uninstallPixel($groupId)
    {
        $oldAdvertisableEid = $this->getAdvertisableEid($groupId);
        $this->deleteConfigValue(sprintf(self::ADVERTISABLE_NAME_CONFIG_TEMPLATE, $groupId));
        $this->deleteConfigValue(sprintf(self::ADVERTISABLE_CONFIG_TEMPLATE, $groupId));
        $this->deleteConfigValue(sprintf(self::PIXEL_CONFIG_TEMPLATE, $groupId));
        $this->notifyShopifypyConfigChange($groupId, $oldAdvertisableEid);
    }

    public function notifyShopifypyConfigChange($groupId, $advertisableEid = null)
    {
        if ($advertisableEid === null) {
            $advertisableEid = $this->getAdvertisableEid($groupId);
        }

        if ($advertisableEid) {
            $url = self::ADROLL_BASE_URL . '/ecommerce/magento2/api/v1/notify_config_change?advertisable=' . $advertisableEid;
            try {
                @file_get_contents($url);
            } catch (\Exception $e) {
                $this->warning('Adroll_Pixel: Store config change notification failed');
            }
        }
    }
}
