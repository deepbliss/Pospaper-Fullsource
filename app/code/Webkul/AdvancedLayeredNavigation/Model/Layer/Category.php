<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_AdvancedLayeredNavigation
 * @author    Webkul
 * @copyright Copyright (c) 2010-2018 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
 
namespace Webkul\AdvancedLayeredNavigation\Model\Layer;

/**
 * Category view layer model
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Category extends \Magento\Catalog\Model\Layer\Category
{
    /**
     * @return \Magento\Catalog\Model\Layer\ItemCollectionProviderInterface
     */
    public function getCollectionProvider()
    {
        return $this->collectionProvider;
    }
}
