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
 * Action Save
 */
class Save extends \Magestore\Megamenu\Controller\Adminhtml\Megamenu
{
    /**
     * Execute action
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if ($data = $this->getRequest()->getPostValue()) {
            /**
             * @var \Magestore\Megamenu\Model\Megamenu $model
             */
            $model = $this->_objectManager->create('Magestore\Megamenu\Model\Megamenu');

            if ($id = $this->getRequest()->getParam('megamenu_id')) {
                $model->load($id);
            }
            if (isset($data['stores']) && is_array($data['stores'])) {
                $data['stores'] = implode(',', $data['stores']);
            }

            $model->setData($data);

            try {
                $this->_imageHelper->mediaUploadImage(
                    $model,
                    'item_icon',
                    \Magestore\Megamenu\Model\Megamenu::MEGAMENU_ICON_PATH,
                    $makeResize = true
                );
                $this->_typeListInterface->cleanType('config');
                $this->_typeListInterface->cleanType('full_page');
                $model->save()->saveItem()->setConfigIds();
                $this->_typeListInterface->cleanType('config');
                $this->_typeListInterface->cleanType('full_page');

                $this->messageManager->addSuccess(__('The item has been saved.'));
                $this->_getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back') === 'edit') {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        [
                            'megamenu_id' => $model->getId(),
                            '_current' => true,
                        ]
                    );
                } elseif ($this->getRequest()->getParam('back') === 'new') {
                    return $resultRedirect->setPath(
                        '*/*/new',
                        ['_current' => true]
                    );
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the record.'));
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath(
                '*/*/edit',
                ['megamenu_id' => $this->getRequest()->getParam('megamenu_id')]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }
}
