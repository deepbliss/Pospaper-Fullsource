<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Megamenu
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Megamenu\Controller\Adminhtml\Megamenu;

use Magento\Framework\Controller\ResultFactory;

/**
 * Action Delete
 */
class Delete extends \Magestore\Megamenu\Controller\Adminhtml\Megamenu
{
    /**
     * Execute action
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('megamenu_id');
        try {
            /** @var \{{model_name}} $model */
            $model = $this->_objectManager->create('Magestore\Megamenu\Model\Megamenu')->setId($id);
            $model->deleteItem()->delete();
            $this->messageManager->addSuccess(
                __('Delete successfully !')
            );
            $this->_typeListInterface->cleanType('block_html');
            $this->_typeListInterface->cleanType('full_page');
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}
