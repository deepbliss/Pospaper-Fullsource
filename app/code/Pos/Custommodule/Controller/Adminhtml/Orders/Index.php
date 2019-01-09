<?php

namespace Pos\Custommodule\Controller\Adminhtml\Orders;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Pos\Custommodule\Controller\Adminhtml\Index
{
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->addContent($resultPage->getLayout()->createBlock('Pos\Custommodule\Block\Adminhtml\Orders\Header'));
        $resultPage->addContent($resultPage->getLayout()->createBlock('Pos\Custommodule\Block\Adminhtml\Orders\Link'));
        $resultPage->getConfig()->getTitle()->prepend(__('Transfer Order to Customer Account'));
        return $resultPage;
    }
}
