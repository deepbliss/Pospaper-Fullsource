<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Aheadworks\RewardPoints\Api\Data\EarnRateInterface;
use Aheadworks\RewardPoints\Model\ResourceModel\EarnRate as EarnRateResource;

/**
 * Class Aheadworks\RewardPoints\Model\EarnRate
 */
class EarnRate extends \Magento\Framework\Model\AbstractModel implements EarnRateInterface
{
    //@codeCoverageIgnoreStart

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(EarnRateResource::class);
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
        return $this->getData(self::RATE_ID);
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
        return $this->getData(self::WEBSITE_ID);
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
        return $this->getData(self::CUSTOMER_GROUP_ID);
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
        return $this->getData(self::LIFETIME_SALES_AMOUNT);
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
        return $this->getData(self::BASE_AMOUNT);
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
        return $this->getData(self::POINTS);
    }
    //@codeCoverageIgnoreEnd
}
