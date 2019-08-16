<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Plugin\Backend\Model\Menu;

use Magento\Backend\Model\Menu;
use Magento\Framework\DataObjectFactory as ObjectFactory;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Builder
 * @package Amasty\Base\Plugin\Backend\Model\Menu
 */
class Builder
{
    const BASE_MENU = 'MenuAmasty_Base::menu';

    const SEO_PARAMS = '?utm_source=extension&utm_medium=backend&utm_campaign=common_menu_to_guide';

    const SETTING_ENABLE = 'amasty_base/menu/enable';

    /**
     * @var Menu\Config
     */
    private $menuConfig;

    /**
     * @var array|null
     */
    private $amastyItems = null;

    /**
     * @var Menu\Filter\IteratorFactory
     */
    private $iteratorFactory;

    /**
     * @var \Magento\Backend\Model\Menu\ItemFactory
     */
    private $itemFactory;

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    private $moduleList;

    /**
     * @var \Amasty\Base\Helper\Module
     */
    private $moduleHelper;

    /**
     * @var \Magento\Config\Model\Config\Structure
     */
    private $configStructure;

    /**
     * @var ObjectFactory
     */
    private $objectFactory;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $metadata;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Magento\Backend\Model\Menu\Config $menuConfig,
        \Magento\Backend\Model\Menu\Filter\IteratorFactory $iteratorFactory,
        \Magento\Backend\Model\Menu\ItemFactory $itemFactory,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Amasty\Base\Helper\Module $moduleHelper,
        \Magento\Config\Model\Config\Structure $configStructure,
        \Magento\Framework\App\ProductMetadataInterface $metadata,
        ObjectFactory $objectFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->menuConfig = $menuConfig;
        $this->iteratorFactory = $iteratorFactory;
        $this->itemFactory = $itemFactory;
        $this->moduleList = $moduleList;
        $this->moduleHelper = $moduleHelper;
        $this->configStructure = $configStructure;
        $this->objectFactory = $objectFactory;
        $this->metadata = $metadata;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param \Magento\Backend\Model\Menu\Builder $subject
     * @param Menu $menu
     *
     * @return Menu
     */
    public function afterGetResult($subject, Menu $menu)
    {
        try {
            $menu = $this->observeMenu($menu);
            //phpcs:ignore Magento2.CodeAnalysis.EmptyBlock.DetectedCatch
        } catch (\Exception $ex) {
            //do nothing - do not show our menu
        }

        return $menu;
    }

    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    private function observeMenu(Menu $menu)
    {
        $item = $menu->get(self::BASE_MENU);
        if (!$item) {
            return $menu;
        }

        if (version_compare($this->metadata->getVersion(), '2.2.0', '<')
            || !$this->scopeConfig->isSetFlag(self::SETTING_ENABLE, ScopeInterface::SCOPE_STORE)

        ) {
            $menu->remove(self::BASE_MENU);
            return $menu;
        }

        $origMenu = $this->menuConfig->getMenu();
        $menuItems = $this->getMenuItems($origMenu);
        $configItems = $this->getConfigItems();

        foreach ($this->getInstalledModules($configItems) as $title => $installedModule) {
            $itemsToAdd = [];
            $moduleInfo = $this->moduleHelper->getFeedModuleData($installedModule);

            if (isset($menuItems[$installedModule])) {
                $this->cloneMenuItems($menuItems[$installedModule], $itemsToAdd, $menu);
            }

            if (isset($configItems[$installedModule]['id'])) {
                $amastyItem = $this->generateMenuItem(
                    $installedModule . '::menuconfig',
                    $installedModule,
                    self::BASE_MENU,
                    'adminhtml/system_config/edit/section/' . $configItems[$installedModule]['id'],
                    __('Configuration')->render()
                );
                $itemsToAdd[] = $amastyItem;
            }

            if (isset($moduleInfo['guide']) && $moduleInfo['guide']) {
                $amastyItem = $this->generateMenuItem(
                    $installedModule . '::menuguide',
                    $installedModule,
                    self::BASE_MENU,
                    'adminhtml/system_config/edit/section/ambase',
                    __('User Guide')->render()
                );
                $itemsToAdd[] = $amastyItem;
            }

            if ($itemsToAdd) {
                $itemId = $installedModule . '::container';
                /** @var \Magento\Backend\Model\Menu\Item $module */
                $module = $this->itemFactory->create(
                    [
                        'data' => [
                            'id'       => $itemId,
                            'title'    => $title,
                            'module'   => $installedModule,
                            'resource' => self::BASE_MENU
                        ]
                    ]
                );
                $menu->add($module, self::BASE_MENU, 1);
                foreach ($itemsToAdd as $copy) {
                    if ($copy) {
                        $menu->add($copy, $itemId, null);
                    }
                }
            }
        }

        return $menu;
    }

    /**
     * @param array $menuItems
     * @param array $itemsToAdd
     * @param Menu $menu
     */
    private function cloneMenuItems($menuItems, &$itemsToAdd, Menu $menu)
    {
        foreach ($menuItems as $link) {
            $amastyItem = $menu->get($link);
            if ($amastyItem) {
                $itemData = $amastyItem->toArray();
                if (isset($itemData['id'])
                    && isset($itemData['resource'])
                    && isset($itemData['action'])
                    && isset($itemData['title'])
                ) {
                    if (isset($itemData['module'])) {
                        $module = $itemData['module'];
                    } else {
                        $module = current(explode('::', $itemData['resource']));
                    }
                    $itemsToAdd[] = $this->generateMenuItem(
                        $itemData['id'] . 'menu',
                        $module,
                        $itemData['resource'],
                        $itemData['action'],
                        $itemData['title']
                    );
                }
            }
        }
    }

    /**
     * @param $id
     * @param $installedModule
     * @param $resource
     * @param $url
     * @param $title
     *
     * @return bool|Menu\Item
     */
    private function generateMenuItem($id, $installedModule, $resource, $url, $title)
    {
        try {
            $item = $this->itemFactory->create(
                [
                    'data' => [
                        'id'           => $id,
                        'title'        => $title,
                        'module'       => $installedModule,
                        'action'       => $url,
                        'resource'     => $resource
                    ]
                ]
            );
        } catch (\Exception $ex) {
            $item = false;
        }

        return $item;
    }

    /**
     * @param $configItems
     *
     * @return array
     */
    private function getInstalledModules($configItems)
    {
        $installed = [];
        $modules = $this->moduleList->getNames();
        $dispatchResult = $this->objectFactory->create(['data' => $modules]);
        $modules = $dispatchResult->toArray();

        foreach ($modules as $moduleName) {
            if (strstr($moduleName, 'Amasty_') === false
                || $moduleName === 'Amasty_Base'
                || in_array($moduleName, $this->moduleHelper->getRestrictedModules())
            ) {
                continue;
            }

            $title = (isset($configItems[$moduleName]['label']) && $configItems[$moduleName]['label'])
                ? $configItems[$moduleName]['label']
                : $this->getModuleTitle($moduleName);

            $installed[$title] = $moduleName;
        }
        ksort($installed);

        return $installed;
    }

    /**
     * @param Menu $menu
     *
     * @return array|null
     */
    private function getMenuItems(Menu $menu)
    {
        if ($this->amastyItems === null) {
            $all = $this->generateAmastyItems($menu);
            $this->amastyItems = [];
            foreach ($all as $item) {
                $name = explode('::', $item);
                $name = $name[0];
                if (!isset($this->amastyItems[$name])) {
                    $this->amastyItems[$name] = [];
                }
                $this->amastyItems[$name][] = $item;
            }
        }

        return $this->amastyItems;
    }

    /**
     * @return array
     */
    private function getConfigItems()
    {
        $configItems = [];
        $config = $this->generateConfigItems();
        foreach ($config as $item => $section) {
            $name = explode('::', $item);
            $name = $name[0];
            if (!isset($configItems[$name])) {
                $configItems[$name] = [];
            }
            $configItems[$name] = $section;
        }

        return $configItems;
    }

    /**
     * @return array
     */
    private function generateAmastyItems($menu)
    {
        $amasty = [];
        foreach ($this->getMenuIterator($menu) as $menuItem) {
            $menuId = $menuItem->getId();
            if (strpos($menuId, 'Amasty') !== false
                && strpos($menuId, 'Amasty_Base') === false
                && ($menuItem->getAction() && strpos($menuItem->getAction(), 'system_config') === false)
            ) {
                $amasty[] = $menuId;
            }
            if ($menuItem->hasChildren()) {
                foreach ($this->generateAmastyItems($menuItem->getChildren()) as $menuChild) {
                    $amasty[] = $menuChild;
                }
            }
        }

        return $amasty;
    }

    /**
     * Get menu filter iterator
     *
     * @param \Magento\Backend\Model\Menu $menu
     *
     * @return \Magento\Backend\Model\Menu\Filter\Iterator
     */
    private function getMenuIterator($menu)
    {
        return $this->iteratorFactory->create(['iterator' => $menu->getIterator()]);
    }

    /**
     * @param $name
     *
     * @return string
     */
    private function getModuleTitle($name)
    {
        $result = $name;
        $module = $this->moduleHelper->getFeedModuleData($name);
        if ($module && isset($module['name'])) {
            $result = $module['name'];
            $result = str_replace(' for Magento 2', '', $result);
        } else {
            $result = str_replace('Amasty_', '', $result);
            preg_match_all('/((?:^|[A-Z])[a-z]+)/', $result, $matches);
            if (isset($matches[1]) && $matches[1]) {
                $result = implode(' ', $matches[1]);
            }
        }

        return $result;
    }

    private function generateConfigItems()
    {
        $result = [];
        $configTabs = $this->configStructure->getTabs();
        $config = $this->findResourceChildren($configTabs, 'amasty');

        if ($config) {
            foreach ($config as $item) {
                $data = $item->getData('resource');
                if (isset($data['resource'])
                    && isset($data['id'])
                    && $data['id']
                ) {
                    $result[$data['resource']] = $data;
                }
            }
        }

        return $result;
    }

    /**
     * @param $config
     * @param $name
     *
     * @return array
     */
    private function findResourceChildren($config, $name)
    {
        $result = [];
        $currentNode = null;
        foreach ($config as $node) {
            if ($node->getId() == $name) {
                $currentNode = $node;
                break;
            }
        }

        if ($currentNode) {
            $result = $currentNode->getChildren();
        }

        return $result;
    }
}
