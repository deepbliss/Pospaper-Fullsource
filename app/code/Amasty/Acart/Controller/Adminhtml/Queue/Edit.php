<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Queue;

use Magento\Framework\Exception\NoSuchEntityException;

class Edit extends \Amasty\Acart\Controller\Adminhtml\Queue
{
    /**
     * Customer edit action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $historyId = (int)$this->getRequest()->getParam('id');

        $history = $this->_objectManager->create('Amasty\Acart\Model\History')
            ->load($historyId);

        if (!$history->getId()) {
            $this->messageManager->addError(__('Something went wrong while editing the queue.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('amasty_acart/*/index');

            return $resultRedirect;
        }

        $this->initCurrentQueue($history);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_Acart::acart_rule');
        $this->prepareDefaultCustomerTitle($resultPage);
        $resultPage->setActiveMenu('Amasty_Acart::acart');

        $resultPage->getConfig()->getTitle()->prepend(__('Edit queue item #%1', $historyId));

        return $resultPage;
    }
}
