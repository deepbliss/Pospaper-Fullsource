<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Email;

class Unsubscribe extends \Amasty\Acart\Controller\Email\Url
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $history = $this->getHistory();

        if ($history) {
            $blacklist = $this->_objectManager->get('Amasty\Acart\Model\Blacklist')
                ->load($history->getCustomerEmail(), 'customer_email');

            if (!$blacklist->getId()) {
                $blacklist->addData(
                    [
                        'customer_email' => $history->getCustomerEmail()
                    ]
                );

                $blacklist->save();
            }

            $this->messageManager->addSuccess(__('You have been unsubscribed'));
        }

        return $resultRedirect->setPath('checkout/cart');
    }
}
