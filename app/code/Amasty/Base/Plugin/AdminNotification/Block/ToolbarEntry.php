<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Plugin\AdminNotification\Block;

use Magento\AdminNotification\Block\ToolbarEntry as NativeToolbarEntry;

class ToolbarEntry
{
    const AMASTY_ATTRIBUTE = ' data-ambase-logo="1"';

    public function afterToHtml(
        NativeToolbarEntry $subject,
        $html
    ) {
        $collection = $subject->getLatestUnreadNotifications()
            ->clear()
            ->addFieldToFilter('is_amasty', 1);

        foreach ($collection as $item) {
            $search = 'data-notification-id="' . $item->getId() . '"';
            $html = str_replace($search, $search . self::AMASTY_ATTRIBUTE, $html);
        }


        return $html;
    }
}
