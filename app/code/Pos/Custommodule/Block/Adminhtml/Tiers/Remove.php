<?php

namespace Pos\Custommodule\Block\Adminhtml\Tiers;

class Remove extends \Magento\Backend\Block\Widget
{
    /**
     * Block's template
     *
     * @var string
     */
    protected $_template = 'Pos_Custommodule::tiers/remove.phtml';

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(\Magento\Backend\Block\Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->setUseContainer(true);
    }
}
