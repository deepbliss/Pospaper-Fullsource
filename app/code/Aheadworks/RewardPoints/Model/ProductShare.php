<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Aheadworks\RewardPoints\Api\Data\ProductShareInterface;
use Aheadworks\RewardPoints\Model\ResourceModel\ProductShare as ProductShareResource;

/**
 * Class Aheadworks\RewardPoints\Model\ProductShare
 */
class ProductShare extends \Magento\Framework\Model\AbstractModel implements ProductShareInterface
{
    //@codeCoverageIgnoreStart

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(ProductShareResource::class);
    }

    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {
        return $this->setData(self::SHARE_ID, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return parent::getData(self::SHARE_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);
    }

    /**
     * {@inheritDoc}
     */
    public function getWebsiteId()
    {
        return parent::getData(self::WEBSITE_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerId()
    {
        return parent::getData(self::CUSTOMER_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductId()
    {
        return parent::getData(self::PRODUCT_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setNetwork($network)
    {
        return $this->setData(self::NETWORK, $network);
    }

    /**
     * {@inheritDoc}
     */
    public function getNetwork()
    {
        return parent::getData(self::NETWORK);
    }
    //@codeCoverageIgnoreEnd
}
