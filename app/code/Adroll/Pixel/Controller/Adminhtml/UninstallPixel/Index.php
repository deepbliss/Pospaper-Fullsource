<?php

namespace Adroll\Pixel\Controller\Adminhtml\UninstallPixel;

use Adroll\Pixel\Helper\Config as ConfigHelper;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Webapi\Exception as WebapiException;

class Index extends Action
{
    protected $_publicActions = ['index'];

    public function __construct(Context $context, ConfigHelper $configHelper)
    {
        $this->_context = $context;
        $this->_configHelper = $configHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        $storeGroupId = $this->getRequest()->getPostValue('store_group_id');

        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);

        if ($storeGroupId === null) {
            $result->setHttpResponseCode(WebapiException::HTTP_BAD_REQUEST);
        } else {
            $this->_configHelper->uninstallPixel($storeGroupId);
            $result->setHttpResponseCode(204); // HTTP NO CONTENT
        };

        return $result;
    }
}
