<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds;

/**
 * Status update in the grid
 */
class Updater extends \Magento\Backend\App\Action
{

    protected $_jsonDecoder;
    protected $_jsonEncoder;
    protected $_renderer;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Json\DecoderInterface $jsonDecoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Renderer\Status $renderer
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Renderer\Status $renderer
    )
    {
        $this->_jsonDecoder = $jsonDecoder;
        $this->_jsonEncoder = $jsonEncoder;
        $this->_renderer = $renderer;
        parent::__construct($context);
    }

    /**
     * Does the menu is allowed
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Wyomind_SimpleGoogleShopping::main');
    }

    /**
     * Execute action
     */
    public function execute()
    {
        $json = [];
        $data = $this->_jsonDecoder->decode($this->getRequest()->getParam('data'));
        foreach ($data as $f) {
            $row = new \Magento\Framework\DataObject();
            $row->setSimplegoogleshoppingId($f[0]);
            $row->setCronExpr($f[1]);
            $json[] = ["id" => $f[0], "content" => ($this->_renderer->render($row))];
        }
        $this->getResponse()->representJson($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode($json));
    }
}
