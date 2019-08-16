<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

use Magento\Framework\Stdlib;

class RuleQuoteFromRuleAndQuoteFactory
{
    /**
     * @var Stdlib\DateTime\DateTime
     */
    private $date;

    /**
     * @var Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var RuleQuoteFactory
     */
    private $ruleQuoteFactory;

    /**
     * @var HistoryFromRuleQuoteFactory
     */
    private $historyFromRuleQuoteFactory;

    public function __construct(
        Stdlib\DateTime\DateTime $date,
        Stdlib\DateTime $dateTime,
        RuleQuoteFactory $ruleQuoteFactory,
        \Amasty\Acart\Model\HistoryFromRuleQuoteFactory $historyFromRuleQuoteFactory
    ) {
        $this->date = $date;
        $this->dateTime = $dateTime;
        $this->ruleQuoteFactory = $ruleQuoteFactory;
        $this->historyFromRuleQuoteFactory = $historyFromRuleQuoteFactory;
    }

    /**
     * @param Rule $rule
     * @param \Magento\Quote\Model\Quote $quote
     * @param bool $testMode
     *
     * @return RuleQuote
     */
    public function create(
        \Amasty\Acart\Model\Rule $rule,
        \Magento\Quote\Model\Quote $quote,
        $testMode = false
    ) {
        $customerEmail = $quote->getCustomerEmail() ? $quote->getCustomerEmail() : $quote->getAcartQuoteEmail();

        /** @var RuleQuote $ruleQuote */
        $ruleQuote = $this->ruleQuoteFactory->create();

        if (!empty($customerEmail)) {
            $time = $this->date->gmtTimestamp();

            $ruleQuote->setData(
                [
                    'rule_id' => $rule->getId(),
                    'quote_id' => $quote->getId(),
                    'store_id' => $quote->getStoreId(),
                    'status' => RuleQuote::STATUS_PROCESSING,
                    'customer_id' => $quote->getCustomerId(),
                    'customer_email' => $customerEmail,
                    'customer_firstname' => $quote->getCustomerFirstname(),
                    'customer_lastname' => $quote->getCustomerLastname(),
                    'test_mode' => $testMode,
                    'created_at' => $this->dateTime->formatDate($time)
                ]
            );

            $ruleQuote->save();
            $histories = [];
            $shedulers = $rule->getScheduleCollection()->getItems();
            if (empty($shedulers)) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Rule do not have any Schedule"));
            }

            foreach ($shedulers as $schedule) {
                $histories[] = $this->historyFromRuleQuoteFactory->create($ruleQuote, $schedule, $rule, $quote, $time);
            }
            $ruleQuote->setData('assigned_history', $histories);
        }

        return $ruleQuote;
    }
}
