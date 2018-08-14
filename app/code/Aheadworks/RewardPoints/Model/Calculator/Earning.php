<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Calculator;

use Aheadworks\RewardPoints\Model\Source\Calculation\PointsEarning;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\TotalsInterface;
use Magento\Sales\Api\Data\CreditmemoInterface;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Aheadworks\RewardPoints\Model\Config;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class Earning
 *
 * @package Aheadworks\RewardPoints\Model\Calculator
 */
class Earning
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var RateCalculator
     */
    private $rateCalculator;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @param Config $config
     * @param RateCalculator $rateCalculator
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Config $config,
        RateCalculator $rateCalculator,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->config = $config;
        $this->rateCalculator = $rateCalculator;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Retrieve calculation earning points value
     *
     * @param int $customerId
     * @param OrderInterface|CreditmemoInterface|TotalsInterface|InvoiceInterface $object
     * @param int|null $websiteId
     * @return int
     */
    public function calculation($object, $customerId, $websiteId = null)
    {
        $baseSubTotal = 0;
        $shippingDiscount = 0;
        if ($object instanceof InvoiceInterface || $object instanceof CreditmemoInterface) {
            $order = $this->getOrderById($object->getOrderId());
            if ($order) {
                // Checking if discount shipping amount was added in first invoices
                if ($object instanceof InvoiceInterface && count($order->getInvoiceCollection()->getItems()) == 1) {
                    $shippingDiscount = $order->getBaseShippingDiscountAmount()
                        + $order->getBaseAwRewardPointsShippingAmount();
                }

                if ($object instanceof CreditmemoInterface) {
                    // Calculate how much shipping discount should be applied
                    // basing on how much shipping should be refunded
                    $creditmemoBaseShippingAmount = (float)$object->getBaseShippingAmount();
                    if ($creditmemoBaseShippingAmount) {
                        $shippingDiscount = $creditmemoBaseShippingAmount *
                            ($order->getBaseAwRewardPointsShippingAmount() + $order->getBaseShippingDiscountAmount()) /
                            $order->getBaseShippingAmount();
                    }
                }
            }
        }
        if ($object instanceof TotalsInterface) {
            $shippingDiscount = $object->getBaseShippingDiscountAmount()
                + $object->getExtensionAttributes()->getBaseAwRewardPointsShippingAmount();
        }

        switch ($this->config->getPointsEarningCalculation($websiteId)) {
            case PointsEarning::BEFORE_TAX:
                $baseSubTotal = $object->getBaseGrandTotal()
                    - $object->getBaseShippingAmount()
                    + $shippingDiscount
                    - $object->getBaseTaxAmount();
                break;
            case PointsEarning::AFTER_TAX:
                $baseSubTotal = $object->getBaseGrandTotal()
                    - $object->getBaseShippingAmount()
                    + $shippingDiscount;
                break;
        }
        if ($baseSubTotal <= 0) {
            return 0;
        }
        return $this->rateCalculator->calculateEarnPoints($customerId, $baseSubTotal, $websiteId);
    }

    /**
     * Retrieve order object by id
     *
     * @param int $orderId
     * @return OrderInterface|bool
     */
    private function getOrderById($orderId)
    {
        try {
            return $this->orderRepository->get($orderId);
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }
}
