<?php
namespace Magento\Quote\Api\Data;

/**
 * Extension class for @see \Magento\Quote\Api\Data\TotalsInterface
 */
class TotalsExtension extends \Magento\Framework\Api\AbstractSimpleObject implements TotalsExtensionInterface
{
    /**
     * @return string|null
     */
    public function getCouponLabel()
    {
        return $this->_get('coupon_label');
    }

    /**
     * @param string $couponLabel
     * @return $this
     */
    public function setCouponLabel($couponLabel)
    {
        $this->setData('coupon_label', $couponLabel);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAwRewardPointsAmount()
    {
        return $this->_get('aw_reward_points_amount');
    }

    /**
     * @param float $awRewardPointsAmount
     * @return $this
     */
    public function setAwRewardPointsAmount($awRewardPointsAmount)
    {
        $this->setData('aw_reward_points_amount', $awRewardPointsAmount);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getBaseAwRewardPointsAmount()
    {
        return $this->_get('base_aw_reward_points_amount');
    }

    /**
     * @param float $baseAwRewardPointsAmount
     * @return $this
     */
    public function setBaseAwRewardPointsAmount($baseAwRewardPointsAmount)
    {
        $this->setData('base_aw_reward_points_amount', $baseAwRewardPointsAmount);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAwRewardPoints()
    {
        return $this->_get('aw_reward_points');
    }

    /**
     * @param int $awRewardPoints
     * @return $this
     */
    public function setAwRewardPoints($awRewardPoints)
    {
        $this->setData('aw_reward_points', $awRewardPoints);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAwRewardPointsDescription()
    {
        return $this->_get('aw_reward_points_description');
    }

    /**
     * @param string $awRewardPointsDescription
     * @return $this
     */
    public function setAwRewardPointsDescription($awRewardPointsDescription)
    {
        $this->setData('aw_reward_points_description', $awRewardPointsDescription);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAwRewardPointsShippingAmount()
    {
        return $this->_get('aw_reward_points_shipping_amount');
    }

    /**
     * @param float $awRewardPointsShippingAmount
     * @return $this
     */
    public function setAwRewardPointsShippingAmount($awRewardPointsShippingAmount)
    {
        $this->setData('aw_reward_points_shipping_amount', $awRewardPointsShippingAmount);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getBaseAwRewardPointsShippingAmount()
    {
        return $this->_get('base_aw_reward_points_shipping_amount');
    }

    /**
     * @param float $baseAwRewardPointsShippingAmount
     * @return $this
     */
    public function setBaseAwRewardPointsShippingAmount($baseAwRewardPointsShippingAmount)
    {
        $this->setData('base_aw_reward_points_shipping_amount', $baseAwRewardPointsShippingAmount);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAwRewardPointsShipping()
    {
        return $this->_get('aw_reward_points_shipping');
    }

    /**
     * @param int $awRewardPointsShipping
     * @return $this
     */
    public function setAwRewardPointsShipping($awRewardPointsShipping)
    {
        $this->setData('aw_reward_points_shipping', $awRewardPointsShipping);
        return $this;
    }
}
