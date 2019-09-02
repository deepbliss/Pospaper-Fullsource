<?php

namespace Pos\Custommodule\Controller\Volume;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Pos\Custommodule\Model\CustommoduleMailInterface;


class Inquiry extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $mail;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        CustommoduleMailInterface $mail
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->mail = $mail;
        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->isPostRequest()) {
            try {
                $data = $this->validatedParams();
                $this->sendEmail($data);
                $this->messageManager->addSuccessMessage(
                    __('Thank you for your message. We\'ll respond to you very soon.')
                );
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong.'));
            }
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }

    private function sendEmail($post)
    {
        $this->mail->send(
            $post['email'],
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
        if (trim($request->getParam('fname')) === '') {
            throw new LocalizedException(__('Name is missing'));
        }
        if (false === \strpos($request->getParam('email'), '@')) {
            throw new LocalizedException(__('Invalid email address'));
        }

        return $request->getParams();
    }
}