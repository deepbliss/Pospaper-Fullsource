<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Amasty\Acart\Block;

use Magento\Checkout\Model\Cart as CustomerCart;
use Amasty\Acart\Model\QuoteEmail as QuoteEmail;

/**
 * Base html block
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class GrabEmail extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CustomerCart
     */
    protected $_cart;

    /**
     * @var QuoteEmail
     */
    private $quoteEmail;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        CustomerCart $cart,
        QuoteEmail $quoteEmail,
        array $data = []
    ) {
        $this->_cart = $cart;
        $this->quoteEmail = $quoteEmail;

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getGrabUrl()
    {
        return $this->_urlBuilder->getUrl('amasty_acart/email/grab');
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        $ret = false;

        if ($this->_cart->getQuote() && !$this->_cart->getCustomerId()) {
            $ret = true;
        }

        return $ret;
    }

    /**
     * @return int
     */
    public function getNeedLogEmail()
    {
        return (int)$this->quoteEmail->isNeedLogEmail();
    }
}
