<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */

/**
 * Event observers configuration schema locator
 */
namespace Amasty\Base\Model\LessToCss\Config;

/**
 * Class SchemaLocator
 * @package Amasty\Base\Model\LessToCss\Config
 */
class SchemaLocator implements \Magento\Framework\Config\SchemaLocatorInterface
{
    /** @var \Magento\Framework\Config\Dom\UrnResolver */
    protected $urnResolver;

    public function __construct(\Magento\Framework\Config\Dom\UrnResolver $urnResolver)
    {
        $this->urnResolver = $urnResolver;
    }

    /**
     * Get path to merged config schema
     *
     * @return string
     */
    public function getSchema()
    {
        return $this->urnResolver->getRealPath('urn:amasty:module:Amasty_Base:etc/less_to_css.xsd');
    }

    /**
     * Get path to pre file validation schema
     *
     * @return string
     */
    public function getPerFileSchema()
    {
        return $this->getSchema();
    }
}
