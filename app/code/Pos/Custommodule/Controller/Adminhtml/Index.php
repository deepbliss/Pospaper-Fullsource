<?php

namespace Pos\Custommodule\Controller\Adminhtml;

abstract class Index extends \Magento\Backend\App\Action
{
    protected $_resource;
    protected $_appState;
    protected $_logger;
    protected $_resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\State $appState,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->_resource = $resource;
        $this->_appState = $appState;
        $this->_logger = $logger;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
}
