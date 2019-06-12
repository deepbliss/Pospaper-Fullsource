<?php

namespace Adroll\Pixel\Controller\Adminhtml\Authenticate;

use Magento\Backend\App\Action;

class Index extends Action
{
    protected $_publicActions = ['index'];

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->addBlock('Adroll\Pixel\Block\Adminhtml\StoreSelector', 'storeSelector');
        $this->_view->getLayout()->getBlock('page.title')->setPageTitle('AdRoll Integration');
        $this->_view->getLayout()->setChild('content', 'storeSelector', 'storeSelector');
        $this->_view->renderLayout();
    }
}
