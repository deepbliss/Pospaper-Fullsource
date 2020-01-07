<?php

namespace Pos\Custommodule\Observer;

use Magento\Framework\Event\ObserverInterface;

class PaymentMethodIsActive implements ObserverInterface
{
    protected $_customerSession;
    protected $_groupRepository;
    protected $_scopeConfig;
    protected $_state;
    protected $_session;
    protected $_storeManager;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\ResourceModel\GroupRepository $groupRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\State $state,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_customerSession = $customerSession;
        $this->_groupRepository = $groupRepository;
        $this->_scopeConfig = $scopeConfig;
        $this->_state = $state;
        $this->_session = $authSession;
        $this->_storeManager = $storeManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $enabled = false;
        //$enabledGroups = array('Approved Credit Customer','Metro Diner','Gromedia');
        $enabledGroups = $this->_scopeConfig->getValue('pospaper_purchase_orders/settings/customer_groups', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $enabledGroups = explode(',',$enabledGroups);
        $enabledStores = array('metrodiner','redrobin');

        $currentStore = $this->_storeManager->getStore();
        if(in_array($currentStore->getCode(),$enabledStores)) {
            $enabled = true;
        }

        if($this->_customerSession->isLoggedIn() && !$enabled) {
            $customer = $this->_customerSession->getCustomer();
            $customerGroup = $this->_groupRepository->getById($customer->getGroupId());
            $enabled = in_array($customerGroup->getCode(),$enabledGroups);
        }

        if($this->_session->isLoggedIn()) {
            $enabled = true;
        }

        if($observer->getEvent()->getMethodInstance()->getCode() == "purchaseorder")
        {
            $checkResult = $observer->getEvent()->getResult();
            $checkResult->setData('is_available', $enabled);
        }
    }
}
