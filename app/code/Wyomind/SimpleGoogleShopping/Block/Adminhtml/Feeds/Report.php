<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds;

/**
 * Report block
 */
class Report extends \Magento\Backend\Block\Template
{

    protected $_sgsModel = null;
    protected $_sgsHelper = null;
    protected $_coreHelper = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Wyomind\SimpleGoogleShopping\Model\Feeds $sgsModel
     * @param \Wyomind\SimpleGoogleShopping\Helper\Data $sgsHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Wyomind\SimpleGoogleShopping\Model\Feeds $sgsModel,
        \Wyomind\SimpleGoogleShopping\Helper\Data $sgsHelper,
        \Wyomind\Core\Helper\Data $coreHelper,
        array $data = []
    )
    {
        $this->_sgsModel = $sgsModel;
        $this->_sgsHelper = $sgsHelper;
        $this->_coreHelper = $coreHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get block content
     * @return string
     */
    public function getContent()
    {

        $request = $this->getRequest();
        $id = $request->getParam('simplegoogleshopping_id');

        if ($id != 0) {
            $this->_sgsModel->load($id);
            $this->_sgsModel->limit = $this->_coreHelper->getStoreConfig('simplegoogleshopping/system/preview');
            $this->_sgsModel->setDisplay(false);
            return $this->_sgsHelper->reportToHtml(unserialize($this->_sgsModel->getSimplegoogleshoppingReport()));
        }
    }
}
