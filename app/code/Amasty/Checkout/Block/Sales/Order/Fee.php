<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */

namespace Amasty\Checkout\Block\Sales\Order;

use Amasty\Checkout\Model\ResourceModel\Fee\CollectionFactory as FeeCollectionFactory;
use Magento\Framework\View\Element\Context;

class Fee extends \Magento\Framework\View\Element\AbstractBlock
{
    /**
     * @var FeeCollectionFactory
     */
    protected $feeCollectionFactory;

    public function __construct(
        Context $context,
        FeeCollectionFactory $feeCollectionFactory,
        array $data = []

    ) {
        $this->feeCollectionFactory = $feeCollectionFactory;
        return parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();

        /** @var \Magento\Sales\Model\Order $order */
        $order = $parent->getOrder();

        $feesQuoteCollection = $this->feeCollectionFactory->create()
            ->addFieldToFilter('quote_id', $order->getQuoteId());

        $feeAmount = 0;
        $baseFeeAmount = 0;

        /** @var \Amasty\Checkout\Model\Fee $fee */
        foreach ($feesQuoteCollection as $fee) {
            $feeAmount += $fee->getData('amount');
            $baseFeeAmount += $fee->getData('base_amount');
        }

        if ($feeAmount > 0) {
            $fee = new \Magento\Framework\DataObject([
                'code'       => 'amasty_checkout',
                'strong'     => false,
                'value'      => $feeAmount,
                'base_value' => $baseFeeAmount,
                'label'      => __('Gift Wrap'),
            ]);

            $parent->addTotal($fee, 'amasty_checkout');
        }

        return $this;
    }
}
