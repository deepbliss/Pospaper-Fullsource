<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds;

/**
 * Edit action
 */
class Edit extends \Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds
{

    /**
     * Execute action
     * @return void
     */
    public function execute()
    {

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu("Magento_Catalog::catalog");
        $resultPage->addBreadcrumb(__('Simple Google Shopping'), __('Simple Google Shopping'));
        $resultPage->addBreadcrumb(__('Manage Data Feeds'), __('Manage Data Feeds'));

        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Wyomind\SimpleGoogleShopping\Model\Feeds');

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addError(__('This data feed no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('simplegoogleshopping/feeds/index');
            }
        }

        $resultPage->getConfig()->getTitle()->prepend($model->getSimplegoogleshoppingId() ? (__('Modify data feed : ') . $model->getSimplegoogleshoppingFilename()) : __('New data feed'));

        $this->coreRegistry->register('data_feed', $model);

        return $resultPage;
    }
}
