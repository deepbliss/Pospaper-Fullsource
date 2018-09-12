<?php
namespace Magento\Quote\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Quote\Api\Data\TotalsInterface
 */
interface TotalsExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return string|null
     */
    public function getCouponLabel();

    /**
     * @param string $couponLabel
     * @return $this
     */
    public function setCouponLabel($couponLabel);

    /**
     * @return float|null
     */
    public function getAwRewardPointsAmount();

    /**
     * @param float $awRewardPointsAmount
     * @return $this
     */
    public function setAwRewardPointsAmount($awRewardPointsAmount);

    /**
     * @return float|null
     */
    public function getBaseAwRewardPointsAmount();

    /**
     * @param float $baseAwRewardPointsAmount
     * @return $this
     */
    public function setBaseAwRewardPointsAmount($baseAwRewardPointsAmount);

    /**
     * @return int|null
     */
    public function getAwRewardPoints();

    /**
     * @param int $awRewardPoints
     * @return $this
     */
    public function setAwRewardPoints($awRewardPoints);

    /**
     * @return string|null
     */
    public function getAwRewardPointsDescription();

    /**
     * @param string $awRewardPointsDescription
     * @return $this
     */
    public function setAwRewardPointsDescription($awRewardPointsDescription);

    /**
     * @return float|null
     */
    public function getAwRewardPointsShippingAmount();

    /**
     * @param float $awRewardPointsShippingAmount
     * @return $this
     */
    public function setAwRewardPointsShippingAmount($awRewardPointsShippingAmount);

    /**
     * @return float|null
     */
    public function getBaseAwRewardPointsShippingAmount();

    /**
     * @param float $baseAwRewardPointsShippingAmount
     * @return $this
     */
    public function setBaseAwRewardPointsShippingAmount($baseAwRewardPointsShippingAmount);

    /**
     * @return int|null
     */
    public function getAwRewardPointsShipping();

    /**
     * @param int $awRewardPointsShipping
     * @return $this
     */
    public function setAwRewardPointsShipping($awRewardPointsShipping);
}
