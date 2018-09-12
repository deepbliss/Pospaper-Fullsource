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

use \MageModule\OrderImportExport\Exception\ImportException;
use Magento\Framework\Exception\LocalizedException;

class Import extends \Magento\Backend\App\Action
{
    /**
     * @var \MageModule\OrderImportExport\Api\Data\ImportConfigInterface
     */
    private $config;

    /**
     * @var \MageModule\OrderImportExport\Api\ImporterInterface
     */
    private $importer;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var \MageModule\Core\Framework\File\Csv
     */
    private $csvProcessor;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $allowedExtensions = ['csv', 'txt'];

    /**
     * Import constructor.
     *
     * @param \MageModule\OrderImportExport\Api\Data\ImportConfigInterface $config
     * @param \MageModule\OrderImportExport\Api\ImporterInterface          $importer
     * @param \Magento\Framework\Controller\Result\RedirectFactory         $redirectFactory
     * @param \Magento\Backend\App\Action\Context                          $context
     * @param \Magento\Framework\File\UploaderFactory                      $uploaderFactory
     * @param \MageModule\Core\Framework\File\Csv                          $csvProcessor
     * @param \Psr\Log\LoggerInterface                                     $logger
     */
    public function __construct(
        \MageModule\OrderImportExport\Api\Data\ImportConfigInterface $config,
        \MageModule\OrderImportExport\Api\ImporterInterface $importer,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\File\UploaderFactory $uploaderFactory,
        \MageModule\Core\Framework\File\Csv $csvProcessor,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->config          = $config;
        $this->importer        = $importer;
        $this->redirectFactory = $redirectFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->csvProcessor    = $csvProcessor;
        $this->logger          = $logger;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*', ['active_tab' => 'import_tab']);

        $messages = [
            'error'   => [],
            'success' => []
        ];

        $imported    = 0;
        $error       = 0;
        $errorImport = 0;

        try {
            $data = $this->getRequest()
                ->getParam('import', []);
            $this->config->addData($data);
            $this->config->saveConfig();

            $files = $this->getRequest()
                ->getFiles('import');

            /** @var \Magento\Framework\File\Uploader $upload */
            $upload = $this->uploaderFactory->create(['fileId' => $files['file']]);
            $upload->setAllowedExtensions($this->allowedExtensions);
            if (!$upload->checkAllowedExtension($upload->getFileExtension())) {
                throw new LocalizedException(
                    __('Invalid file type. Only %1 files are allowed.', implode(' and ', $this->allowedExtensions))
                );
            }

            $errorLimit = $this->config->getErrorLimit();

            $this->csvProcessor->setDelimiter($this->config->getDelimiter());
            $this->csvProcessor->setEnclosure($this->config->getEnclosure());
            $this->csvProcessor->setStream($files['file']['tmp_name']);

            $headers = [];
            $key = 0;
            while ($row = $this->csvProcessor->streamReadCsv()) {
                if ($key === 0) {
                    $headers = $row;
                } else {
                    try {
                        $row = array_combine($headers, $row);
                        $this->importer->import($row);
                        $imported++;
                    } catch (ImportException $e) {
                        if ($e->isImported()) {
                            $errorImport++;
                            $messages['error'][] = 'Row #' . $key . ': ' . $e->getMessage();
                        } else {
                            $error++;
                            $messages['error'][] = 'Row #' . $key . ': ' . $e->getMessage();
                        }
                    } catch (\Exception $e) {
                        $error++;
                        $messages['error'][] = 'Row #' . $key . ': ' . $e->getMessage();
                    }

                    if ($errorLimit <= ($error + $errorImport)) {
                        $messages['error'][] = '';
                        $messages['error'][] = sprintf(
                            'Import has been stopped at row #%d because the error limit of %d was reached',
                            $key,
                            $errorLimit
                        );
                        break;
                    }
                }
                $key++;
            }

            $this->csvProcessor->unsetStream();

            if ($key < 1) {
                throw new LocalizedException(
                    __('Your file does not contain any orders.')
                );
            }

            if ($imported) {
                $this->messageManager->addSuccessMessage(__('%1 orders imported successfully', $imported));
            }

            if ($error) {
                $this->messageManager->addErrorMessage(__('%1 orders were not imported due to error(s)', $error));
            }

            if ($errorImport) {
                $this->messageManager->addWarningMessage(
                    __(
                        '%1 orders were imported but may have experienced error(s) during the creation of invoices, shipments, or credit memos',
                        $errorImport
                    )
                );
            }

            if ($messages['error']) {
                foreach ($messages['error'] as $message) {
                    $this->messageManager->addErrorMessage($message);
                }
            }

            if ($messages['success']) {
                foreach ($messages['success'] as $message) {
                    $this->messageManager->addSuccessMessage($message);
                }
            }
        } catch (LocalizedException $e) {
            $this->logger->critical(__($e->getMessage()));
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }

        return $resultRedirect;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(
            \MageModule\OrderImportExport\Model\Config\Import::ADMIN_RESOURCE
        );
    }
}
