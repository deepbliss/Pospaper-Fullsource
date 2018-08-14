<?php
namespace Magento\Theme\Api\Data;

/**
 * Extension class for @see \Magento\Theme\Api\Data\DesignConfigInterface
 */
class DesignConfigExtension extends \Magento\Framework\Api\AbstractSimpleObject implements DesignConfigExtensionInterface
{
    /**
     * @return \Magento\Theme\Api\Data\DesignConfigDataInterface[]|null
     */
    public function getDesignConfigData()
    {
        return $this->_get('design_config_data');
    }

    /**
     * @param \Magento\Theme\Api\Data\DesignConfigDataInterface[] $designConfigData
     * @return $this
     */
    public function setDesignConfigData($designConfigData)
    {
        $this->setData('design_config_data', $designConfigData);
        return $this;
    }
}
