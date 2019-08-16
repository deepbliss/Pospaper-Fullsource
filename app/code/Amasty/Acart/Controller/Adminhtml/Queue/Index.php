<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Queue;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Index extends \Amasty\Acart\Controller\Adminhtml\Queue
{
    const CRON_FAQ_LINK = 'https://amasty.com/knowledge-base/topic-magento-related-questions.html'
        . '?utm_source=extension&utm_medium=link&utm_campaign=abandoned-cart-m2-emails-queue-cron-faq#97';

    /**
     * @var \Amasty\Acart\Model\Indexer
     */
    private $indexer;

    /**
     * @var \Magento\Framework\Message\Factory
     */
    private $messageFactory;

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
        \Amasty\Acart\Model\Indexer $indexer,
        \Magento\Framework\Message\Factory $messageFactory
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
        $this->messageFactory = $messageFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        try {
            $message = __('If there are no emails in the queue for a long time, please make sure that cron is '
            . 'properly configured for your Magento. Please find more information '
                . '<a class="new-page-url" href=\'%1\' target=\'_blank\'>here</a>.', self::CRON_FAQ_LINK);

            $this->messageManager->addMessage(
                $this->messageFactory->create(\Magento\Framework\Message\MessageInterface::TYPE_WARNING, $message)
            );

            $this->indexer->run();
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Error. Please see the log for more information.')
            );
            $this->logger->critical($e->__toString());
        }

        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()->prepend(__('Queue'));

        return $resultPage;
    }
}
