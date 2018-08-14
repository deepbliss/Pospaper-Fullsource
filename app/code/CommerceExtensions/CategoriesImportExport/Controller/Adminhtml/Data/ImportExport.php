<?php
/**
 * Copyright © 2015 CommerceExtensions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace CommerceExtensions\CategoriesImportExport\Controller\Adminhtml\Data;

use Magento\Framework\Controller\ResultFactory;

class ImportExport extends \CommerceExtensions\CategoriesImportExport\Controller\Adminhtml\Data
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

        $resultPage->setActiveMenu('CommerceExtensions_CategoriesImportExport::system_convert_categories');
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('CommerceExtensions\CategoriesImportExport\Block\Adminhtml\Data\ImportExportHeader')
        );
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('CommerceExtensions\CategoriesImportExport\Block\Adminhtml\Data\ImportExport')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Categories'));
        $resultPage->getConfig()->getTitle()->prepend(__('Import and Export Categories'));
        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('CommerceExtensions_CategoriesImportExport::import_export');
    }
}
