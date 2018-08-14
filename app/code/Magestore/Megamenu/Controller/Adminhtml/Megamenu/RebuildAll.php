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


/**
 * Action ExportCsv
 */
class RebuildAll  extends \Magestore\Megamenu\Controller\Adminhtml\Megamenu
{
    /**
     * Execute action
     */
    public function execute()
    {
        $collection = $this->_collectionFactory->create();
        try {
            $this->_configResoure->saveConfig('megamenu/general/ids','0','default', 0);
            $this->_typeListInterface->cleanType('config');
            $this->_typeListInterface->cleanType('full_page');
            foreach ($collection as $item) {
                $item->saveItem()->setConfigIds();
            }
            $this->_configResoure->saveConfig('megamenu/general/ids',implode(',',$collection->getAllids()),'default', 0);
            $this->_typeListInterface->cleanType('config');
            $this->_typeListInterface->cleanType('full_page');
            $this->messageManager->addSuccess(__('The Mega Menu Cache has been refreshed.'));

        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/index');
    }
}
