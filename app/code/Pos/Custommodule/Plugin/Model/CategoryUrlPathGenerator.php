<?php

namespace Pos\Custommodule\Plugin\Model;

class CategoryUrlPathGenerator
{
    public function afterGetUrlPath(\Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator $subject, $path)
    {
        if (strpos($path, 'full-catalog/') !== false) {
            $path = str_replace('full-catalog/','',$path);
        }

        return $path;
    }
}