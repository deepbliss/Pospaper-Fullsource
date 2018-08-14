<?php

/* *
 * Copyright © 2016 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Category;

class Tree extends \Magento\Catalog\Block\Adminhtml\Category\Tree
{

    /**
     * overridden to not limit the number of level retrieved to 3 (Magento's default
     * @param mixed|null $parenNodeCategory
     * @return array
     */
    public function getTree($parenNodeCategory = null)
    {
        $rootArray = $this->_getNodeJson($this->getRoot($parenNodeCategory, 99));
        $tree = isset($rootArray['children']) ? $rootArray['children'] : [];
        return $tree;
    }
}
