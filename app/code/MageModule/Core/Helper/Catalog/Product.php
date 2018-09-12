<?php
/**
 * Copyright (c) 2018 MageModule: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author       MageModule admin@magemodule.com
 * @copyright   2018 MageModule
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Helper\Catalog;

use Magento\Catalog\Api\Data\ProductInterface;

class Product extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    private $resource;

    /**
     * Product constructor.
     *
     * @param \Magento\Framework\App\Helper\Context        $context
     * @param \Magento\Catalog\Model\ResourceModel\Product $resource
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product $resource
    ) {
        parent::__construct($context);
        $this->resource = $resource;
    }

    /**
     * @return array
     */
    public function getEntityIdSkuPairs()
    {
        $connection = $this->resource->getConnection();
        $select     = $connection->select()->from(
            $this->resource->getEntityTable(),
            ['entity_id', ProductInterface::SKU]
        );

        return $connection->fetchPairs($select);
    }

    /**
     * @return array
     */
    public function getSkuEntityIdPairs()
    {
        $connection = $this->resource->getConnection();
        $select     = $connection->select()->from(
            $this->resource->getEntityTable(),
            [ProductInterface::SKU, 'entity_id']
        );

        return $connection->fetchPairs($select);
    }
}
