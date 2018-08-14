<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Adminhtml\Sales\Order;

use Magento\Framework\DataObject\Factory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Aheadworks\RewardPoints\Block\Adminhtml\Sales\Order\Total
 */
class Total extends Template
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param Context $context
     * @param Factory $factory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Factory $factory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->factory = $factory;
    }

    /**
     * Retrieve sales order model
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock) {
            return $parentBlock->getOrder();
        }
        return null;
    }

    /**
     * Retrieve totals source object
     *
     * @return \Magento\Sales\Model\Order|\Magento\Sales\Model\Order\Invoice
     */
    public function getSource()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock) {
            return $parentBlock->getSource();
        }
        return null;
    }

    /**
     * Initialize reward points order total
     *
     * @return \Aheadworks\RewardPoints\Block\Sales\Order\Total
     */
    public function initTotals()
    {
        $order = $this->getOrder();
        if ($order) {
            $source = $this->getSource();
            if ($source) {
                if ($order->getAwUseRewardPoints() && $source->getBaseAwRewardPointsAmount()) {
                    $this->getParentBlock()->addTotal(
                        $this->factory->create(
                            [
                                'code'       => 'aw_reward_points',
                                'strong'     => false,
                                'label'      => $source->getAwRewardPointsDescription(),
                                'value'      => $source->getAwRewardPointsAmount(),
                                'base_value' => $source->getBaseAwRewardPointsAmount(),
                            ]
                        )
                    );
                }
                if ($source->getEntityType() == 'creditmemo' && !$source->getEntityId()) {
                } else {
                    if ($source->getAwRewardPointsBlnceRefund()) {
                        $this->getParentBlock()->addTotal(
                            $this->factory->create(
                                [
                                    'code'       => 'aw_reward_points_refund',
                                    'strong'     => true,
                                    'label'      =>
                                        __('%1 Returned to Reward Points', $source->getAwRewardPointsBlnceRefund()),
                                    'value'      => $source->getAwRewardPointsRefund(),
                                    'base_value' => $source->getBaseAwRewardPointsRefund(),
                                    'area'       => 'footer'
                                ]
                            ),
                            'grand_total'
                        );
                    }
                    if ($source->getAwRewardPointsBlnceReimbursed()) {
                        $this->getParentBlock()->addTotal(
                            $this->factory->create(
                                [
                                    'code'       => 'aw_reward_points_reimbursed',
                                    'strong'     => true,
                                    'label'      => __(
                                        '%1 Reimbursed spent Reward Points',
                                        $source->getAwRewardPointsBlnceReimbursed()
                                    ),
                                    'value'      => $source->getAwRewardPointsReimbursed(),
                                    'base_value' => $source->getBaseAwRewardPointsReimbursed(),
                                    'area'       => 'footer'
                                ]
                            ),
                            'grand_total'
                        );
                    }
                }
            }
        }
        return $this;
    }
}
