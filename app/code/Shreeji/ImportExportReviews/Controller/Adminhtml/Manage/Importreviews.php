<?php

namespace Shreeji\ImportExportReviews\Controller\Adminhtml\Manage;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Importreviews extends \Magento\Backend\App\Action {

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Shreeji_ImportExportReviews::customerreview_manage';

    /**
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory 
     */
    protected $_fileUploaderFactory;

    const IMPORT_REVIEWDIRECTORY = '';

    /**
     *
     * @var \Magento\Framework\Filesystem 
     */
    protected $fileSystem;

    /**
     *
     * @var type \Shreeji\ImportExportReviews\Model\ImportReviews
     */
    protected $_importModel;

    /**
     * 
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param Filesystem $fileSystem
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Shreeji\ImportExportReviews\Model\ImportReviews $_importModel
     */
    public function __construct(
    \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory, Filesystem $fileSystem, \Magento\Backend\App\Action\Context $context, \Shreeji\ImportExportReviews\Model\ImportReviews $_importModel
    ) {
        parent::__construct($context);
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->fileSystem = $fileSystem;
        $this->_importModel = $_importModel;
    }

    /**
     * Savepayment action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute() {
        try {
            $resultRedirect = $this->resultRedirectFactory->create();
            $uploader = $this->_fileUploaderFactory->create(['fileId' => 'importreviews']);
            $uploader->setAllowedExtensions(['csv']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $path = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA)
                    ->getAbsolutePath('importreviews/');
            $uploader->getUploadedFileName();
            $result = $uploader->save($path);
            if (is_array($result) && isset($result['path']) && isset($result['file'])) {
                $fileName = 'importreviews' . $result['file'];
                $this->_importModel->importReviews($fileName);
            } else {
                $this->messageManager->addError(__('Unable to upload CSV file.'));
                return $resultRedirect->setPath('importexportreviews/manage/index');
            }
            $this->messageManager->addSuccess(__('Customer reviews successfully imported.'));
            return $resultRedirect->setPath('importexportreviews/manage/index');
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            return $resultRedirect->setPath('importexportreviews/manage/index');
        }
        $this->messageManager->addError(__('We can\'t save data.'));
        return $resultRedirect->setPath('importexportreviews/manage/index');
    }

}
