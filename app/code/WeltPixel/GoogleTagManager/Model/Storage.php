<?php
namespace WeltPixel\GoogleTagManager\Model;

/**
 * Class \WeltPixel\GoogleTagManager\Model\Storage
 */
class Storage extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry
    )
    {
        parent::__construct($context, $registry);
    }
}
