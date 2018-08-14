<?php
namespace Magento\Sales\Api\Data;

/**
 * Extension class for @see \Magento\Sales\Api\Data\CreditmemoCreationArgumentsInterface
 */
class CreditmemoCreationArgumentsExtension extends \Magento\Framework\Api\AbstractSimpleObject implements CreditmemoCreationArgumentsExtensionInterface
{
    /**
     * @return int[]|null
     */
    public function getReturnToStockItems()
    {
        return $this->_get('return_to_stock_items');
    }

    /**
     * @param int[] $returnToStockItems
     * @return $this
     */
    public function setReturnToStockItems($returnToStockItems)
    {
        $this->setData('return_to_stock_items', $returnToStockItems);
        return $this;
    }
}
