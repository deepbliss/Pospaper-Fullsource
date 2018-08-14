<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Model\Sales;

use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Sales\Model\Order\Invoice;

/**
 * Class Aheadworks\RewardPoints\Plugin\Model\Sales\InvoicePlugin
 */
class InvoicePlugin
{
    /**
     * @var CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsService;

    /**
     * @var bool
     */
    private $isInvoicePaid = false;

    /**
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     */
    public function __construct(
        CustomerRewardPointsManagementInterface $customerRewardPointsService
    ) {
        $this->customerRewardPointsService = $customerRewardPointsService;
    }

    /**
     * Setting paid flag to invoice for adding points
     *
     * @param Invoice $invoice
     * @return Invoice
     */
    public function afterPay(Invoice $invoice)
    {
        $this->isInvoicePaid = true;
        return $invoice;
    }

    /**
     * Add earned points to customer after save invoice
     *
     * @param Invoice $subject
     * @return Invoice
     */
    public function afterAfterSave(Invoice $subject)
    {
        if ($this->isInvoicePaid) {
            $this->customerRewardPointsService->addPointsForPurchases($subject->getEntityId());
            $this->isInvoicePaid = false;
        }
        return $subject;
    }
}
