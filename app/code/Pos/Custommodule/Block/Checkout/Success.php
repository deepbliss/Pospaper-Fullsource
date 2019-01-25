<?php
namespace Pos\Custommodule\Block\Checkout;

class Success extends \Magento\Checkout\Block\Onepage\Success
{
    /**
     * @return int
     */
    public function getGrandTotal()
    {
        $order = $this->_checkoutSession->getLastRealOrder();
        return $order->getGrandTotal();
    }
}