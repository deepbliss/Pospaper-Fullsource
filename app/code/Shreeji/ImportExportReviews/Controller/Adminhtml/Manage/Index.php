<?php

namespace Shreeji\ImportExportReviews\Controller\Adminhtml\Manage;

class Index extends \Magento\Backend\App\Action {

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Shreeji_ImportExportReviews::customerreview_manage';

    /**
     * Add report breadcrumbs
     *
     * @return $this
     */
    public function _initAction() {
        $this->_view->loadLayout();
        $this->_addBreadcrumb(__('Import Export Customer Reviews'), __('Import Export Customer Reviews'));
        return $this;
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute() {
        try {
            $this->_initAction()->_setActiveMenu(
                    'Shreeji_ImportExportReviews::customerreview_manage'
            )->_addBreadcrumb(
                    __('Import Export Customer Reiews'), __('Import Export Customer Reviews')
            );
            $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Import Export Customer Reviews'));
            $this->_view->renderLayout();
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError(
                    __('An error occurred while showing the import export customer reviews.Please review the log and try again.')
            );
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $this->_redirect('');
            return;
        }
    }

}
