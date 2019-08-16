<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\ResourceModel\Quote;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Collection extends \Magento\Quote\Model\ResourceModel\Quote\Collection
{
    const LAST_EXECUTED_PATH = 'amasty_acart/common/last_executed';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot $entitySnapshot,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $entitySnapshot,
            $connection,
            $resource
        );
    }

    /**
     * @return $this
     */
    public function addAbandonedCartsFilter()
    {
        $this->addFieldToFilter('main_table.is_active', ['eq' => 1])
            ->getSelect()->joinLeft(
                ['ruleQuote' => $this->getTable('amasty_acart_rule_quote')],
                'main_table.entity_id = ruleQuote.quote_id and ruleQuote.test_mode <> 1',
                []
            )->where('main_table.items_count > 0');

        if ($this->scopeConfig->getValue('amasty_acart/general/send_onetime')) {
            $this->getSelect()->where('ruleQuote.rule_quote_id IS NULL');
        } else {
            $this->getSelect()
                ->columns(
                    [
                        'maxRuleId' => 'MAX(`ruleQuote`.`rule_quote_id`)',
                        'latestCreated' => 'MAX(ruleQuote.created_at)'
                    ]
                )->group('main_table.entity_id')
                ->having('maxRuleId IS NULL OR latestCreated < updated_at');
        }

        return $this;
    }

    /**
     * @param bool $debug
     * @param array $permittedDomains
     *
     * @return $this
     */
    public function joinQuoteEmail($debug = false, $permittedDomains = [])
    {
        $this->getSelect()->joinLeft(
            ['quoteEmail' => $this->getTable('amasty_acart_quote_email')],
            'main_table.entity_id = quoteEmail.quote_id',
            ['acart_quote_email' => 'customer_email']
        );

        $emailColumn = $this->getConnection()->getIfNullSql('main_table.customer_email', 'quoteEmail.customer_email');

        if ($debug && count($permittedDomains)) {
            $emailCondition = [];
            foreach ($permittedDomains as $domain) {
                $emailCondition[] = ['like' => '%@' . $domain . '%'];
            }

            $this->addFieldToFilter(
                $emailColumn,
                $emailCondition
            );
        }

        $this->addFieldToFilter($emailColumn, ['notnull' => true]);

        $this->getSelect()
            ->columns($emailColumn . ' as target_email');

        return $this;
    }

    /**
     * @param string $currentExecution
     * @param string $lastExecution
     *
     * @return $this
     */
    public function addTimeFilter($currentExecution, $lastExecution)
    {
        $dateColumn = $this->getConnection()->getIfNullSql('main_table.updated_at', 'main_table.created_at');

        $this->addFieldToFilter($dateColumn, ['gteq' => $lastExecution])
            ->addFieldToFilter($dateColumn, ['lt' => $currentExecution]);

        return $this;
    }
}
