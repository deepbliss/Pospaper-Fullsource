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

namespace Amasty\Acart\Controller\Adminhtml\Queue;

use Magento\Backend\App\Action;
use Psr\Log\LoggerInterface;

class MassCancel extends \Amasty\Acart\Controller\Adminhtml\Queue
{
    protected $filter;

    protected $collectionFactory;


    public function __construct(
        Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Translate\InlineInterface $translateInline,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        LoggerInterface $logger,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Amasty\Acart\Model\ResourceModel\History\CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;

        parent::__construct(
            $context,
            $coreRegistry,
            $fileFactory,
            $translateInline,
            $resultPageFactory,
            $resultJsonFactory,
            $resultLayoutFactory,
            $resultRawFactory,
            $resultForwardFactory,
            $logger
        );
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());

            foreach ($collection as $history) {
                $history->setStatus(\Amasty\Acart\Model\History::STATUS_ADMIN);
                $history->save();
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(
                __('Something went wrong while export feed data. Please review the error log.')
            );
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
        }

        $this->_redirect('amasty_acart/queue/index');
    }
}
