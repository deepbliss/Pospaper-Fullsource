<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Adminhtml\Reports;

use Amasty\Acart\Controller\Adminhtml\Reports\Ajax;

class Statistics extends \Magento\Framework\View\Element\Template
{
    public function getStatisticFields()
    {
        return [
            Ajax::ABANDONMENT_RATE => $this->escapeHtml(__('Cart Abandonment Rate')),
            Ajax::EMAILS_SENT => $this->escapeHtml(__('Emails Sent')),
            Ajax::CARTS_RESTORED => $this->escapeHtml(__('Carts Restored')),
            Ajax::ORDERS_PLACED => $this->escapeHtml(__('Orders Placed*')),
            Ajax::POTENTIAL_REVENUE => $this->escapeHtml(__('Potential Revenue')),
            Ajax::RECOVERED_REVENUE => $this->escapeHtml(__('Recovered Revenue*')),
            Ajax::EFFICIENCY => $this->escapeHtml(__('Abandoned Cart Email Efficiency')),
        ];
    }
}
