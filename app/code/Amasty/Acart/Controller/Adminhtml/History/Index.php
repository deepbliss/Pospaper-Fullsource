<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\History;

use Magento\Framework\Exception\LocalizedException;

class Index extends \Amasty\Acart\Controller\Adminhtml\History
{

    /**
     * @var \Amasty\Acart\Model\Indexer
     */
    private $indexer;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Translate\InlineInterface $translateInline,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Psr\Log\LoggerInterface $logger,
        \Amasty\Acart\Model\Indexer $indexer
    ) {
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
        $this->indexer = $indexer;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     * |\Magento\Framework\Controller\ResultInterface
     * |\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        try {
            $this->indexer->run();
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Error. Please see the log for more information.')
            );
            $this->logger->critical($e->getMessage());
        }

        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()->prepend(__('History'));

        return $resultPage;
    }
}
