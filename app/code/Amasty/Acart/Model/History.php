<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

class History extends \Magento\Framework\Model\AbstractModel
{
    const STATUS_PROCESSING = 'processing';

    const STATUS_SENT = 'sent';

    const STATUS_CANCEL_EVENT = 'cancel_event';

    const STATUS_BLACKLIST = 'blacklist';

    const STATUS_ADMIN = 'admin';

    const STATUS_NOT_NEWSLETTER_SUBSCRIBER = 'not_newsletter_subscriber';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $date;

    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    private $_quoteFactory;

    /**
     * @var RuleQuoteFactory
     */
    private $ruleQuoteFactory;

    /**
     * @var \Magento\Framework\Mail\MessageFactory
     */
    private $messageFactory;

    /**
     * @var \Magento\Framework\Mail\TransportInterfaceFactory
     */
    private $mailTransportFactory;

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * @var \Magento\SalesRule\Model\RuleFactory
     */
    private $salesRuleFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection
     */
    private $newsletterSubscriberCollection;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\Mail\TransportInterfaceFactory $mailTransportFactory,
        \Magento\Framework\Mail\MessageFactory $messageFactory,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Amasty\Acart\Model\RuleQuoteFactory $ruleQuoteFactory,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\SalesRule\Model\RuleFactory $salesRuleFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Amasty\Acart\Model\Config $config,
        \Magento\Newsletter\Model\ResourceModel\Subscriber\Collection $newsletterSubscriberCollection,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->dateTime = $dateTime;
        $this->date = $date;
        $this->messageFactory = $messageFactory;
        $this->mailTransportFactory = $mailTransportFactory;
        $this->_quoteFactory = $quoteFactory;
        $this->ruleQuoteFactory = $ruleQuoteFactory;
        $this->stockRegistry = $stockRegistry;
        $this->salesRuleFactory = $salesRuleFactory;
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->newsletterSubscriberCollection = $newsletterSubscriberCollection;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function _construct()
    {
        $this->_init('Amasty\Acart\Model\ResourceModel\History');
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     */
    protected function initDiscountPrices(\Magento\Quote\Model\Quote $quote)
    {
        $this->setSubtotal($quote->getSubtotal());
        $this->setGrandTotal($quote->getGrandTotal());
    }

    /**
     * @param null|int $storeId
     *
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    public function getStore($storeId = null)
    {
        if (!$storeId) {
            $storeId = $this->getStoreId();
        }

        return $this->storeManager->getStore($storeId);
    }

    /**
     * @param bool $testMode
     */
    public function execute($testMode = false)
    {
        if (!$this->_cancel()) {
            $this->setExecutedAt($this->dateTime->formatDate($this->date->gmtTimestamp()))
                ->save();

            if ($testMode) {
                $this->_sendEmail($testMode);
                $status = self::STATUS_SENT;
            } else {
                $blacklist = \Magento\Framework\App\ObjectManager::getInstance()
                    ->create('Amasty\Acart\Model\Blacklist')->load($this->getCustomerEmail(), 'customer_email');

                if ($blacklist->getId()) {
                    $status = self::STATUS_BLACKLIST;
                } elseif (!$this->validateNewsletterSubscribersOnly($this->getCustomerEmail())) {
                    $status = self::STATUS_NOT_NEWSLETTER_SUBSCRIBER;
                } else {
                    $this->_sendEmail($testMode);
                    $status = self::STATUS_SENT;
                }
            }

            $this->setStatus($status);

            $this->setFinishedAt($this->dateTime->formatDate($this->date->gmtTimestamp()))
                ->save();
        } else {
            $this->setStatus(self::STATUS_CANCEL_EVENT)
                ->save();
            $ruleQuote = $this->ruleQuoteFactory->create()->load($this->getRuleQuoteId());
            $ruleQuote->complete();
        }
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    private function validateNewsletterSubscribersOnly($email)
    {
        if (!$this->config->isEmailsToNewsletterSubscribersOnly($this->getStoreId())) {
            return true;
        }

        /** @var \Magento\Newsletter\Model\Subscriber|null $newsletterSubscriber */
        $newsletterSubscriber = $this->newsletterSubscriberCollection->getItemByColumnValue('subscriber_email', $email);

        if ($newsletterSubscriber
            && $newsletterSubscriber->getSubscriberStatus() == \Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item $quoteItem
     *
     * @return bool|\Magento\CatalogInventory\Api\Data\StockItemInterface
     */
    protected function _getStockItem($quoteItem)
    {
        if (!$quoteItem
            || !$quoteItem->getProductId()
            || !$quoteItem->getQuote()
            || $quoteItem->getQuote()->getIsSuperMode()
        ) {
            return false;
        }

        $stockItem = $this->stockRegistry->getStockItem(
            $quoteItem->getProduct()->getId(),
            $quoteItem->getProduct()->getStore()->getWebsiteId()
        );

        return $stockItem;
    }

    /**
     * @return bool
     */
    protected function _cancel()
    {
        $cancel = false;

        if ($this->getCancelCondition()) {
            foreach (explode(',', $this->getCancelCondition()) as $cancelCondition) {
                $quote = $this->_quoteFactory->create()->load($this->getQuoteId());
                $quoteValidation = $this->_validateCancelQuote($quote);

                switch ($cancelCondition) {
                    case \Amasty\Acart\Model\Rule::CANCEL_CONDITION_ALL_PRODUCTS_WENT_OUT_OF_STOCK:
                        if (!$quoteValidation['all_products']) {
                            $cancel = true;
                        }
                        break;
                    case \Amasty\Acart\Model\Rule::CANCEL_CONDITION_ANY_PRODUCT_WENT_OUT_OF_STOCK:
                        if (!$quoteValidation['any_products']) {
                            $cancel = true;
                        }
                        break;
                }
            }
        }

        return $cancel;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     *
     * @return array
     */
    protected function _validateCancelQuote($quote)
    {
        $inStock = 0;

        foreach ($quote->getAllItems() as $item) {
            $stockItem = $this->_getStockItem($item);

            if ($stockItem) {
                if ($stockItem->getIsInStock()) {
                    $inStock++;
                }
            }
        }

        return [
            'all_products' => (($inStock == 0) ? false : true),
            'any_products' => (((count($quote->getAllItems()) - $inStock) != 0) ? false : true)
        ];
    }

    /**
     * @param bool $testMode
     */
    protected function _sendEmail($testMode = false)
    {
        $senderName = $this->config->getSenderName($this->getStoreId());
        $senderEmail = $this->config->getSenderEmail($this->getStoreId());
        $bcc = $this->config->getBcc($this->getStoreId());
        $safeMode = $this->config->isSafeMode($this->getStoreId());
        $recipientEmail = $this->config->getTestingRecipientEmail($this->getStoreId());
        $replyToEmail = $this->config->getReplyToEmail($this->getStoreId());
        $replyToName = $this->config->getReplyToName($this->getStoreId());

        $name = [
            $this->getCustomerFirstname(),
            $this->getCustomerLastname(),
        ];

        $to = $this->getCustomerEmail();

        if (($testMode || $safeMode) && $recipientEmail) {
            $to = $recipientEmail;
        }

        /** @var \Magento\Framework\Mail\Message $message */
        $message = $this->messageFactory->create()
            ->addTo($to, implode(' ', $name))
            ->setFrom($senderEmail, $senderName)
            ->setSubject(html_entity_decode($this->getEmailSubject(), ENT_QUOTES));

        if (method_exists($message, 'setBodyHtml')) {
            $message->setBodyHtml($this->getEmailBody());
        } else {
            $message->setBody($this->getEmailBody())
                ->setMessageType(\Magento\Framework\Mail\MessageInterface::TYPE_HTML);
        }

        if (!empty($bcc) && !$testMode && !$safeMode) {
            $message->addBcc(explode(',', $bcc));
        }

        if ($replyToEmail) {
            $message->setReplyTo($replyToEmail, $replyToName ? : '');
        }

        $mailTransport = $this->mailTransportFactory->create(
            [
                'message' => $message
            ]
        );
        $mailTransport->sendMessage();
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        return $this->ruleQuoteFactory->create()->loadById($this->getRuleQuoteId())->getRule();
    }
}
