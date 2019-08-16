<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */

/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Amasty\Acart\Controller\Adminhtml\Blacklist;

class Delete extends \Amasty\Acart\Controller\Adminhtml\Blacklist
{
    /**
     * Delete promo quote action
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->_objectManager->create('Amasty\Acart\Model\Blacklist');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the blacklist.'));
                $this->_redirect('amasty_acart/*/');

                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete the blacklist right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('amasty_acart/*/edit', ['id' => $this->getRequest()->getParam('id')]);

                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a blacklist to delete.'));
        $this->_redirect('amasty_acart/*/');
    }
}
