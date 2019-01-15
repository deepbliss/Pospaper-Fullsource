<?php

namespace Pos\CreditApp\Controller\Application;

use Magento\Framework\App\Request\DataPersistorInterface;
use Psr\Log\LoggerInterface;
use Pos\CreditApp\Model\CreditAppMailInterface;
use Pos\CreditApp\Model\CreditAppFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\DataObject;
use Magento\Framework\App\Filesystem\DirectoryList;

class Submit extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;

    protected $customerSession;
    protected $mathRandom;
    protected $date;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CreditAppMailInterface
     */
    private $mail;

    protected $creditAppFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Math\Random $mathRandom
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param DataPersistorInterface $dataPersistor
     * @param CreditAppMailInterface $mail
     * @param CreditAppFactory $creditAppFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Math\Random $mathRandom,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        DataPersistorInterface $dataPersistor,
        CreditAppMailInterface $mail,
        CreditAppFactory $creditAppFactory,
        LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->customerSession = $customerSession;
        $this->mathRandom = $mathRandom;
        $this->date = $date;
        $this->dataPersistor = $dataPersistor;
        $this->mail = $mail;
        $this->creditAppFactory = $creditAppFactory;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /*
        if (!$this->customerSession->isLoggedIn()) {
            $this->customerSession->setBeforeAuthUrl($this->_url->getUrl('credit/application', ['_current' => true]));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('customer/account/login');
        }
        */
        $customer = false;

        if (!$this->isPostRequest()) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        try {
            $certificate = $this->uploadCertificate();
            if($certificate === false) {
                throw new \Exception('There was an error uploading your resale certificate. Only JPG, JPEG, GIF, PNG and PDF file types are allowed.');
            }
            $hash = $this->mathRandom->getUniqueHash();
            $model = $this->creditAppFactory->create();
            if ($this->customerSession->isLoggedIn()) {
                $customer = $this->customerSession->getCustomer();
            }
            $data = $this->validatedParams();
            $data['hash'] = $hash;
            $data['filename'] = $certificate;
            $data['dateinsert'] = $this->date->gmtDate();
            $data['datereviewed'] = '';
            $data['credtlimits'] = '';
            $data['approvedby'] = '';
            $data['customerservice'] = '';
            if($customer) {
                $data['customerid'] = $customer->getId();
                $data['customername'] = $customer->getName();
                $data['customeremail'] = $customer->getEmail();
            } else {
                $data['customerid'] = '';
                $data['customername'] = '';
                $data['customeremail'] = '';
            }
            $data['copyto'] = (isset($data['emailcopy']) && $data['emailcopy'] == 1) ? 1:0;
            $this->logger->debug(print_r($data,true));
            $model->setData($data)->save();
            $data['link'] = $this->_url->getUrl('credit/application/view', ['file' => $hash]);
            $this->sendEmail($data);
            $this->messageManager->addSuccessMessage(
                __('Thank you for submitting the credit applicaiton. We\'ll respond to you very soon.')
            );
            $this->dataPersistor->clear('credit_applicaiton');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('credit_applicaiton', $this->getRequest()->getParams());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('credit_applicaiton', $this->getRequest()->getParams());
        }
        return $this->resultRedirectFactory->create()->setPath('credit/application');
    }

    private function uploadCertificate()
    {
        $fileId = 'resale_certificate';
        $fileRequest = $this->getRequest()->getFiles($fileId);
        $fileName = '';
        if ($fileRequest) {
            if (isset($fileRequest['name'])) {
                $fileName = $fileRequest['name'];
            } else {
                $fileName = '';
            }
        }

        if ($fileRequest && strlen($fileName)) {
            /*
             * Save image upload
             */
            try {
                $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'pdf']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);

                /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                    ->getDirectoryRead(DirectoryList::MEDIA);
                $result = $uploader->save(
                    $mediaDirectory->getAbsolutePath(\Pos\CreditApp\Model\CreditApp::BASE_MEDIA_PATH)
                );
                return \Pos\CreditApp\Model\CreditApp::BASE_MEDIA_PATH.$result['file'];
            } catch (\Exception $e) {
                return false;
            }
        }
        return $fileName;
    }

    private function sendEmail($post)
    {
        $this->mail->send(
            $post['billingemail'],
            ['data' => new DataObject($post)]
        );
    }

    private function isPostRequest()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        return !empty($request->getPostValue());
    }

    private function validatedParams()
    {
        $request = $this->getRequest();
        if (trim($request->getParam('fullname')) === '') {
            throw new LocalizedException(__('Name is missing'));
        }
        if (false === \strpos($request->getParam('billingemail'), '@')) {
            throw new LocalizedException(__('Invalid email address'));
        }
        if (trim($request->getParam('hideit')) !== '') {
            throw new \Exception();
        }

        return $request->getParams();
    }
}
