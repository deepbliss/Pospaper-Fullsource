<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Plugin\Adminhtml\Block\Widget\Form\Element;

use Magento\Backend\Block\Widget\Form\Element;

/**
 * Class Dependence
 * @package Amasty\Base\Plugin\Adminhtml\Block\Widget\Form\Element
 */
class Dependence
{
    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetadata;

    public function __construct(\Magento\Framework\App\ProductMetadataInterface $productMetadata)
    {
        $this->productMetadata = $productMetadata;
    }

    /**
     * @param Element\Dependence $subject
     * @param \Closure $proceed
     * @param $fieldName
     * @param $fieldNameFrom
     * @param $refField
     * @return Element\Dependence
     */
    public function aroundAddFieldDependence(
        Element\Dependence $subject,
        \Closure $proceed,
        $fieldName,
        $fieldNameFrom,
        $refField
    ) {
        if (version_compare($this->productMetadata->getVersion(), '2.2.0', '<')
            && strpos($fieldName, 'groups[][fields]') !== false
        ) {
            return $subject;
        }

        return $proceed($fieldName, $fieldNameFrom, $refField);
    }
}
