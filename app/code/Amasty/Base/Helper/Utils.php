<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Utils
 * @package Amasty\Base\Helper
 */
class Utils extends AbstractHelper
{
    public function _exit($code = 0)
    {
        /** @codingStandardsIgnoreStart */
        exit($code);
        /** @codingStandardsIgnoreEnd */
    }

    public function _echo($a)
    {
        /** @codingStandardsIgnoreStart */
        echo $a;
        /** @codingStandardsIgnoreEnd */
    }
}
