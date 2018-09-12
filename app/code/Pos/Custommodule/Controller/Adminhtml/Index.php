<?php

namespace Pos\Custommodule\Controller\Adminhtml;

abstract class Index extends \Magento\Backend\App\Action
{
    protected $_resource;

    protected $_appState;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\State $appState
    ) {
        $this->_resource = $resource;
        $this->_appState = $appState;
        parent::__construct($context);
    }
}
