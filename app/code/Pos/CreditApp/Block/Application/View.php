<?php

namespace Pos\CreditApp\Block\Application;

use Pos\CreditApp\Model\CreditAppFactory;
use Pos\CreditApp\Model\ResourceModel\CreditApp\CollectionFactory as CreditAppCollectionFactory;

class View extends \Magento\Framework\View\Element\Template
{
    protected $storeManager;

    protected $creditAppFactory;

    /**
     * Constructor
     *
     * @param CreditAppCollectionFactory $creditAppFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        CreditAppCollectionFactory $creditAppFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->creditAppFactory = $creditAppFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    public function getFile()
    {
        $file = null;
        $hash = $this->getRequest()->getParam('file');
        $creditApp = $this->creditAppFactory->create()
            ->addFieldToFilter('hash',$hash)
            ->getFirstItem();
        if($creditApp->getId()) {
            if($creditApp->getFilename()) {
                $file = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$creditApp->getFilename();
            }
        }
        return $file;
    }
}
