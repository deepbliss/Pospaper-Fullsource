<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

use Magento\Framework\Stdlib;
use Magento\Framework\App\Area;

class HistoryFromRuleQuoteFactory
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
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var CouponForHistoryFactory
     */
    private $couponForHistoryFactory;

    /**
     * @var HistoryFactory
     */
    private $historyFactory;

    /**
     * @var UrlManager
     */
    private $urlManager;

    /**
     * @var FormatManager
     */
    private $formatManager;

    /**
     * @var \Magento\Framework\Mail\Template\FactoryInterface
     */
    private $templateFactory;

    /**
     * @var \Magento\SalesRule\Model\RuleFactory
     */
    private $salesRuleFactory;

    /**
     * @var array
     */
    private $sameCouponData = [];

    public function __construct(
        Stdlib\DateTime\DateTime $date,
        Stdlib\DateTime $dateTime,
        ConfigProvider $configProvider,
        CouponForHistoryFactory $couponForHistoryFactory,
        HistoryFactory $historyFactory,
        \Amasty\Acart\Model\UrlManager $urlManager,
        \Amasty\Acart\Model\FormatManager $formatManager,
        \Magento\SalesRule\Model\RuleFactory $salesRuleFactory,
        \Magento\Framework\Mail\Template\FactoryInterface $templateFactory
    ) {
        $this->date = $date;
        $this->dateTime = $dateTime;
        $this->configProvider = $configProvider;
        $this->couponForHistoryFactory = $couponForHistoryFactory;
        $this->historyFactory = $historyFactory;
        $this->urlManager = $urlManager;
        $this->formatManager = $formatManager;
        $this->templateFactory = $templateFactory;
        $this->salesRuleFactory = $salesRuleFactory;
    }

    /**
     * @param RuleQuote $ruleQuote
     * @param Schedule $schedule
     * @param Rule $rule
     * @param \Magento\Quote\Model\Quote $quote
     * @param $time
     *
     * @return History
     */
    public function create(
        RuleQuote $ruleQuote,
        Schedule $schedule,
        Rule $rule,
        \Magento\Quote\Model\Quote $quote,
        $time
    ) {
        $couponData = $this->getCouponData($schedule, $rule, $ruleQuote);

        if ($this->configProvider->isDebugMode()) {
            $deliveryTime = 10;
        } else {
            $deliveryTime = $schedule->getDeliveryTime() ? : (5 * 50);
        }

        /** @var History $history */
        $history = $this->historyFactory->create();

        $history->setData(
            array_merge(
                [
                    'rule_quote_id' => $ruleQuote->getId(),
                    'schedule_id' => $schedule->getId(),
                    'status' => History::STATUS_PROCESSING,
                    'public_key' => uniqid(),
                    'scheduled_at' => $this->dateTime->formatDate($time + $deliveryTime),
                ],
                $couponData
            )
        );

        $template = $this->createEmailTemplate($ruleQuote, $schedule, $rule, clone $quote, $history);

        $history->save();
        $history->addData(
            [
                'email_body' => $template->processTemplate(),
                'email_subject' => html_entity_decode($template->getSubject(), ENT_QUOTES),
            ]
        );

        $history->save();

        return $history;
    }

    /**
     * @param Schedule $schedule
     * @param Rule $rule
     * @param RuleQuote $ruleQuote
     *
     * @return array
     */
    private function getCouponData(
        Schedule $schedule,
        Rule $rule,
        RuleQuote $ruleQuote
    ) {
        $couponData = [];
        $salesCoupon = false;
        $salesRule = false;

        if ($schedule->getSendSameCoupon()) {
            $couponData = $this->sameCouponData;
        } else {
            if ($schedule->getUseShoppingCartRule()) {
                /** @var \Magento\SalesRule\Model\Rule $salesRule */
                $salesRule = $this->salesRuleFactory->create()->load($schedule->getSalesRuleId());
                if ($salesRule->getId()) {
                    $salesCoupon = $this->couponForHistoryFactory->generateCouponPool($salesRule);
                }
            } elseif ($schedule->getSimpleAction()) {
                $salesRule = $this->couponForHistoryFactory->create($ruleQuote, $schedule, $rule);
            }

            if ($salesRule) {
                if ($salesCoupon) {
                    $couponData = [
                        'sales_rule_id' => $salesRule->getId(),
                        'sales_rule_coupon_id' => $salesCoupon->getId(),
                        'sales_rule_coupon' => $salesCoupon->getCode(),
                        'sales_rule_coupon_expiration_date' => $salesCoupon->getExpirationDate(),
                    ];
                } else {
                    $couponData = [
                        'sales_rule_id' => $salesRule->getId(),
                        'sales_rule_coupon_id' => null,
                        'sales_rule_coupon' => $salesRule->getCouponCode(),
                        'sales_rule_coupon_expiration_date' => $salesRule->getToDate(),
                    ];
                }
                $this->sameCouponData = $couponData;
            }
        }

        return $couponData;
    }

    /**
     * @param RuleQuote $ruleQuote
     * @param Schedule $schedule
     * @param Rule $rule
     * @param \Magento\Quote\Model\Quote $quote
     * @param History $history
     *
     * @return \Magento\Framework\Mail\TemplateInterface
     */
    private function createEmailTemplate(
        RuleQuote $ruleQuote,
        Schedule $schedule,
        Rule $rule,
        \Magento\Quote\Model\Quote $quote,
        History $history
    ) {
        $vars = [
            'quote' => $quote,
            'rule' => $rule,
            'ruleQuote' => $ruleQuote,
            'history' => $history,
            'urlmanager' => $this->urlManager->init($rule, $history),
            'formatmanager' => $this->formatManager->init(
                [
                    FormatManager::TYPE_HISTORY => $history,
                    FormatManager::TYPE_QUOTE => $quote,
                    FormatManager::TYPE_RULE_QUOTE => $ruleQuote
                ]
            ),
        ];

        if ($quote->getCustomerIsGuest()) {
            $vars['customerIsGuest'] = true;
        }

        if ($history->getSalesRuleCoupon()) {
            $quote->setCouponCode($history->getSalesRuleCoupon());
        }
        $quote->collectTotals();

        $discount = $quote->getSubtotal() - $quote->getSubtotalWithDiscount();
        $quote->setData('discount', $discount);

        $template = $this->templateFactory->get($schedule->getTemplateId())
            ->setVars($vars)
            ->setOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => $ruleQuote->getStoreId()
                ]
            );

        return $template;
    }
}
