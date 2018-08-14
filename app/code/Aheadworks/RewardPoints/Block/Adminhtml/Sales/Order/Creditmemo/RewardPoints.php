<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Adminhtml\Sales\Order\Creditmemo;

use Aheadworks\RewardPoints\Model\Config;
use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;

/**
 * Class RewardPoints
 *
 * @package Aheadworks\RewardPoints\Block\Adminhtml\Sales\Order\Creditmemo
 */
class RewardPoints extends \Magento\Backend\Block\Template
{
    /**
     * @var string
     */
    protected $_template = 'Aheadworks_RewardPoints::sales/order/creditmemo/rewardpoints.phtml';

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Config $config,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve credit memo
     *
     * @return \Magento\Sales\Model\Order\Creditmemo
     */
    public function getCreditmemo()
    {
        return $this->coreRegistry->registry('current_creditmemo');
    }

    /**
     * Check whether can refund reward points to customer
     *
     * @return bool
     */
    public function canRefund()
    {
        if ($this->getCreditmemo()->getOrder()->getCustomerIsGuest() && !$this->isRefundOffline()) {
            return false;
        }
        return true;
    }

    /**
     * Retrieve value to refund on reward points
     *
     * @return float
     */
    public function getRefundToRewardPoints()
    {
        return $this->getCreditmemo()->getAwRewardPointsRefundValue();
    }

    /**
     * Check that is auto refund or not
     *
     * @return bool
     */
    public function isRewardPointsRefundAutomatically()
    {
        return $this->config->isRewardPointsRefundAutomatically();
    }

    /**
     * Check that is offline refund or not
     *
     * @return bool
     */
    private function isRefundOffline()
    {
        if ($this->getCreditmemo()->getInvoice() && $this->getCreditmemo()->getInvoice()->getTransactionId()) {
            return false;
        }
        return true;
    }
}
