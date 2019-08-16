<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Plugin\Backend\Block;

use Magento\Backend\Block\Menu as NativeMenu;

/**
 * Class Menu
 * @package Amasty\Base\Plugin\Backend\Block
 */
class Menu
{
    const MAX_ITEMS = 10;

    /**
     * @param NativeMenu $subject
     * @param $menu
     * @param int $level
     * @param int $limit
     * @param array $colBrakes
     *
     * @return array
     */
    public function beforeRenderNavigation(NativeMenu $subject, $menu, $level = 0, $limit = 0, $colBrakes = [])
    {
        if ($menu->get('Amasty_Base::marketplace') && $level != 0) {
            $level = 0;
            $limit = self::MAX_ITEMS;
            if (is_array($colBrakes)) {
                foreach ($colBrakes as $key => $colBrake) {
                    if (isset($colBrake['colbrake'])
                        && $colBrake['colbrake']
                    ) {
                        $colBrakes[$key]['colbrake'] = false;
                    }

                    if (isset($colBrake['colbrake']) && (($key - 1) % $limit) == 0) {
                        $colBrakes[$key]['colbrake'] = true;
                    }
                }
            }
        }

        return [$menu, $level, $limit, $colBrakes];
    }

    /**
     * @param NativeMenu $subject
     * @param $html
     *
     * @return string
     */
    public function afterToHtml(NativeMenu $subject, $html)
    {
        $js = $subject->getLayout()->createBlock(\Magento\Backend\Block\Template::class)
            ->setTemplate('Amasty_Base::js.phtml')
            ->toHtml();

        return $html . $js;
    }
}
