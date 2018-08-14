<?php
namespace Magento\Eav\Api\Data;

/**
 * Extension class for @see \Magento\Eav\Api\Data\AttributeGroupInterface
 */
class AttributeGroupExtension extends \Magento\Framework\Api\AbstractSimpleObject implements AttributeGroupExtensionInterface
{
    /**
     * @return string|null
     */
    public function getAttributeGroupCode()
    {
        return $this->_get('attribute_group_code');
    }

    /**
     * @param string $attributeGroupCode
     * @return $this
     */
    public function setAttributeGroupCode($attributeGroupCode)
    {
        $this->setData('attribute_group_code', $attributeGroupCode);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSortOrder()
    {
        return $this->_get('sort_order');
    }

    /**
     * @param string $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder)
    {
        $this->setData('sort_order', $sortOrder);
        return $this;
    }
}
