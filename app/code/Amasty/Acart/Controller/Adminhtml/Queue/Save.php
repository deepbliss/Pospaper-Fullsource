<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Queue;

use Magento\Framework\Exception\NoSuchEntityException;

class Save extends \Amasty\Acart\Controller\Adminhtml\Queue
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            $data = $this->getRequest()->getPostValue();

            try {
                $model = $this->_objectManager->create('Amasty\Acart\Model\History');

                $id = $this->getRequest()->getParam('history_id');

                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong queue is specified.'));
                    }
                }

                $model->setData($data);

                $model->save();

                $this->messageManager->addSuccess(__('You saved the queue item.'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('amasty_acart/*/edit', ['id' => $model->getId()]);

                    return;
                }
                $this->_redirect('amasty_acart/*/');

                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('history_id');
                if (!empty($id)) {
                    $this->_redirect('amasty_acart/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('amasty_acart/*/index');
                }

                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the queue data. Please review the error log.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('amasty_acart/*/edit', ['id' => $this->getRequest()->getParam('history_id')]);

                return;
            }
        }
    }
}
