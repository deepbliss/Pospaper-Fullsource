<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Email\Items;

class Quote extends \Magento\Framework\View\Element\Template
{
    /**
     * @return array
     */
    public function getItems()
    {
        return $this->getQuote()->getAllVisibleItems();
    }
}
