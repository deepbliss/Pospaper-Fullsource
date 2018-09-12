<?php
namespace Magento\Sales\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Sales\Api\Data\OrderItemInterface
 */
interface OrderItemExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \Magento\GiftMessage\Api\Data\MessageInterface|null
     */
    public function getGiftMessage();

    /**
     * @param \Magento\GiftMessage\Api\Data\MessageInterface $giftMessage
     * @return $this
     */
    public function setGiftMessage(\Magento\GiftMessage\Api\Data\MessageInterface $giftMessage);

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
     * @return float|null
     */
    public function getBaseAwRewardPointsInvoiced();

    /**
     * @param float $baseAwRewardPointsInvoiced
     * @return $this
     */
    public function setBaseAwRewardPointsInvoiced($baseAwRewardPointsInvoiced);

    /**
     * @return float|null
     */
    public function getAwRewardPointsInvoiced();

    /**
     * @param float $awRewardPointsInvoiced
     * @return $this
     */
    public function setAwRewardPointsInvoiced($awRewardPointsInvoiced);

    /**
     * @return int|null
     */
    public function getAwRewardPointsBlnceInvoiced();

    /**
     * @param int $awRewardPointsBlnceInvoiced
     * @return $this
     */
    public function setAwRewardPointsBlnceInvoiced($awRewardPointsBlnceInvoiced);

    /**
     * @return float|null
     */
    public function getBaseAwRewardPointsReimbursed();

    /**
     * @param float $baseAwRewardPointsReimbursed
     * @return $this
     */
    public function setBaseAwRewardPointsReimbursed($baseAwRewardPointsReimbursed);

    /**
     * @return float|null
     */
    public function getAwRewardPointsReimbursed();

    /**
     * @param float $awRewardPointsReimbursed
     * @return $this
     */
    public function setAwRewardPointsReimbursed($awRewardPointsReimbursed);

    /**
     * @return int|null
     */
    public function getAwRewardPointsBlnceReimbursed();

    /**
     * @param int $awRewardPointsBlnceReimbursed
     * @return $this
     */
    public function setAwRewardPointsBlnceReimbursed($awRewardPointsBlnceReimbursed);
}
