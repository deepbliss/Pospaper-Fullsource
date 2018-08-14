<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Plugin\Controller\Onepage;

class SuccessPlugin
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
    }

    /**
     * @param \Magento\Checkout\Controller\Onepage\Success $subject
     * @return null
     */
    public function beforeExecute(\Magento\Checkout\Controller\Onepage\Success $subject)
    {
        if ($message = $this->customerSession->getAmcheckoutRegisterMessage()) {
            switch ($message['type']) {
                case 'error':
                    $this->messageManager->addErrorMessage($message['text']);
                    break;
                case 'success':
                    $this->messageManager->addSuccessMessage($message['text']);
                    break;
            }
            $this->customerSession->unsAmcheckoutRegisterMessage();
        }

        return null;
    }
}
