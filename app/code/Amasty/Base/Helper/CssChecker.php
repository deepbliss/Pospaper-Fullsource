<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\View\Asset\Repository;

class CssChecker extends AbstractHelper
{
    const CSS_EXIST_PATH = 'css/styles-m.css';

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * @var Repository
     */
    private $asset;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    private $appEmulation;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var File
     */
    private $file;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Helper\Context $context,
        Repository $asset,
        \Magento\Store\Model\App\Emulation $appEmulation,
        \Magento\Framework\Filesystem\Io\File $file,
        \Magento\Framework\Filesystem $filesystem
    ) {
        parent::__construct($context);

        $this->filesystem = $filesystem;
        $this->asset = $asset;
        $this->appEmulation = $appEmulation;
        $this->storeManager = $storeManager;
        $this->file = $file;
    }

    /**
     * @return array
     */
    public function getCorruptedWebsites()
    {
        $pubStaticPath = $this->filesystem->getDirectoryRead(DirectoryList::STATIC_VIEW)->getAbsolutePath();
        $failWebsites = [];
        $websites = [];

        foreach ($this->storeManager->getStores() as $store) {
            $websiteId = $store->getWebsiteId();
            $websiteName = $this->storeManager->getWebsite()->getName();

            if (in_array($websiteId, $websites)) {
                continue;
            } else {
                $websites[] = $websiteId;
            }

            $storeId = $store->getStoreId();

            $this->appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
            $urlPath = $this->asset->getUrlWithParams(self::CSS_EXIST_PATH, []);
            $this->appEmulation->stopEnvironmentEmulation();

            $cssPath = $pubStaticPath . strstr($urlPath, 'frontend/');

            if (!$this->file->fileExists($cssPath)) {
                $failWebsites[$websiteId] = $websiteName;
            }
        }

        return $failWebsites;
    }
}
