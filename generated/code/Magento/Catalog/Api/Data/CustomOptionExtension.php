<?php
namespace Magento\Catalog\Api\Data;

/**
 * Extension class for @see \Magento\Catalog\Api\Data\CustomOptionInterface
 */
class CustomOptionExtension extends \Magento\Framework\Api\AbstractSimpleObject implements CustomOptionExtensionInterface
{
    /**
     * @return \Magento\Framework\Api\Data\ImageContentInterface|null
     */
    public function getFileInfo()
    {
        return $this->_get('file_info');
    }

    /**
     * @param \Magento\Framework\Api\Data\ImageContentInterface $fileInfo
     * @return $this
     */
    public function setFileInfo(\Magento\Framework\Api\Data\ImageContentInterface $fileInfo)
    {
        $this->setData('file_info', $fileInfo);
        return $this;
    }
}
