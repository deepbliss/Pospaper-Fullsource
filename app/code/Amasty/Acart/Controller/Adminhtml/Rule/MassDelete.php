<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Psr\Log\LoggerInterface;

class MassDelete extends \Amasty\Acart\Controller\Adminhtml\Rule
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    private $filter;

    /**
     * @var \Amasty\Acart\Model\ResourceModel\Rule\CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        Action\Context $context,
        LoggerInterface $logger,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Amasty\Acart\Model\ResourceModel\Rule\CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->logger = $logger;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Execute action
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());

            foreach ($collection as $rule) {
                $rule->delete();
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while delete rule. Please review the error log.')
            );
            $this->logger->critical($e);
        }

        $this->_redirect('amasty_acart/*/index');
    }
}
