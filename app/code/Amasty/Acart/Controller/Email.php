<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller;

use Magento\Framework\App\Action\Context;

abstract class Email extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Amasty\Acart\Model\UrlManager
     */
    protected $urlManager;

    /**
     * @var \Amasty\Acart\Model\RuleQuote
     */
    protected $ruleQuote;

    /**
     * @var \Amasty\Acart\Model\ResourceModel\RuleQuote
     */
    protected $ruleQuoteResource;

    /**
     * @var \Amasty\Acart\Model\ResourceModel\History\CollectionFactory
     */
    protected $historyCollectionFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    protected $quoteFactory;

    /**
     * @var \Magento\Checkout\Model\SessionFactory
     */
    protected $checkoutSessionFactory;

    public function __construct(
        Context $context,
        \Amasty\Acart\Model\UrlManager $urlManager,
        \Amasty\Acart\Model\RuleQuote $ruleQuote,
        \Amasty\Acart\Model\ResourceModel\RuleQuote $ruleQuoteResource,
        \Amasty\Acart\Model\ResourceModel\History\CollectionFactory $historyCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\SessionFactory $checkoutSessionFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Quote\Model\QuoteFactory $quoteFactory
    ) {
        parent::__construct($context);
        $this->urlManager = $urlManager;
        $this->ruleQuote = $ruleQuote;
        $this->ruleQuoteResource = $ruleQuoteResource;
        $this->historyCollectionFactory = $historyCollectionFactory;
        $this->customerSession = $customerSession;
        $this->customerFactory = $customerFactory;
        $this->quoteFactory = $quoteFactory;
        $this->checkoutSessionFactory = $checkoutSessionFactory;
    }
}
