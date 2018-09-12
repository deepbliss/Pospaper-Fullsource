<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Observer;

use Amasty\Base\Helper\Module;
use Magento\Framework\Event\ObserverInterface;

class GenerateInformationTab implements ObserverInterface
{
    const SEO_PARAMS = '?utm_source=extension&utm_medium=backend&utm_campaign=';
    const MAGENTO_VERSION = '_m2';

    /**
     * @var array
     */
    private $moduleData = null;

    private $block;

    /**
     * @var Module
     */
    private $moduleHelper;

    /**
     * @var string
     */
    private $moduleLink;

    /**
     * @var string
     */
    private $moduleCode;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    private $assetRepo;

    public function __construct(
        Module $moduleHelper,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Framework\View\Asset\Repository $assetRepo
    ) {
        $this->moduleHelper = $moduleHelper;
        $this->moduleManager = $moduleManager;
        $this->assetRepo = $assetRepo;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $block = $observer->getBlock();
        if ($block) {
            $this->setBlock($block);
            $html = $this->generateHtml();
            $block->setContent($html);
        }
    }

    /**
     * @return string
     */
    private function generateHtml()
    {
        $html = '<div class="amasty-info-block">'
            . $this->showVersionInfo()
            . $this->showUserGuideLink()
            . $this->additionalContent()
            . $this->showModuleExistingConflicts();
        $html .= '</div>';

        return $html;
    }

    /**
     * @return string
     */
    private function getLogoHtml()
    {
        $src = $this->assetRepo->getUrl("Amasty_Base::images/amasty_logo.svg");
        $href = 'https://amasty.com' . $this->getSeoparams() . 'amasty_logo_' . $this->getModuleCode();
        $html = '<a target="_blank" href="' . $href . '"><img class="amasty-logo" src="' . $src . '"/></a>';

        return $html;
    }

    /**
     * @return string
     */
    private function additionalContent()
    {
        $html = '';
        $content = $this->getBlock()->getAdditionalModuleContent();
        if ($content) {
            if (!is_array($content)) {
                $content = [
                    [
                        'type' => 'success',
                        'text' => $content
                    ]
                ];
            }

            foreach ($content as $message) {
                if (isset($message['type']) && isset($message['text'])) {
                    $html .= '<div class="amasty-additional-content"><span class="message ' . $message['type'] . '">'
                        . $message['text']
                        .'</span></div>';
                }
            }
        }

        return $html;
    }

    /**
     * @return string
     */
    private function showVersionInfo()
    {
        $html = '<div class="amasty-module-version">';

        $currentVer = $this->getCurrentVersion();
        if ($currentVer) {
            $isVersionLast = $this->isLastVersion($currentVer);
            $class = $isVersionLast ? 'last-version' : '';
            $html .= '<div><span class="version-title">'
                . $this->getModuleName() . ' '
                . '<span class="module-version ' . $class . '">' . $currentVer . '</span>'
                . __(' by ')
                . '</span>'
                . $this->getLogoHtml()
                . '</div>';

            if (!$isVersionLast) {
                $html .=
                    '<div><span class="upgrade-error message message-warning">'
                    . __(
                        'Update is available and recommended. See the '
                        . '<a target="_blank" href="%1">Change Log</a>',
                        $this->getChangeLogLink()
                    )
                    . '</span></div>';
            }
        }

        $html .= '</div>';

        return $html;
    }

    private function getCurrentVersion()
    {
        $data = $this->moduleHelper->getModuleInfo($this->getModuleCode());

        return isset($data['version']) ? $data['version'] : null;
    }

    private function getModuleCode()
    {
        if (!$this->moduleCode) {
            $this->moduleCode = '';
            $class = get_class($this->getBlock());
            if ($class) {
                $class = explode('\\', $class);
                if (isset($class[0]) && isset($class[1])) {
                    $this->moduleCode = $class[0] . '_' . $class[1];
                }
            }
        }

        return $this->moduleCode;
    }

    /**
     * @return string
     */
    private function getChangeLogLink()
    {
        return $this->getModuleLink()
            . $this->getSeoparams() . 'changelog_' . $this->getModuleCode() . '#changelog';
    }

    /**
     * @return string
     */
    private function showUserGuideLink()
    {
        $html = '<div class="amasty-user-guide"><span class="message success">'
            . __(
                'Need help with the settings?'
                . '  Please  consult the <a target="_blank" href="%1">user guide</a>'
                .' to configure the extension properly.',
                $this->getUserGuideLink()
            )
            . '</span></div>';

        return $html;
    }

    private function getUserGuideLink()
    {
        $link = $this->getBlock()->getUserGuide();
        if ($link) {
            $seoLink = $this->getSeoparams();
            if (strpos($link, '?') !== false) {
                $seoLink =str_replace('?', '&', $seoLink);
            }

            $link .= $seoLink . 'userguide_' . $this->getModuleCode();
        }

        return $link;
    }

    /**
     * @return string
     */
    private function getSeoparams()
    {
        return self::SEO_PARAMS;
    }

    /**
     * @param $currentVer
     * @return bool
     */
    private function isLastVersion($currentVer)
    {
        $result = true;

        $module = $this->getFeedModuleData();
        if ($module
            && isset($module['version'])
            && version_compare($module['version'], (string)$currentVer, '>')
        ) {
            $result = false;
        }

        return $result;
    }

    private function getModuleName()
    {
        $result = __('Extension');
        $module = $this->getFeedModuleData();
        if ($module && isset($module['name'])) {
            $result = $module['name'];
            $result = str_replace(' for Magento 2', '', $result);
        }

        return $result;
    }

    /**
     * @return array|null
     */
    private function getFeedModuleData()
    {
        if ($this->moduleData === null) {
            $allExtensions = $this->moduleHelper->getAllExtensions();
            if ($allExtensions && isset($allExtensions[$this->getModuleCode()])) {
                $module = $allExtensions[$this->getModuleCode()];
                if ($module && is_array($module)) {
                    $module = array_shift($module);
                }

                $this->moduleData = $module;
            }
        }

        return $this->moduleData;
    }

    /**
     * @param $currentVer
     * @return string
     */
    private function getModuleLink()
    {
        if (!$this->moduleLink) {
            $this->moduleLink = '';
            $module = $this->getFeedModuleData();
            if ($module && isset($module['url'])) {
                $this->moduleLink = $module['url'];
            }
        }

        return $this->moduleLink;
    }

    /**
     * @return array
     */
    private function getExistingConflicts()
    {
        $conflicts = [];
        $module = $this->getFeedModuleData();
        if ($module && isset($module['conflictExtensions'])) {
            $conflictsFromSite = $module['conflictExtensions'];
            $conflictsFromSite = str_replace(' ', '', $conflictsFromSite);
            $conflictsFromSite = explode(',', $conflictsFromSite);
            $conflicts = array_merge($conflicts, $conflictsFromSite);
            $conflicts = array_unique($conflicts);
        }

        return $conflicts;
    }

    /**
     * @return string
     */
    private function showModuleExistingConflicts()
    {
        $html = '';
        $messages = [];
        foreach ($this->getExistingConflicts() as $moduleName) {
            if ($this->moduleManager->isEnabled($moduleName)) {
                $messages[] = __(
                    'Incompatibility with the %1. '
                    . 'To avoid the conflicts we strongly recommend turning off the 3rd party mod via the following command: "%2"',
                    $moduleName,
                    'magento module:disable ' . $moduleName
                );
            }
        }

        if (count($messages)) {
            $html = '<div class="amasty-conflicts-title">'
                . __('Problems detected:')
                . '</div>';

            $html .= '<div class="amasty-disable-extensions">';
            foreach ($messages as $message) {
                $html .= '<p class="message message-error">' . $message . '</p>';
            }

            $html .= '</div>';
        }

        return $html;
    }

    /**
     * @return mixed
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param mixed $block
     */
    public function setBlock($block)
    {
        $this->block = $block;
    }
}
