<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model;

use Aheadworks\Freeshippinglabel\Api\Data\LabelContentInterface;
use Aheadworks\Freeshippinglabel\Api\Data\LabelContentExtensionInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * LabelContent class
 */
class LabelContent extends AbstractExtensibleObject implements LabelContentInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLabelId()
    {
        return $this->_get(self::LABEL_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setLabelId($labelId)
    {
        return $this->setData(self::LABEL_ID, $labelId);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        return $this->_get(self::STORE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType()
    {
        return $this->_get(self::CONTENT_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setContentType($contentType)
    {
        return $this->setData(self::CONTENT_TYPE, $contentType);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->_get(self::MESSAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(LabelContentExtensionInterface $extensionAttributes)
    {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
