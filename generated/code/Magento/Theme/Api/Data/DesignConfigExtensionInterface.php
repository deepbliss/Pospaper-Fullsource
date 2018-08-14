<?php
namespace Magento\Theme\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Theme\Api\Data\DesignConfigInterface
 */
interface DesignConfigExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \Magento\Theme\Api\Data\DesignConfigDataInterface[]|null
     */
    public function getDesignConfigData();

    /**
     * @param \Magento\Theme\Api\Data\DesignConfigDataInterface[] $designConfigData
     * @return $this
     */
    public function setDesignConfigData($designConfigData);
}
