<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Plugin\Frontend;

use Amasty\Base\Model\LessToCss\Config\Converter;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Asset\File;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Config\Renderer;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Framework\App\ObjectManager;

/**
 * Class AddAssets
 * @package Amasty\Base\Plugin\Frontend
 */
class AddAssets
{
    const CACHE_KEY = 'amasty_should_load_css_files';

    protected $filesToCheck = ['css/styles-l.css', 'css/styles-m.css'];

    /**
     * @var Config
     */
    private $config;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var \Amasty\Base\Model\LessToCss\Config
     */
    private $lessConfig;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    private $layout;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\View\Design\FileResolution\Fallback\StaticFile
     */
    private $fallback;

    /**
     * @var ThemeProviderInterface
     */
    private $themeProvider;

    /**
     * @var \Magento\Framework\View\DesignInterface
     */
    private $design;

    public function __construct(
        Config $config,
        CacheInterface $cache,
        \Amasty\Base\Model\LessToCss\Config $lessConfig,
        \Magento\Framework\View\LayoutInterface $layout,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Design\FileResolution\Fallback\StaticFile $fallback,
        \Magento\Framework\View\DesignInterface $design
    ) {
        $this->config = $config;
        $this->cache = $cache;
        $this->lessConfig = $lessConfig;
        $this->layout = $layout;
        $this->scopeConfig = $scopeConfig;
        $this->fallback = $fallback;
        $this->design = $design;
    }

    /**
     * Add our css files if less functionality for theme is missing
     *
     * @param Renderer $subject
     * @param array $resultGroups
     *
     * @return array
     */
    public function beforeRenderAssets(Renderer $subject, $resultGroups = [])
    {
        $theme = $this->design->getDesignTheme();
        $cacheKey = self::CACHE_KEY . $theme->getCode();
        /** @var bool|int $shouldLoad */
        $shouldLoad = $this->cache->load($cacheKey);
        if ($shouldLoad === false) {
            $shouldLoad = $this->isShouldLoadCss();
            $this->cache->save($shouldLoad, $cacheKey);
        }

        if ($shouldLoad) {
            $modulesConfig = $this->lessConfig->get();
            $currentHandles = $this->layout->getUpdate()->getHandles();
            foreach ($modulesConfig as $moduleName => $moduleConfig) {
                foreach ($moduleConfig[Converter::IFCONFIG] as $configPath) {
                    if (!$this->scopeConfig->isSetFlag($configPath, ScopeInterface::SCOPE_STORE)) {
                        continue 2;
                    }
                }
                foreach ($moduleConfig[Converter::HANDLES] as $handle) {
                    if (in_array($handle, $currentHandles)) {
                        $this->addCss($moduleName, $moduleConfig[Converter::CSS_OPTIONS]);
                        continue 2;
                    }
                }
            }
        }

        return [$resultGroups];
    }

    /**
     * @param string $moduleName
     * @param array $moduleConfig
     */
    private function addCss($moduleName, $moduleConfig)
    {
        // i.e. 'Amasty_Checkout::css/styles.css'
        $cssPath = $moduleName . '::' . $moduleConfig[Converter::CSS_OPTION_PATH]
            . '/' . $moduleConfig[Converter::CSS_OPTION_FILENAME] . '.css';

        $this->config->addPageAsset($cssPath);
    }

    /**
     * @return int
     */
    private function isShouldLoadCss()
    {
        /** @var \Magento\Framework\View\Asset\GroupedCollection $collection */
        $collection = $this->config->getAssetCollection();
        $found = 0;
        $shouldFind = count($this->filesToCheck);
        /** @var File $item */
        foreach ($collection->getAll() as $item) {
            if ($item instanceof File
                && in_array($item->getFilePath(), $this->filesToCheck)
            ) {
                $found++;
                if ($found === $shouldFind && $this->findLess($item) === false) {
                    //styles with usual name founded, but this styles dont have less
                    return 1;
                }
            }
        }

        return (int)($found < $shouldFind);
    }

    /**
     * @param File $asset
     *
     * @return bool|string
     */
    private function findLess(File $asset)
    {
        try {
            /** @var \Magento\Framework\View\Asset\File\FallbackContext $context */
            $context = $asset->getContext();

            $themeModel = $this->getThemeProvider()->getThemeByFullPath(
                $context->getAreaCode() . '/' . $context->getThemePath()
            );
            $path = preg_replace('#\.css$#', '.less', $asset->getFilePath());

            $sourceFile = $this->fallback->getFile(
                $context->getAreaCode(),
                $themeModel,
                $context->getLocale(),
                $path,
                $asset->getModule()
            );
        } catch (\Exception $e) {
            return false;
        }

        return $sourceFile;
    }

    /**
     * @return ThemeProviderInterface
     */
    private function getThemeProvider()
    {
        if (null === $this->themeProvider) {
            $this->themeProvider = ObjectManager::getInstance()->get(ThemeProviderInterface::class);
        }

        return $this->themeProvider;
    }
}
