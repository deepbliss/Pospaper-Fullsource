<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Rule;

class TestEmail extends \Amasty\Acart\Controller\Adminhtml\Rule
{
    /**
     * @var \Amasty\Acart\Model\RuleFactory
     */
    private $ruleFactory;

    /**
     * @var \Amasty\Acart\Model\QuoteEmailFactory
     */
    private $quoteEmailFactory;

    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    private $quoteFactory;

    /**
     * @var \Amasty\Acart\Model\ResourceModel\History\CollectionFactory
     */
    private $historyCollectionFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Amasty\Acart\Model\RuleQuoteFromRuleAndQuoteFactory
     */
    private $ruleQuoteFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Amasty\Acart\Model\RuleFactory $ruleFactory,
        \Amasty\Acart\Model\QuoteEmailFactory $quoteEmailFactory,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Amasty\Acart\Model\RuleQuoteFromRuleAndQuoteFactory $ruleQuoteFactory,
        \Amasty\Acart\Model\ResourceModel\History\CollectionFactory $historyCollectionFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->ruleFactory = $ruleFactory;
        $this->quoteEmailFactory = $quoteEmailFactory;
        $this->quoteFactory = $quoteFactory;
        $this->ruleQuoteFactory = $ruleQuoteFactory;
        $this->historyCollectionFactory = $historyCollectionFactory;
        $this->logger = $logger;
        parent::__construct($context);
    }

    public function execute()
    {
        $quoteId = $this->getRequest()->getParam('quote_id');
        $ruleId = $this->getRequest()->getParam('rule_id');

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteFactory->create()->load($quoteId);

        if (!$quote->getId()) {
            $quote = $quote->loadByIdWithoutStore($quoteId);
        }

        /** @var \Amasty\Acart\Model\Rule $rule */
        $rule = $this->ruleFactory->create()->load($ruleId);
        /** @var \Amasty\Acart\Model\QuoteEmail $quoteEmail */
        $quoteEmail = $this->quoteEmailFactory->create()->load($quoteId, 'quote_id');

        try {
            if ($quote->getId() && $rule->getId()) {
                if ($quoteEmail->getId()) {
                    $quote->setAcartQuoteEmail($quoteEmail->getCustomerEmail());
                }

                $ruleQuote = $this->ruleQuoteFactory->create($rule, $quote, true);

                if ($ruleQuote->getId()) {
                    $historyItems = $ruleQuote->getData('assigned_history');
                    if (empty($historyItems)) {
                        /** @var \Amasty\Acart\Model\ResourceModel\History\Collection $historyResource */
                        $historyResource = $this->historyCollectionFactory->create();
                        $historyResource->addRuleQuoteData()
                            ->addRuleData()
                            ->addFieldToFilter('main_table.rule_quote_id', $ruleQuote->getId());

                        $historyItems = $historyResource->getItems();
                    }
                    if (empty($historyItems)) {
                        throw new \Magento\Framework\Exception\LocalizedException(__("Email didn't send."));
                    }

                    foreach ($historyItems as $history) {
                        $history->execute(true);
                    }

                    $ruleQuote->complete();
                } else {
                    throw new \Magento\Framework\Exception\LocalizedException(__("Email didn't send."));
                }
            }
        } catch (\Magento\Framework\Exception\InputException $e) {
            $this->getMessageManager()->addErrorMessage($e->getMessage());
            $this->logger->critical($e);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->getMessageManager()->addErrorMessage($e->getMessage());
            $this->logger->critical($e);
        } catch (\Exception $e) {
            $this->getMessageManager()->addErrorMessage($e->getMessage());
            $this->logger->critical($e);
        }

        $messages = $this->getMessageManager()->getMessages(true);
        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);

        return $result->setData(
            [
                'error' => $messages->getCount() > 0,
                'errorMsg' => $messages->getCount() > 0 ? $messages->getLastAddedMessage()->getText() : null
            ]
        );
    }
}
