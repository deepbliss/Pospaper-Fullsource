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
namespace Webkul\AdvancedLayeredNavigation\Model\ResourceModel\Fulltext;

/**
 * Fulltext Collection
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Collection extends \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection
{
    /**
     * @var array
     */
    protected $_addedFilters = [];

    /**
     * Apply attribute filter to facet collection
     *
     * @param string $field
     * @param null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if (is_string($field)) {
            $this->_addedFilters[$field] = $condition;
        }
        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Get applied filters
     *
     * @return array
     */
    public function getAddedFilters()
    {
        return $this->_addedFilters;
    }
}
