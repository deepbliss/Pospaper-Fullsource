<?php

namespace Pos\Custommodule\Plugin\Controller\Category;

class View
{

    protected $_storeManager;
    protected $_resultRedirectFactory;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context
    )
    {
        $this->_storeManager = $storeManager;
        $this->_resultRedirectFactory = $context->getResultRedirectFactory();
    }

    public function afterExecute($subject,$result)
    {
        $enabledStores = array('metrodiner','redrobin');
        $currentStore = $this->_storeManager->getStore();
        if(in_array($currentStore->getCode(),$enabledStores)) {
            return $this->_resultRedirectFactory->create()->setPath('/');
        }

        return $result;
    }
}