<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Api;

interface NewsletterManagementInterface
{
    /**
     * Set payment information before redirect to payment for customer.
     *
     * @param string $cartId
     * @param mixed|null $amcheckoutData
     * @return void.
     */
    public function subscribe($cartId, $amcheckoutData);
}