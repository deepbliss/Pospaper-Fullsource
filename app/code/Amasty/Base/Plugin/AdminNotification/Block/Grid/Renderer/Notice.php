<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Plugin\AdminNotification\Block\Grid\Renderer;

use Magento\AdminNotification\Block\Grid\Renderer\Notice as NativeNotice;

class Notice
{
    public function aroundRender(
        NativeNotice $subject,
        \Closure $proceed,
        \Magento\Framework\DataObject $row
    ) {
        $result = $proceed($row);

        $amastyLogo = $row->getData('is_amasty') ? ' amasty-grid-logo' : '';
        $result = '<div class="ambase-grid-message' . $amastyLogo .'">' . $result . '</div>';

        return  $result;
    }
}
