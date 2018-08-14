<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds;

/**
 * Preview block
 */
class Preview extends \Magento\Backend\Block\Template
{

    protected $_sgsModel = null;
    protected $_coreHelper = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Wyomind\SimpleGoogleShopping\Model\Feeds $sgsModel
     * @param \Wyomind\Core\Helper\Data $coreHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Wyomind\SimpleGoogleShopping\Model\Feeds $sgsModel,
        \Wyomind\Core\Helper\Data $coreHelper,
        array $data = []
    )
    {
        $this->_sgsModel = $sgsModel;
        $this->_coreHelper = $coreHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get content of the block
     * @return string
     * @throws \Exception
     */
    public function getContent()
    {

        $request = $this->getRequest();
        $id = $request->getParam('simplegoogleshopping_id');

        if ($id != 0) {
            try {
                $this->_sgsModel->load($id);
                $this->_sgsModel->limit = $this->_coreHelper->getStoreConfig('simplegoogleshopping/system/preview');
                $this->_sgsModel->setDisplay(true);
                $content = $this->_sgsModel->generateXml($request);
                return $content;
            } catch (\Exception $e) {
                return __('Unable to generate the data feed : ' . $e->getMessage());
            }
        }
    }
}
