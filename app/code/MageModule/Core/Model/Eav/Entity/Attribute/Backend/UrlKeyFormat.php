<?php

namespace MageModule\Core\Model\Eav\Entity\Attribute\Backend;

/**
 * This class only formats a string like a url key. It does not
 * generate unique keys or create url rewrites
 *
 * Class UrlKeyFormat
 *
 * @package MageModule\Core\Model\Eav\Entity\Attribute\Backend
 */
class UrlKeyFormat extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var \Magento\Framework\Filter\FilterManager
     */
    private $filterManager;

    /**
     * UrlKeyFormat constructor.
     *
     * @param \Magento\Framework\Filter\FilterManager $filterManager
     */
    public function __construct(
        \Magento\Framework\Filter\FilterManager $filterManager
    ) {
        $this->filterManager = $filterManager;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     *
     * @return \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend|void
     */
    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getName();
        if ($object->getData($attributeCode)) {
            $object->setData(
                $attributeCode,
                $this->filterManager->translitUrl(
                    $object->getData($attributeCode)
                )
            );
        }

        parent::beforeSave($object);
    }
}
