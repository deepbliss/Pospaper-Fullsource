<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Block\Adminhtml;

class Messages extends \Magento\Backend\Block\Template
{
    const AMASTY_BASE_SECTION_NAME = 'amasty_base';
    /**
     * @var \Amasty\Base\Model\AdminNotification\Messages
     */
    private $messageManager;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Amasty\Base\Model\AdminNotification\Messages $messageManager,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->messageManager = $messageManager;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messageManager->getMessages();
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        $html  = '';
        if ($this->request->getParam('section') == self::AMASTY_BASE_SECTION_NAME) {
            $html = parent::_toHtml();
        }

        return $html;
    }
}
