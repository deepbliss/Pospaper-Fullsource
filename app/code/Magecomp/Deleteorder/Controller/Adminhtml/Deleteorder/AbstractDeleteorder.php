<?php
namespace Magecomp\Deleteorder\Controller\Adminhtml\Deleteorder;

abstract class AbstractDeleteorder extends \Magento\Backend\App\Action
{
	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('deleteorder');
    }
	
	

    
}