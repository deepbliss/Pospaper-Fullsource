<?php
namespace Magento\Eav\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Eav\Api\Data\AttributeGroupInterface
 */
interface AttributeGroupExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return string|null
     */
    public function getAttributeGroupCode();

    /**
     * @param string $attributeGroupCode
     * @return $this
     */
    public function setAttributeGroupCode($attributeGroupCode);

    /**
     * @return string|null
     */
    public function getSortOrder();

    /**
     * @param string $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);
}
