<?php

namespace Nextopia\Search\Controller\Exporter;

use \Magento\Framework\App\Action\Action;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;
use \Nextopia\Search\Helper\Data as NsearchHelperData;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ObjectManager;

class Index extends Action {

    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;

    /** @var \Nextopia\Search\Helper\Data  */
    protected $nsearchHelperData;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(Context $context, PageFactory $resultPageFactory
    , NsearchHelperData $nsearchHelperData) {

        $this->resultPageFactory = $resultPageFactory;
        $this->nsearchHelperData = $nsearchHelperData;
        parent::__construct($context);
    }

    public function execute() {

        $this->_sendUploadResponse();

        return;
    }

    protected function _sendUploadResponse() {

        $storeId = $this->_request->__get('store_id');

        // If extension or cron not enabled , return 404 error
        if (!($this->nsearchHelperData->isEnabled($storeId) &&
                $this->nsearchHelperData->isCronNextopiaEnabled($storeId))) {
            $this->_response->setHttpResponseCode(404)
                    ->setBody("404 Not Found !!!")
                    ->sendResponse();

            return;
        }

        $sAuthUser = $this->nsearchHelperData->getAuthUser($storeId);
        $sAuthPassword = $this->nsearchHelperData->getAuthPw($storeId);

        // Give csv file if username/password are empty or if username/password are set and the request has the right credentials
        if ((empty($sAuthUser) && empty($sAuthPassword)) ||
                (!empty($sAuthUser) && !empty($sAuthPassword) &&
                $this->_request->__get('PHP_AUTH_USER') === $sAuthUser &&
                $this->_request->__get('PHP_AUTH_PW') === $sAuthPassword)
        ) {

            $om = ObjectManager::getInstance();
            $oDir = $om->get('Magento\Framework\App\Filesystem\DirectoryList');
            $baseDir = $oDir->getRoot();
            $varDir = $oDir->getPath($oDir::VAR_DIR);
            $fileName = $varDir . "/nextopia-exporter-files/nextopia-export-$storeId.csv";

            if (!file_exists($fileName)) {
                $this->_response->setHttpResponseCode(404)
                        ->setBody("404 Not Found !!!")
                        ->sendResponse();
                return;
            }

            // Force the content download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileName));
            readfile($fileName);
        } else {
            $this->_response->setHttpResponseCode(401)
                    ->setBody("401 Unauthorized !!!")
                    ->sendResponse();
        }

        return;
    }

}
