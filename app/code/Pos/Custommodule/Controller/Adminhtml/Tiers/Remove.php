<?php

namespace Pos\Custommodule\Controller\Adminhtml\Tiers;

use Magento\Framework\Controller\ResultFactory;

class Remove extends \Pos\Custommodule\Controller\Adminhtml\Index
{
    public function execute()
    {
        try {
            $sql = "TRUNCATE {$this->_resource->getTableName('catalog_product_entity_tier_price')}";
            $this->_resource->getConnection()->query($sql);
            $this->messageManager->addSuccess(__('Pricing Tiers have been removed.'));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError(__('Something went wrong, please check log files.'));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRedirectUrl());
        return $resultRedirect;
    }
}
