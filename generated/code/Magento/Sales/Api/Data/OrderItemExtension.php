<?php
namespace Magento\Sales\Api\Data;

/**
 * Extension class for @see \Magento\Sales\Api\Data\OrderItemInterface
 */
class OrderItemExtension extends \Magento\Framework\Api\AbstractSimpleObject implements OrderItemExtensionInterface
{
    /**
     * @return \Magento\GiftMessage\Api\Data\MessageInterface|null
     */
    public function getGiftMessage()
    {
        return $this->_get('gift_message');
    }

    /**
     * @param \Magento\GiftMessage\Api\Data\MessageInterface $giftMessage
     * @return $this
     */
    public function setGiftMessage(\Magento\GiftMessage\Api\Data\MessageInterface $giftMessage)
    {
        $this->setData('gift_message', $giftMessage);
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
     * @return float|null
     */
    public function getBaseAwRewardPointsInvoiced()
    {
        return $this->_get('base_aw_reward_points_invoiced');
    }

    /**
     * @param float $baseAwRewardPointsInvoiced
     * @return $this
     */
    public function setBaseAwRewardPointsInvoiced($baseAwRewardPointsInvoiced)
    {
        $this->setData('base_aw_reward_points_invoiced', $baseAwRewardPointsInvoiced);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAwRewardPointsInvoiced()
    {
        return $this->_get('aw_reward_points_invoiced');
    }

    /**
     * @param float $awRewardPointsInvoiced
     * @return $this
     */
    public function setAwRewardPointsInvoiced($awRewardPointsInvoiced)
    {
        $this->setData('aw_reward_points_invoiced', $awRewardPointsInvoiced);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAwRewardPointsBlnceInvoiced()
    {
        return $this->_get('aw_reward_points_blnce_invoiced');
    }

    /**
     * @param int $awRewardPointsBlnceInvoiced
     * @return $this
     */
    public function setAwRewardPointsBlnceInvoiced($awRewardPointsBlnceInvoiced)
    {
        $this->setData('aw_reward_points_blnce_invoiced', $awRewardPointsBlnceInvoiced);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getBaseAwRewardPointsReimbursed()
    {
        return $this->_get('base_aw_reward_points_reimbursed');
    }

    /**
     * @param float $baseAwRewardPointsReimbursed
     * @return $this
     */
    public function setBaseAwRewardPointsReimbursed($baseAwRewardPointsReimbursed)
    {
        $this->setData('base_aw_reward_points_reimbursed', $baseAwRewardPointsReimbursed);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAwRewardPointsReimbursed()
    {
        return $this->_get('aw_reward_points_reimbursed');
    }

    /**
     * @param float $awRewardPointsReimbursed
     * @return $this
     */
    public function setAwRewardPointsReimbursed($awRewardPointsReimbursed)
    {
        $this->setData('aw_reward_points_reimbursed', $awRewardPointsReimbursed);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAwRewardPointsBlnceReimbursed()
    {
        return $this->_get('aw_reward_points_blnce_reimbursed');
    }

    /**
     * @param int $awRewardPointsBlnceReimbursed
     * @return $this
     */
    public function setAwRewardPointsBlnceReimbursed($awRewardPointsBlnceReimbursed)
    {
        $this->setData('aw_reward_points_blnce_reimbursed', $awRewardPointsBlnceReimbursed);
        return $this;
    }
}
