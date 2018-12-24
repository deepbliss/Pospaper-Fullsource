<?php

namespace Pos\Custommodule\Block\Html;

class Login extends \Magento\Framework\View\Element\Template
{
    protected $_customerUrl;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Url $customerUrl,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_customerUrl = $customerUrl;
    }

    public function getPostActionUrl()
    {
        return $this->_customerUrl->getLoginPostUrl();
    }
}