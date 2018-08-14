<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds;

/**
 * Delete action
 */
class Delete extends \Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds
{

    /**
     * Execute action
     * @return void
     */
    public function execute()
    {

        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->_objectManager->create('Wyomind\SimpleGoogleShopping\Model\Feeds');
                $model->setId($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The data feed has been deleted.'));
                return $this->resultRedirectFactory->create()->setPath('simplegoogleshopping/feeds/index');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $this->resultRedirectFactory->create()->setPath('simplegoogleshopping/feeds/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__("We can't find a data feed to delete."));
        return $this->resultRedirectFactory->create()->setPath('simplegoogleshopping/feeds/index');
    }
}
