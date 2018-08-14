<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Nextopia\Search\Plugin;

class Minification
{
    /**
     * @param string $contentType
     * @return string[]
     */
    public function afterGetExcludes(\Magento\Framework\View\Asset\Minification $subject, $result)
    {
        $result[] = "/cdn.nextopia.net/";
        return $result;
    }
}
