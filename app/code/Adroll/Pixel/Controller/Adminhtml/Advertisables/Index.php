<?php

namespace Adroll\Pixel\Controller\Adminhtml\Advertisables;

use Magento\Backend\App\Action;

class Index extends Action
{
    protected $_publicActions = ['index'];

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->addBlock('Adroll\Pixel\Block\Adminhtml\Advertisables', 'advertisables');
        $this->_view->getLayout()->getBlock('page.title')->setPageTitle('AdRoll Integration');
        $this->_view->getLayout()->setChild('content', 'advertisables', 'advertisables');
        $this->_view->renderLayout();
    }
}
