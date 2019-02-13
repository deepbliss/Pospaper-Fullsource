<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Model\ResourceModel\Interval;

/**
 * Collection Class
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'interval_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'paradoxlabs_subscription_interval_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'interval_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \ParadoxLabs\Subscriptions\Model\Interval::class,
            \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval::class
        );
    }

    /**
     * Add product filter to the current collection.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface
     * @return $this
     */
    public function addProductFilter(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $this->addFieldToFilter(
            'main_table.product_id',
            $product->getData('row_id') ?: $product->getId()
        );

        return $this;
    }

    /**
     * Sort intervals according to the associated custom options.
     *
     * @return $this
     */
    public function applyValueSortOrder()
    {
        $this->getSelect()->joinLeft(
            [
                'option_value' => $this->getTable('catalog_product_option_type_value')
            ],
            'option_value.option_id=main_table.option_id AND option_value.option_type_id=main_table.value_id',
            [
                'sort_order',
            ]
        );

        $this->setOrder('sort_order', self::SORT_ORDER_ASC);

        return $this;
    }
}
