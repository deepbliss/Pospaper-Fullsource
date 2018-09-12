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

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;

class Export extends \Magento\Backend\App\Action
{
    /**
     * @var \MageModule\Core\Helper\File
     */
    private $helper;

    /**
     * @var \MageModule\OrderImportExport\Api\Data\ExportConfigInterface
     */
    private $config;

    /**
     * @var \MageModule\OrderImportExport\Api\ExporterInterfaceFactory
     */
    private $exporterFactory;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    private $fileFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Export constructor.
     *
     * @param \MageModule\Core\Helper\File                                 $helper
     * @param \MageModule\OrderImportExport\Api\Data\ExportConfigInterface $config
     * @param \MageModule\OrderImportExport\Api\ExporterInterfaceFactory   $exporterFactory
     * @param \Magento\Backend\App\Action\Context                          $context
     * @param \Magento\Framework\App\Response\Http\FileFactory             $fileFactory
     * @param \Magento\Framework\Controller\Result\RedirectFactory         $redirectFactory
     * @param \Psr\Log\LoggerInterface                                     $logger
     */
    public function __construct(
        \MageModule\Core\Helper\File $helper,
        \MageModule\OrderImportExport\Api\Data\ExportConfigInterface $config,
        \MageModule\OrderImportExport\Api\ExporterInterfaceFactory $exporterFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->helper          = $helper;
        $this->config          = $config;
        $this->exporterFactory = $exporterFactory;
        $this->fileFactory     = $fileFactory;
        $this->redirectFactory = $redirectFactory;
        $this->logger          = $logger;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $data = $this->getRequest()->getParam('export', []);
            $this->config->addData($data);
            $this->config->saveConfig();

            /** @var \MageModule\OrderImportExport\Api\ExporterInterface $exporter */
            $exporter = $this->exporterFactory->create(['config' => $this->config]);

            $data = $exporter->getData();
            if (empty($data)) {
                throw new LocalizedException(__('There are no orders that meet the search criteria.'));
            }
            $filepath = $exporter->export(false);

            $file = $this->fileFactory->create(
                $this->helper->getBasename($filepath),
                [
                    'type'  => 'filename',
                    'value' => $this->helper->getRelativePath($filepath)
                ],
                DirectoryList::ROOT,
                'application/csv'
            );
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__($e->getMessage()));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*', ['active_tab' => 'export_tab']);

            return $resultRedirect;
        }

        return $file;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(
            \MageModule\OrderImportExport\Model\Config\Export::ADMIN_RESOURCE
        );
    }
}
