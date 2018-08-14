<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Renderer;

/**
 * Link renderer
 */
class Link extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    protected $_storeManager = null;
    protected $_objectManager = null;
    protected $_list = null;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param \Wyomind\SimpleGoogleShopping\Helper\Data $sgsHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Wyomind\SimpleGoogleShopping\Helper\Data $sgsHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_sgsHelper = $sgsHelper;
    }

    /**
     * Renders grid column
     * @param  \Magento\Framework\Object $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        return $this->_sgsHelper->generationStats($row);
    }
}
