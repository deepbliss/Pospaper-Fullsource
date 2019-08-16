<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Blacklist;

use Magento\Backend\App\Action;
use Psr\Log\LoggerInterface;

class MassDelete extends \Amasty\Acart\Controller\Adminhtml\Blacklist
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
        \Amasty\Acart\Model\ResourceModel\Blacklist\CollectionFactory $collectionFactory
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

            foreach ($collection as $item) {
                $item->delete();
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(
                __('Something went wrong while export feed data. Please review the error log.')
            );
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
        }

        $this->_redirect('amasty_acart/*/index');
    }
}
