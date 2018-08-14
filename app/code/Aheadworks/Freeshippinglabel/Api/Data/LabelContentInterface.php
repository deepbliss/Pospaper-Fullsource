<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Label content interface
 * @api
 */
interface LabelContentInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    const LABEL_ID = 'label_id';
    const STORE_ID = 'store_id';
    const CONTENT_TYPE = 'content_type';
    const MESSAGE = 'message';
    /**#@-*/

    /**
     * Get label ID
     *
     * @return int|null
     */
    public function getLabelId();

    /**
     * Set label ID
     *
     * @param int $labelId
     * @return $this
     */
    public function setLabelId($labelId);

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage();

    /**
     * Set message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * Get store ID
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Set store ID
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * Get content type
     *
     * @return string
     */
    public function getContentType();

    /**
     * Set content type
     *
     * @param string $contentType
     * @return $this
     */
    public function setContentType($contentType);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Freeshippinglabel\Api\Data\LabelContentExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Freeshippinglabel\Api\Data\LabelContentExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(LabelContentExtensionInterface $extensionAttributes);
}
