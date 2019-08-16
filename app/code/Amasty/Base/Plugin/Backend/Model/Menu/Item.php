<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Plugin\Backend\Model\Menu;

use Magento\Backend\Model\Menu\Item as NativeItem;

/**
 * Class Item
 * @package Amasty\Base\Plugin\Backend\Model\Menu
 */
class Item
{
    const BASE_MARKETPLACE = 'Amasty_Base::marketplace';

    const SEO_PARAMS = '?utm_source=extension&utm_medium=backend&utm_campaign=main_menu_to_user_guide';

    //phpcs:ignore Magento2.Files.LineLength.MaxExceeded
    const MARKET_URL = 'https://amasty.com/magento-2-extensions.html?utm_source=extension&utm_medium=backend&utm_campaign=main_menu_to_catalog';

    /**
     * @var \Amasty\Base\Helper\Module
     */
    private $moduleHelper;

    public function __construct(
        \Amasty\Base\Helper\Module $moduleHelper
    ) {
        $this->moduleHelper = $moduleHelper;
    }

    /**
     * @param NativeItem $subject
     * @param $url
     *
     * @return string
     */
    public function afterGetUrl(NativeItem $subject, $url)
    {
        $id = $subject->getId();
        if ($id == self::BASE_MARKETPLACE) {
            $url = self::MARKET_URL;
        }

        /* we can't add guide link into item object - find link again */
        if (strpos($id, '::menuguide') !== false
            && strpos($id, 'Amasty') !== false
        ) {
            $moduleCode = explode('::', $subject->getId());
            $moduleCode = $moduleCode[0];
            $moduleInfo = $this->moduleHelper->getFeedModuleData($moduleCode);
            if (isset($moduleInfo['guide']) && $moduleInfo['guide']) {
                $url = $moduleInfo['guide'];
                $seoLink = self::SEO_PARAMS;
                if (strpos($url, '?') !== false) {
                    $seoLink = str_replace('?', '&', $seoLink);
                }
                $url .= $seoLink;
            } else {
                $url = '';
            }
        }

        return $url;
    }
}
