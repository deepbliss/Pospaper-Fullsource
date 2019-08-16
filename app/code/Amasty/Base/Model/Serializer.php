<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */

namespace Amasty\Base\Model;

/**
 * Wrapper for Serialize
 * @since 1.1.0
 */
class Serializer
{
    /**
     * @var null|\Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @var \Magento\Framework\Unserialize\Unserialize
     */
    private $unserialize;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Unserialize\Unserialize $unserialize
    ) {
        if (interface_exists(\Magento\Framework\Serialize\SerializerInterface::class)) {
            // for magento later then 2.2
            $this->serializer = $objectManager->get(\Magento\Framework\Serialize\SerializerInterface::class);
        }
        $this->unserialize = $unserialize;
    }

    public function serialize($value)
    {
        if ($this->serializer === null) {
            //@codingStandardsIgnoreStart
            return serialize($value);
            //@codingStandardsIgnoreEnd
        }

        return $this->serializer->serialize($value);
    }

    public function unserialize($value)
    {
        if ($this->serializer === null) {
            return $this->unserialize->unserialize($value);
        }

        try {
            return $this->serializer->unserialize($value);
        } catch (\InvalidArgumentException $exception) {
            //@codingStandardsIgnoreStart
            return unserialize($value);
            //@codingStandardsIgnoreEnd
        }
    }
}
