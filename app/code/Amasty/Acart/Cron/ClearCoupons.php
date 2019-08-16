<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Cron;

class ClearCoupons
{
    /**
     * @var \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory
     */
    private $ruleCollectionFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $date;

    public function __construct(
        \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $ruleCollectionFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Stdlib\DateTime $dateTime
    ) {
        $this->ruleCollectionFactory = $ruleCollectionFactory;
        $this->dateTime = $dateTime;
        $this->date = $date;
    }

    public function execute()
    {
        $formattedDate = $this->dateTime->formatDate($this->date->gmtTimestamp());

        /** @var \Magento\SalesRule\Model\ResourceModel\Rule\Collection $collection */
        $collection = $this->ruleCollectionFactory->create();

        $collection->join(
            ['history' => $collection->getTable('amasty_acart_history')],
            'main_table.rule_id = history.sales_rule_id',
            []
        )->addFieldToFilter('to_date', ['lt' => $formattedDate]);

        foreach ($collection->getItems() as $rule) {
            $rule->delete();
        }
    }
}
