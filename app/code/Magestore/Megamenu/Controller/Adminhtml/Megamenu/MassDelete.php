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
 * Action MassDelete
 */
class MassDelete extends \Magestore\Megamenu\Controller\Adminhtml\Megamenu
{
    /**
     * Execute action
     */
    public function execute()
    {
        $entityIds = $this->getRequest()->getParam('megamenus');
        if (!is_array($entityIds) || empty($entityIds)) {
            $this->messageManager->addError(__('Please select record(s).'));
        } else {
            /** @var \Magestore\Megamenu\Model\ResourceModel\Megamenu\Collection $collection */
            $collection = $this->_objectManager->create('Magestore\Megamenu\Model\ResourceModel\Megamenu\Collection');
            $collection->addFieldToFilter('megamenu_id', ['in' => $entityIds]);
            try {
                foreach ($collection as $item) {
                    $item->deleteItem()->delete();
                }
                $this->_typeListInterface->cleanType('block_html');
                $this->_typeListInterface->cleanType('full_page');
                $this->messageManager->addSuccess(
                    __('A total of %1 item(s) have been deleted.', count($entityIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}
