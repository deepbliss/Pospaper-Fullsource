<?php
namespace Magento\Sales\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Sales\Api\Data\OrderInterface
 */
interface OrderExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \Magento\Sales\Api\Data\ShippingAssignmentInterface[]|null
     */
    public function getShippingAssignments();

    /**
     * @param \Magento\Sales\Api\Data\ShippingAssignmentInterface[] $shippingAssignments
     * @return $this
     */
    public function setShippingAssignments($shippingAssignments);

    /**
     * @return string|null
     */
    public function getAmazonOrderReferenceId();

    /**
     * @param string $amazonOrderReferenceId
     * @return $this
     */
    public function setAmazonOrderReferenceId($amazonOrderReferenceId);

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
     * @return int|null
     */
    public function getAwUseRewardPoints();

    /**
     * @param int $awUseRewardPoints
     * @return $this
     */
    public function setAwUseRewardPoints($awUseRewardPoints);

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
     * @return float|null
     */
    public function getBaseAwRewardPointsRefunded();

    /**
     * @param float $baseAwRewardPointsRefunded
     * @return $this
     */
    public function setBaseAwRewardPointsRefunded($baseAwRewardPointsRefunded);

    /**
     * @return float|null
     */
    public function getAwRewardPointsRefunded();

    /**
     * @param float $awRewardPointsRefunded
     * @return $this
     */
    public function setAwRewardPointsRefunded($awRewardPointsRefunded);

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
     * @return int|null
     */
    public function getAwRewardPointsBlnceRefunded();

    /**
     * @param int $awRewardPointsBlnceRefunded
     * @return $this
     */
    public function setAwRewardPointsBlnceRefunded($awRewardPointsBlnceRefunded);

    /**
     * @return float|null
     */
    public function getBaseAwRewardPointsRefund();

    /**
     * @param float $baseAwRewardPointsRefund
     * @return $this
     */
    public function setBaseAwRewardPointsRefund($baseAwRewardPointsRefund);

    /**
     * @return float|null
     */
    public function getAwRewardPointsRefund();

    /**
     * @param float $awRewardPointsRefund
     * @return $this
     */
    public function setAwRewardPointsRefund($awRewardPointsRefund);

    /**
     * @return int|null
     */
    public function getAwRewardPointsBlnceRefund();

    /**
     * @param int $awRewardPointsBlnceRefund
     * @return $this
     */
    public function setAwRewardPointsBlnceRefund($awRewardPointsBlnceRefund);

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

    /**
     * @return \Magento\Tax\Api\Data\OrderTaxDetailsAppliedTaxInterface[]|null
     */
    public function getAppliedTaxes();

    /**
     * @param \Magento\Tax\Api\Data\OrderTaxDetailsAppliedTaxInterface[] $appliedTaxes
     * @return $this
     */
    public function setAppliedTaxes($appliedTaxes);

    /**
     * @return \Magento\Tax\Api\Data\OrderTaxDetailsItemInterface[]|null
     */
    public function getItemAppliedTaxes();

    /**
     * @param \Magento\Tax\Api\Data\OrderTaxDetailsItemInterface[] $itemAppliedTaxes
     * @return $this
     */
    public function setItemAppliedTaxes($itemAppliedTaxes);

    /**
     * @return boolean|null
     */
    public function getConvertingFromQuote();

    /**
     * @param boolean $convertingFromQuote
     * @return $this
     */
    public function setConvertingFromQuote($convertingFromQuote);
}
