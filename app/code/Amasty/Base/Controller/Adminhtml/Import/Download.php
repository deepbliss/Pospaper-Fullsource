<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Controller\Adminhtml\Import;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Module\Dir\Reader;

/**
 * Class Download
 * @package Amasty\Base\Controller\Adminhtml\Import
 */
class Download
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var ReadFactory
     */
    private $readFactory;

    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    public function __construct(
        Reader $reader,
        ReadFactory $readFactory,
        FileFactory $fileFactory,
        RequestInterface $request,
        ManagerInterface $messageManager,
        ResultFactory $resultFactory
    ) {
        $this->reader = $reader;
        $this->readFactory = $readFactory;
        $this->fileFactory = $fileFactory;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->resultFactory = $resultFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\Controller\Result\Raw
     */
    public function downloadSample($moduleName)
    {
        if (empty($moduleName)) {
            return $this->emptyModuleName();
        }

        if (!preg_match('/[a-z0-9\-]+/i', $this->request->getParam('filename'))) {

            return $this->noEntityFound();
        }
        $fileName = $this->request->getParam('filename') . '.csv';
        $moduleDir = $this->reader->getModuleDir('', $moduleName);
        $fileAbsolutePath = $moduleDir . '/Files/Sample/' . $fileName;
        $directoryRead = $this->readFactory->create($moduleDir);
        $filePath = $directoryRead->getRelativePath($fileAbsolutePath);
        if (!$directoryRead->isFile($filePath)) {

            return $this->noEntityFound();
        }

        $this->fileFactory->create(
            $fileName,
            null,
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
            'application/octet-stream',
            $directoryRead->stat($filePath)['size']
        );
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $resultRaw->setContents($directoryRead->readFile($filePath));
        return $resultRaw;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    private function noEntityFound()
    {
        $this->messageManager->addErrorMessage(__('There is no sample file for this entity.'));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/');

        return $resultRedirect;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    private function emptyModuleName()
    {
        $this->messageManager->addErrorMessage(__('Module Name is empty.'));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/');

        return $resultRedirect;
    }
}
