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
 * Action Edit
 */
class Edit extends \Magestore\Megamenu\Controller\Adminhtml\Megamenu
{
    /**
     * Execute action
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('megamenu_id');
        $model = $this->_objectManager->create('Magestore\Megamenu\Model\Megamenu');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_objectManager->get('Magento\Framework\Registry')->register('megamenu_model', $model);

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        if($id){
            $resultPage->getConfig()->getTitle()->prepend(__('Edit Mega Menu Item "%1"', $model->getNameMenu()));
        }else {
            $resultPage->getConfig()->getTitle()->prepend(__('New Mega Menu Item'));
        }
        $resultPage->setActiveMenu('Magestore_Megamenu::magestoremegamenu');
        return $resultPage;
    }
}
