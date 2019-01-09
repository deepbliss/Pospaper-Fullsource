<?php

namespace Pos\Custommodule\Block\Adminhtml\Orders;

class Link extends \Magento\Backend\Block\Widget
{
    /**
     * Block's template
     *
     * @var string
     */
    protected $_template = 'Pos_Custommodule::orders/link.phtml';

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(\Magento\Backend\Block\Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->setUseContainer(true);
    }

    public function getFormUrl()
    {
        return $this->_urlBuilder->getUrl('pospaper/orders/link');
    }

    public function getSearchFormUrl()
    {
        return $this->_urlBuilder->getUrl('pospaper/orders/search');
    }
}
