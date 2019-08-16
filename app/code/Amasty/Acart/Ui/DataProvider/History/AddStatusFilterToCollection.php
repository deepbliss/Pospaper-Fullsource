<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Amasty\Acart\Ui\DataProvider\History;

use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;

class AddStatusFilterToCollection implements AddFilterToCollectionInterface
{
    public function addFilter(Collection $collection, $field, $condition = null)
    {
        $collection->addFieldToFilter('main_table.' . $field, $condition);
    }
}
