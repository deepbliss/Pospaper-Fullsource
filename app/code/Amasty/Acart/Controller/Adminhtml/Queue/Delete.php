<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Queue;

class Delete extends \Amasty\Acart\Controller\Adminhtml\Queue
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
                $model = $this->_objectManager->create('Amasty\Acart\Model\History');
                $model->load($id);
                $model->setStatus(\Amasty\Acart\Model\History::STATUS_ADMIN);
                $model->save();
                $this->messageManager->addSuccess(__('You deleted the queue.'));
                $this->_redirect('amasty_acart/*/');

                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete the queue right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('amasty_acart/*/edit', ['id' => $this->getRequest()->getParam('id')]);

                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a queue to delete.'));
        $this->_redirect('amasty_acart/*/');
    }
}
