<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Aheadworks\RewardPoints\Api\Data\SpendRateInterface;
use Aheadworks\RewardPoints\Model\ResourceModel\SpendRate as SpendRateResource;

/**
 * Class Aheadworks\RewardPoints\Model\SpendRate
 */
class SpendRate extends \Magento\Framework\Model\AbstractModel implements SpendRateInterface
{
    //@codeCoverageIgnoreStart

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(SpendRateResource::class);
    }

    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {
        return $this->setData(self::RATE_ID, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return parent::getData(self::RATE_ID);
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
    public function setCustomerGroupId($customerGroupId)
    {
        return $this->setData(self::CUSTOMER_GROUP_ID, $customerGroupId);
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerGroupId()
    {
        return parent::getData(self::CUSTOMER_GROUP_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setLifetimeSalesAmount($lifetimeSalesAmount)
    {
        return $this->setData(self::LIFETIME_SALES_AMOUNT, $lifetimeSalesAmount);
    }

    /**
     * {@inheritDoc}
     */
    public function getLifetimeSalesAmount()
    {
        return parent::getData(self::LIFETIME_SALES_AMOUNT);
    }

    /**
     * {@inheritDoc}
     */
    public function setPoints($points)
    {
        return $this->setData(self::POINTS, $points);
    }

    /**
     * {@inheritDoc}
     */
    public function getPoints()
    {
        return parent::getData(self::POINTS);
    }

    /**
     * {@inheritDoc}
     */
    public function setBaseAmount($baseAmount)
    {
        return $this->setData(self::BASE_AMOUNT, $baseAmount);
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseAmount()
    {
        return parent::getData(self::BASE_AMOUNT);
    }
    //@codeCoverageIgnoreEnd
}
