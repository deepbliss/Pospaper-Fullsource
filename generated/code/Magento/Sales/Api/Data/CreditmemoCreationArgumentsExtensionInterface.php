<?php
namespace Magento\Sales\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Sales\Api\Data\CreditmemoCreationArgumentsInterface
 */
interface CreditmemoCreationArgumentsExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return int[]|null
     */
    public function getReturnToStockItems();

    /**
     * @param int[] $returnToStockItems
     * @return $this
     */
    public function setReturnToStockItems($returnToStockItems);
}
