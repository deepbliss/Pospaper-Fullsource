<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

class CouponForHistoryFactory
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $date;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var \Magento\SalesRule\Model\RuleFactory
     */
    private $salesRuleFactory;

    /**
     * @var \Magento\SalesRule\Model\Coupon\Massgenerator
     */
    private $couponGenerator;

    /**
     * @var \Magento\SalesRule\Model\ResourceModel\Coupon\CollectionFactory
     */
    private $couponCollectionFactory;

    /**
     * @var \Amasty\Base\Model\Serializer
     */
    private $serializer;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    private $groupCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection|null
     */
    private $groupCollection = null;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\SalesRule\Model\RuleFactory $salesRuleFactory,
        \Magento\SalesRule\Model\Coupon\Massgenerator\Proxy $couponMassgenerator,
        \Magento\SalesRule\Model\ResourceModel\Coupon\CollectionFactory $couponCollectionFactory,
        \Amasty\Base\Model\Serializer $serializer,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->date = $date;
        $this->dateTime = $dateTime;
        $this->salesRuleFactory = $salesRuleFactory;
        $this->couponGenerator = $couponMassgenerator;
        $this->couponCollectionFactory = $couponCollectionFactory;
        $this->serializer = $serializer;
        $this->groupCollectionFactory = $groupCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @param RuleQuote $ruleQuote
     * @param Schedule $schedule
     * @param Rule $rule
     *
     * @return SalesRule
     */
    public function create(
        \Amasty\Acart\Model\RuleQuote $ruleQuote,
        \Amasty\Acart\Model\Schedule $schedule,
        \Amasty\Acart\Model\Rule $rule
    ) {
        $store = $this->getStore($ruleQuote->getStoreId());
        /** @var \Magento\SalesRule\Model\Rule $salesRule */
        $salesRule = $this->salesRuleFactory->create();
        $salesRule->setData(
            [
                'name' => 'Amasty: Abandoned Cart Coupon #' . $ruleQuote->getCustomerEmail(),
                'is_active' => '1',
                'website_ids' => [
                    0 => $store->getWebsiteId()
                ],
                'customer_group_ids' => $this->getGroupsIds($rule),
                'coupon_code' => strtoupper(uniqid()),
                'uses_per_coupon' => 1,
                'coupon_type' => 2,
                'from_date' => '',
                'to_date' => $this->getCouponToDate($schedule->getExpiredInDays(), $schedule->getDeliveryTime()),
                'uses_per_customer' => 1,
                'simple_action' => $schedule->getSimpleAction(),
                'discount_amount' => $schedule->getDiscountAmount(),
                'stop_rules_processing' => '0',
            ]
        );

        if ($schedule->getDiscountQty() > 0) {
            $salesRule->setDiscountQty($schedule->getDiscountQty());
        }

        if ($schedule->getDiscountStep() > 0) {
            $salesRule->setDiscountStep($schedule->getDiscountStep());
        }

        $salesRule->setConditionsSerialized($this->serializer->serialize($this->getConditions($rule)));
        $salesRule->save();

        return $salesRule;
    }

    /**
     * @param null|int $storeId
     *
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    public function getStore($storeId = null)
    {
        return $this->storeManager->getStore($storeId);
    }

    /**
     * @param Rule $rule
     *
     * @return array
     */
    private function getGroupsIds(\Amasty\Acart\Model\Rule $rule)
    {
        $groupsIds = [];
        $strGroupIds = $rule->getCustomerGroupIds();

        if (!empty($strGroupIds)) {
            $groupsIds = explode(',', $strGroupIds);
        } else {
            foreach ($this->getGroupCollection()->getData() as $group) {
                $groupsIds[] = $group['customer_group_id'];
            }
        }

        return $groupsIds;
    }

    /**
     * @return \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    private function getGroupCollection()
    {
        if ($this->groupCollection === null) {
            $this->groupCollection = $this->groupCollectionFactory->create();
        }

        return $this->groupCollection;
    }

    /**
     * @param Rule $rule
     *
     * @return array
     */
    private function getConditions(\Amasty\Acart\Model\Rule $rule)
    {
        $salesRuleConditions = [];
        $conditions = $rule->getSalesRule()->getConditions()->asArray();

        if (isset($conditions['conditions'])) {
            foreach ($conditions['conditions'] as $idx => $condition) {
                if ($condition['attribute'] !== \Amasty\Acart\Model\SalesRule\Condition\Carts::ATTRIBUTE_CARDS_NUM) {
                    $salesRuleConditions[] = $condition;
                }
            }
        }

        return [
            'type' => 'Magento\SalesRule\Model\Rule\Condition\Combine',
            'attribute' => '',
            'operator' => '',
            'value' => '1',
            'is_value_processed' => '',
            'aggregator' => 'all',
            'conditions' => $salesRuleConditions
        ];
    }

    /**
     * @param \Magento\SalesRule\Model\Rule $rule
     *
     * @return \Magento\SalesRule\Model\Coupon|null
     */
    public function generateCouponPool(\Magento\SalesRule\Model\Rule $rule)
    {
        $salesCoupon = null;

        $this->couponGenerator->setData(
            [
                'rule_id' => $rule->getId(),
                'qty' => 1,
                'length' => 12,
                'format' => 'alphanum',
                'prefix' => '',
                'suffix' => '',
                'dash' => '0',
                'uses_per_coupon' => '0',
                'uses_per_customer' => '0',
                'to_date' => '',
            ]
        );

        $this->couponGenerator->generatePool();

        /** @var \Magento\SalesRule\Model\ResourceModel\Coupon\Collection $resourceCoupon */
        $resourceCoupon = $this->couponCollectionFactory->create();

        $resourceCoupon->addFieldToFilter('main_table.rule_id', $rule->getId())
            ->getSelect()
            ->joinLeft(
                ['h' => $resourceCoupon->getTable('amasty_acart_history')],
                'main_table.coupon_id = h.sales_rule_coupon_id',
                []
            )->where('h.history_id is null')
            ->order('main_table.coupon_id desc')
            ->limit(1);

        $items = $resourceCoupon->getItems();
        if (!empty($items)) {
            $salesCoupon = end($items);
        }

        return $salesCoupon;
    }

    /**
     * @param int $days
     * @param int $deliveryTime
     *
     * @return null|string
     */
    private function getCouponToDate($days, $deliveryTime)
    {
        return $this->dateTime->formatDate($this->date->gmtTimestamp() + $days * 24 * 3600 + $deliveryTime);
    }
}
