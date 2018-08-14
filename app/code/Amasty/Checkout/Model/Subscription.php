<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Newsletter\Model\Subscriber;
use Magento\Newsletter\Model\SubscriberFactory;

class Subscription 
{
    /**
     * @var SubscriberFactory
     */
    protected $subscriberFactory;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    public function __construct(
        SubscriberFactory $subscriberFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {

        $this->subscriberFactory = $subscriberFactory;
        $this->messageManager = $messageManager;
        $this->checkoutSession = $checkoutSession;
    }

    public function subscribe($email = null)
    {
        if (is_null($email)) {
            $email = $this->checkoutSession->getQuote()->getCustomerEmail();
        }
        
        try {
            $status = $this->subscriberFactory->create()->subscribe($email);
            if ($status == Subscriber::STATUS_NOT_ACTIVE) {
                $this->messageManager->addSuccessMessage(__('The confirmation request has been sent.'));
            } else {
                $this->messageManager->addSuccessMessage(__('Thank you for your subscription.'));
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('There was a problem with the subscription: %1', $e->getMessage())
            );
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong with the subscription.'));
        }
    }
}
