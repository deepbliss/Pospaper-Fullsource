<?php

namespace Pos\Custommodule\Plugin;

class LoginPostPlugin
{

    protected $_storeManager;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_storeManager = $storeManager;
    }

    public function afterExecute(
        \Magento\Customer\Controller\Account\LoginPost $subject,
        $result)
    {
        $currentStore = $this->_storeManager->getStore();
        if($currentStore->getCode() == 'metrodiner') {
            $result->setPath('/');
        }

        return $result;
    }
}