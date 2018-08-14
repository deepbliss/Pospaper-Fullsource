<?php
/**
 * Copyright © 2015 CommerceExtensions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace CommerceExtensions\OrderImportExport\Controller\Adminhtml\Data;

use Magento\Framework\Controller\ResultFactory;

class ImportExport extends \CommerceExtensions\OrderImportExport\Controller\Adminhtml\Data
{
    /**
     * Import and export Page
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('CommerceExtensions_OrderImportExport::system_convert_shoppingcartrules');
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('CommerceExtensions\OrderImportExport\Block\Adminhtml\Data\ImportExportHeader')
        );
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('CommerceExtensions\OrderImportExport\Block\Adminhtml\Data\ImportExport')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Orders'));
        $resultPage->getConfig()->getTitle()->prepend(__('Import and Export Orders'));
        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('CommerceExtensions_OrderImportExport::import_export');
    }
}
