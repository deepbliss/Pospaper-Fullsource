<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Amasty\Acart\Model\RuleQuote as RuleQuoteData;

class RuleQuote extends AbstractDb
{
    const MAIN_TABLE = 'amasty_acart_rule_quote';

    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, 'rule_quote_id');
    }

    /**
     * Delete previous rule_quote entities
     *
     * @since 1.6.1 moved from RuleQuote\Collection
     */
    public function deleteNotUnique()
    {
        $activeIds = $this->getActiveRuleQuoteIds();

        $select = $this->getConnection()->select()
            ->from($this->getMainTable())
            ->where('`status` != ?', RuleQuoteData::STATUS_COMPLETE)
            ->where('`rule_quote_id` NOT IN (?)', $activeIds);

        $deleteQuery = $this->getConnection()->deleteFromSelect($select, $this->getMainTable());
        $this->getConnection()->query($deleteQuery);
    }

    /**
     * @return array
     */
    public function getActiveRuleQuoteIds()
    {
        $select = $this->getConnection()->select()
            ->from($this->getMainTable(), ['MAX(`rule_quote_id`)'])
            ->where('status != ?', RuleQuoteData::STATUS_COMPLETE)
            ->group('quote_id');

        return $this->getConnection()->fetchCol($select);
    }
}
