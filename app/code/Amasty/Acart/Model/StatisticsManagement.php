<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

use Amasty\Acart\Controller\Adminhtml\Reports\Ajax;
use Amasty\Acart\Model\ResourceModel\RuleQuote\CollectionFactory as RuleQuoteCollectionFactory;
use Magento\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;
use Amasty\Acart\Model\ResourceModel\History\CollectionFactory as HistoryCollectionFactory;

class StatisticsManagement
{
    /**#@+*/
    const SUM_GRAND_TOTAL = 'SUM(base_grand_total)';
    const COUNT_OF_ORDERS = 'count(order.entity_id)';
    /**#@-*/

    /**
     * RuleQuoteCollectionFactory
     */
    private $ruleQuoteCollectionFactory;

    /**
     * QuoteCollectionFactory
     */
    private $quoteCollectionFactory;

    /**
     * @var HistoryCollectionFactory
     */
    private $historyCollectionFactory;

    public function __construct(
        RuleQuoteCollectionFactory $ruleQuoteCollection,
        QuoteCollectionFactory $quoteCollection,
        HistoryCollectionFactory $historyCollectionFactory
    ) {
        $this->ruleQuoteCollectionFactory = $ruleQuoteCollection;
        $this->quoteCollectionFactory = $quoteCollection;
        $this->historyCollectionFactory = $historyCollectionFactory;
    }

    /**
     * @return \Amasty\Acart\Model\ResourceModel\RuleQuote\Collection
     */
    private function createRuleQuoteCollection()
    {
        return $this->ruleQuoteCollectionFactory->create();
    }

    /**
     * @param array $storeIds
     * @param string $dateTo
     * @param string $dateFrom
     *
     * @return float|int
     */
    public function getAbandonmentRate($storeIds, $dateTo, $dateFrom)
    {
        $result = 0;

        /** @var \Amasty\Acart\Model\ResourceModel\RuleQuote\Collection $ruleQuoteCollection */
        $ruleQuoteCollection = $this->createRuleQuoteCollection();
        /** @var \Magento\Quote\Model\ResourceModel\Quote\Collection $quoteCollection */
        $quoteCollection = $this->quoteCollectionFactory->create();

        if ($dateTo && $dateFrom) {
            $quoteCollection->addFieldToFilter('created_at', ['lteq' => $dateTo])
                ->addFieldToFilter('created_at', ['gteq' => $dateFrom]);
        }

        $quoteCollection->addFieldToFilter('store_id', ['in' => $storeIds])
            ->addFieldToFilter('is_active', 0);

        $abandonedQuoteQty = $ruleQuoteCollection
            ->addFilterByAbandonedStatus(\Amasty\Acart\Model\RuleQuote::ABANDONED_NOT_RESTORED_STATUS)
            ->addFilterByStoreIds($storeIds)
            ->addFilterByDate($dateTo, $dateFrom)
            ->getSize();

        $ordersQty = $quoteCollection->getSize();

        $totalQty = $abandonedQuoteQty + $ordersQty;

        if ($totalQty) {
            $result = $abandonedQuoteQty / $totalQty * Ajax::PERCENT;
            $result = round($result);
        }

        return $result;
    }

    /**
     * @param array $storeIds
     * @param string $dateTo
     * @param string $dateFrom
     *
     * @return int
     */
    public function getTotalSend($storeIds, $dateTo, $dateFrom)
    {
        /** @var \Amasty\Acart\Model\ResourceModel\History\Collection $historyCollection */
        $historyCollection = $this->historyCollectionFactory->create();

        $sentEmails = $historyCollection
            ->addRuleQuoteData()
            ->addFilterByStoreIds($storeIds)
            ->addFilterByDate($dateTo, $dateFrom)
            ->addFilterByStatus(History::STATUS_SENT)
            ->getSize();

        return $sentEmails;
    }

    /**
     * @param array $storeIds
     * @param string $dateTo
     * @param string $dateFrom
     *
     * @return int
     */
    public function getTotalRestoredCarts($storeIds, $dateTo, $dateFrom)
    {
        /** @var \Amasty\Acart\Model\ResourceModel\RuleQuote\Collection $ruleQuoteCollection */
        $ruleQuoteCollection = $this->createRuleQuoteCollection();

        $restoredQuoteQty = $ruleQuoteCollection
            ->addFilterByAbandonedStatus(\Amasty\Acart\Model\RuleQuote::ABANDONED_RESTORED_STATUS)
            ->addFilterByStoreIds($storeIds)
            ->addFilterByDate($dateTo, $dateFrom)
            ->getSize();

        return $restoredQuoteQty;
    }

    /**
     * @param array $storeIds
     * @param string $dateTo
     * @param string $dateFrom
     *
     * @return int
     */
    public function getTotalAbandonedMoney($storeIds, $dateTo, $dateFrom)
    {
        /** @var \Amasty\Acart\Model\ResourceModel\RuleQuote\Collection $ruleQuoteCollection */
        $ruleQuoteCollection = $this->createRuleQuoteCollection();

        $result = $ruleQuoteCollection->getTotalAbandonedMoney($storeIds, $dateTo, $dateFrom);

        return round($result);
    }

    /**
     * @param array $storeIds
     * @param string $dateTo
     * @param string $dateFrom
     *
     * @return int
     */
    public function getAbandonmentRevenue($storeIds, $dateTo, $dateFrom)
    {
        /** @var \Amasty\Acart\Model\ResourceModel\RuleQuote\Collection $ruleQuoteCollection */
        $ruleQuoteCollection = $this->createRuleQuoteCollection();

        $result = $ruleQuoteCollection->getRestoredOrdersValue($storeIds, $dateTo, $dateFrom, self::SUM_GRAND_TOTAL);

        return round($result);
    }

    /**
     * @param array $storeIds
     * @param string $dateTo
     * @param string $dateFrom
     *
     * @return int
     */
    public function getOrdersViaRestoredCarts($storeIds, $dateTo, $dateFrom)
    {
        /** @var \Amasty\Acart\Model\ResourceModel\RuleQuote\Collection $ruleQuoteCollection */
        $ruleQuoteCollection = $this->createRuleQuoteCollection();

        return $ruleQuoteCollection->getRestoredOrdersValue($storeIds, $dateTo, $dateFrom, self::COUNT_OF_ORDERS);
    }
}
