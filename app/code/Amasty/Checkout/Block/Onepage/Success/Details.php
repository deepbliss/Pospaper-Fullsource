<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Onepage\Success;

use Magento\Framework\View\Element\Template;

class Details extends Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $session;

    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Checkout\Model\Session $session,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->session = $session;
    }

    protected function _prepareLayout()
    {
        if (!$this->registry->registry('current_order')) {
            $this->registry->register('current_order', $this->getOrder());
        }

        return parent::_prepareLayout();
    }

    /**
     * Retrieve current order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->session->getLastRealOrder();
    }
}
