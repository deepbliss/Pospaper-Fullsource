<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Onepage\Success;

use Magento\Store\Model\ScopeInterface;

class Cms extends \Magento\Cms\Block\Block
{
    public function getBlockId()
    {
        return (int)$this->_scopeConfig->getValue(
            'amasty_checkout/success_page/block_id', ScopeInterface::SCOPE_STORE
        );
    }
}
