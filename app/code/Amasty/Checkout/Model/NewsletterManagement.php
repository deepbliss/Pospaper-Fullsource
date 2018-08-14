<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model;

use Amasty\Checkout\Api\NewsletterManagementInterface;

class NewsletterManagement implements NewsletterManagementInterface
{
    protected $checkoutDataHelper;

    public function __construct(
        \Amasty\Checkout\Helper\CheckoutData $checkoutDataHelper
    ) {
        $this->checkoutDataHelper = $checkoutDataHelper;
    }

    /**
     * Set payment information before redirect to payment for customer.
     *
     * @param string $cartId
     * @param mixed|null $amcheckoutData
     * @return void.
     */
    public function subscribe($cartId, $amcheckoutData)
    {
        $this->checkoutDataHelper->beforePlaceOrder($amcheckoutData);

        $this->checkoutDataHelper->afterPlaceOrder($amcheckoutData);
    }
}