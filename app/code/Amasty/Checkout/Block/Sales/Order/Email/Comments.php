<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Sales\Order\Email;

class Comments extends \Magento\Sales\Block\Order\View
{

    protected function _construct()
    {
        parent::_construct();

        $this
            ->setTemplate('Amasty_Checkout::onepage/details/comments.phtml')
            ->setData('area', 'frontend')
        ;
    }

    /**
     * @return bool|mixed
     */
    public function getOrder()
    {
        if ($order = $this->getData('order')) {
            return $order;
        }

        if ($this->_coreRegistry->registry('current_order')) {
            return $this->_coreRegistry->registry('current_order');
        }

        return false;
    }
}

