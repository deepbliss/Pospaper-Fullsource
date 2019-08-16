<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\ResourceModel\RuleQuote;

use Amasty\Acart\Model\History;
use Amasty\Acart\Model\RuleQuote;
use Amasty\Acart\Model\StatisticsManagement;

/**
 * @method RuleQuote[] getItems()
 * @method RuleQuote getFirstItem()
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init(\Amasty\Acart\Model\RuleQuote::class, \Amasty\Acart\Model\ResourceModel\RuleQuote::class);
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

    public function addCompleteFilter()
    {
        $this->getSelect()
            ->joinLeft(
                ['history' => $this->getTable('amasty_acart_history')],
                'main_table.rule_quote_id = history.rule_quote_id AND history.status <> "' . History::STATUS_SENT . '"',
                []
            )
            ->where('main_table.status = ? ', RuleQuote::STATUS_PROCESSING)
            ->group('main_table.rule_quote_id')
            ->having('count(history.rule_quote_id) = 0');

        return $this;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function addFilterByAbandonedStatus($status)
    {
        $this->addFieldToFilter('abandoned_status', $status)
            ->groupByQuoteId();

        return $this;
    }

    /**
     * @param string $status
     *
     * @return Collection
     */
    public function addFilterByStatus($status)
    {
        return $this->addFieldToFilter('status', $status);
    }

    public function groupByQuoteId()
    {
        $this->getSelect()->group('quote_id');
    }

    /**
     * @param array $storeIds
     * @param string $dateTo
     * @param string $dateFrom
     *
     * @return string
     */
    public function getTotalAbandonedMoney($storeIds, $dateTo, $dateFrom)
    {
        $select = $this->getSelect();
        $select2 = clone $select;

        if ($dateFrom && $dateTo) {
            $select->where('main_table.created_at BETWEEN \'' . $dateFrom . '\' AND \'' . $dateTo . '\'');
        }

        $select2->reset();

        $select2->from(['quote' => $this->getTable('quote')], StatisticsManagement::SUM_GRAND_TOTAL . ' as total')
            ->where('quote.is_active = 1')
            ->where(
                'quote.entity_id IN (?)',
                $select->reset('columns')
                    ->where('main_table.store_id IN (?)', $storeIds)
                    ->columns('quote_id')
                    ->group('quote_id')
            );

        return $this->getConnection()->fetchOne($select2);
    }

    /**
     * @param array $storeIds
     * @param string $dateTo
     * @param string $dateFrom
     * @param string $param
     *
     * @return string
     */
    public function getRestoredOrdersValue($storeIds, $dateTo, $dateFrom, $param)
    {
        $select = $this->getSelect();
        $select2 = clone $select;

        if ($dateFrom && $dateTo) {
            $select->where('main_table.created_at BETWEEN \'' . $dateFrom . '\' AND \'' . $dateTo . '\'');
        }

        $select2->reset();

        $select2->from(['order' => $this->getTable('sales_order')], $param . ' as total')
            ->where(
                'order.quote_id IN (?)',
                $select->reset('columns')
                    ->columns('quote_id')
                    ->where('main_table.store_id IN (?)', $storeIds)
                    ->where(
                        'main_table.abandoned_status = (?)',
                        \Amasty\Acart\Model\RuleQuote::ABANDONED_RESTORED_STATUS
                    )
                    ->group('quote_id')
            );

        return $this->getConnection()->fetchOne($select2);
    }

    /**
     * @param array $storeIds
     *
     * @return Collection
     */
    public function addFilterByStoreIds($storeIds)
    {
        return $this->addFieldToFilter('store_id', ['in' => $storeIds]);
    }

    /**
     * @param string $dateTo
     * @param string $dateFrom
     *
     * @return Collection
     */
    public function addFilterByDate($dateTo, $dateFrom)
    {
        if ($dateTo && $dateFrom) {
            return $this->addFieldToFilter('created_at', ['lteq' => $dateTo])
                ->addFieldToFilter('created_at', ['gteq' => $dateFrom]);
        } else {
            return $this;
        }
    }
}
