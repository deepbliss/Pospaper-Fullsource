<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author       MageModule, LLC admin@magemodule.com
 * @copyright   2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Controller\Adminhtml\Orderimportexport;

/**
 * Adminhtml Data controller
 */
class Index extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'MageModule_OrderImportExport::import_export';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Index constructor.
     *
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Psr\Log\LoggerInterface                   $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->logger            = $logger;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $this->getRequest()->setParam('id', 0);
            $resultPage = $this->resultPageFactory->create();
            $resultPage->setActiveMenu('MageModule_OrderImportExport::system_convert_orderimportexport');
            $resultPage->getConfig()->getTitle()->prepend(__('Order Import/Export'));
            $resultPage->addBreadcrumb(__('Order Import/Export'), __('Order Import/Export'));
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(__('Exception occurred during Order Import/Export page load'));
            $resultRedirect->setPath('adminhtml/index');

            return $resultRedirect;
        }

        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        $isAllowedImport = $this->_authorization->isAllowed(
            \MageModule\OrderImportExport\Model\Config\Import::ADMIN_RESOURCE
        );
        $isAllowedExport = $this->_authorization->isAllowed(
            \MageModule\OrderImportExport\Model\Config\Export::ADMIN_RESOURCE
        );

        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE) && ($isAllowedExport || $isAllowedImport);
    }
}
