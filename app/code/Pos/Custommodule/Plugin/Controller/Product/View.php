<?php

namespace Pos\Custommodule\Plugin\Controller\Product;

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
        $currentStore = $this->_storeManager->getStore();
        if($currentStore->getCode() == 'metrodiner') {
            return $this->_resultRedirectFactory->create()->setPath('/');
        }

        return $result;
    }
}