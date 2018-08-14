<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Model\Sales;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderExtension;

/**
 * Class OrderPlugin
 *
 * @package Aheadworks\RewardPoints\Plugin\Model\Sales
 */
class OrderPlugin
{
    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * @param OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * Add points data to order
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderCollection $resultOrder
     * @return OrderCollection
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderCollection $resultOrder)
    {
        foreach ($resultOrder->getItems() as $order) {
            $this->afterGet($subject, $order);
        }
        return $resultOrder;
    }

    /**
     * Get reward points
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $resultOrder
     * @return OrderInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $resultOrder)
    {
        $extensionAttributes = $resultOrder->getExtensionAttributes();
        if ($this->isAwPointsDataAlreadySet($extensionAttributes)) {
            return $resultOrder;
        }

        /** @var OrderExtension $orderExtension */
        $orderExtension = $extensionAttributes
            ? $extensionAttributes
            : $this->orderExtensionFactory->create();
        $this->setAwPointsData($resultOrder, $orderExtension);
        $resultOrder->setExtensionAttributes($orderExtension);

        return $resultOrder;
    }

    /**
     * Is points data already set
     *
     * @param OrderExtensionInterface $extensionAttributes
     * @return bool
     */
    private function isAwPointsDataAlreadySet($extensionAttributes)
    {
        return $extensionAttributes && $extensionAttributes->getAwUseRewardPoints();
    }

    /**
     * Set points data
     *
     * @param OrderInterface $resultOrder
     * @param OrderExtension $orderExtension
     * @return void
     */
    private function setAwPointsData(OrderInterface $resultOrder, OrderExtension $orderExtension)
    {
        $orderExtension->setAwUseRewardPoints($resultOrder->getData('aw_use_reward_points'));
        $orderExtension->setAwRewardPointsAmount($resultOrder->getData('aw_reward_points_amount'));
        $orderExtension->setBaseAwRewardPointsAmount($resultOrder->getData('base_aw_reward_points_amount'));
        $orderExtension->setAwRewardPoints($resultOrder->getData('aw_reward_points'));
        $orderExtension->setAwRewardPointsShippingAmount($resultOrder->getData('aw_reward_points_shipping_amount'));
        $orderExtension->setBaseAwRewardPointsShippingAmount(
            $resultOrder->getData('base_aw_reward_points_shipping_amount')
        );
        $orderExtension->setAwRewardPointsShipping($resultOrder->getData('aw_reward_points_shipping'));
        $orderExtension->setAwRewardPointsDescription($resultOrder->getData('aw_reward_points_description'));
        $orderExtension->setBaseAwRewardPointsInvoiced($resultOrder->getData('base_aw_reward_points_invoiced'));
        $orderExtension->setAwRewardPointsInvoiced($resultOrder->getData('aw_reward_points_invoiced'));
        $orderExtension->setBaseAwRewardPointsRefunded($resultOrder->getData('base_aw_reward_points_refunded'));
        $orderExtension->setAwRewardPointsRefunded($resultOrder->getData('aw_reward_points_refunded'));
        $orderExtension->setAwRewardPointsBlnceInvoiced($resultOrder->getData('aw_reward_points_blnce_invoiced'));
        $orderExtension->setAwRewardPointsBlnceRefunded($resultOrder->getData('aw_reward_points_blnce_refunded'));
        $orderExtension->setBaseAwRewardPointsRefund($resultOrder->getData('base_aw_reward_points_refund'));
        $orderExtension->setAwRewardPointsRefund($resultOrder->getData('aw_reward_points_refund'));
        $orderExtension->setAwRewardPointsBlnceRefund($resultOrder->getData('aw_reward_points_blnce_refund'));
        $orderExtension->setBaseAwRewardPointsReimbursed($resultOrder->getData('base_aw_reward_points_reimbursed'));
        $orderExtension->setAwRewardPointsReimbursed($resultOrder->getData('aw_reward_points_reimbursed'));
        $orderExtension->setAwRewardPointsBlnceReimbursed($resultOrder->getData('aw_reward_points_blnce_reimbursed'));
    }
}
