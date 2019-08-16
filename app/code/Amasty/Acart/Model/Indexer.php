<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Indexer extends \Magento\Framework\DataObject
{
    const LAST_EXECUTED_CODE = 'amasty_acart_last_executed';

    /**
     * @var ResourceModel\Quote\CollectionFactory
     */
    private $_resourceQuoteFactory;

    /**
     * @var ResourceModel\Rule\CollectionFactory
     */
    private $_resourceRuleFactory;

    /**
     * @var ResourceModel\History\CollectionFactory
     */
    private $_resourceHistoryFactory;

    /**
     * @var ResourceModel\RuleQuote\CollectionFactory
     */
    private $_resourceRuleQuoteFactory;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $_dateTime;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $_date;

    /**
     * @var \Magento\Framework\FlagFactory
     */
    private $flagManagerFactory;

    /**
     * @var ResourceModel\RuleQuote
     */
    private $ruleQuoteResource;

    /**
     * @var RuleQuoteFromRuleAndQuoteFactory
     */
    private $ruleQuoteFromRuleAndQuoteFactory;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resource;

    /**
     * @var \Magento\Framework\Flag
     */
    private $flagData;

    /**
     * @var int
     */
    protected $_actualGap = 600; //2 days

    /**
     * @var int|null
     */
    protected $_lastExecution = null;

    /**
     * @var int|null
     */
    protected $_currentExecution = null;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezoneInterface;

    public function __construct(
        \Amasty\Acart\Model\ResourceModel\Quote\CollectionFactory $resourceQuoteFactory,
        \Amasty\Acart\Model\ResourceModel\Rule\CollectionFactory $resourceRuleFactory,
        \Amasty\Acart\Model\ResourceModel\History\CollectionFactory $resourceHistoryFactory,
        \Amasty\Acart\Model\ResourceModel\RuleQuote\CollectionFactory $resourceRuleQuoteFactory,
        \Amasty\Acart\Model\ConfigProvider $configProvider,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface,
        \Amasty\Acart\Model\RuleQuoteFromRuleAndQuoteFactory $ruleQuoteFromRuleAndQuoteFactory,
        \Amasty\Acart\Model\ResourceModel\RuleQuote $ruleQuoteResource,
        \Magento\Framework\FlagFactory $flagManagerFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
    ) {
        $this->_resourceQuoteFactory = $resourceQuoteFactory;
        $this->_resourceRuleFactory = $resourceRuleFactory;
        $this->_resourceHistoryFactory = $resourceHistoryFactory;
        $this->_resourceRuleQuoteFactory = $resourceRuleQuoteFactory;
        $this->ruleQuoteFromRuleAndQuoteFactory = $ruleQuoteFromRuleAndQuoteFactory;
        $this->_dateTime = $dateTime;
        $this->_date = $date;
        $this->timezoneInterface = $timezoneInterface;
        $this->configProvider = $configProvider;
        $this->ruleQuoteResource = $ruleQuoteResource;
        $this->flagManagerFactory = $flagManagerFactory;
        $this->resource = $resource;
        parent::__construct($data);
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function run()
    {
        $this->resource->getConnection()->beginTransaction();
        try {
            $this->_prepare();
            $this->_execute();
            $this->resource->getConnection()->commit();
        } catch (\Exception $e) {
            $this->resource->getConnection()->rollBack();
            throw $e;
        }
        $this->getFlag()->save();
    }

    /**
     * @return void
     */
    protected function _prepare()
    {
        /** @var \Amasty\Acart\Model\ResourceModel\Quote\Collection $resourceQuote */
        $resourceQuote = $this->_resourceQuoteFactory->create();
        $resourceQuote->addAbandonedCartsFilter()
            ->joinQuoteEmail(
                $this->configProvider->isDebugMode(),
                $this->configProvider->getDebugEnabledEmailDomains()
            );

        if (!$this->configProvider->isDebugMode()) {
            $resourceQuote->addTimeFilter(
                $this->localizeDate($this->_getCurrentExecution()),
                $this->localizeDate($this->_getLastExecution())
            );
        }

        if ($this->configProvider->isOnlyCustomers()) {
            $resourceQuote->addFieldToFilter('main_table.customer_id', ['notnull' => true]);
        }

        /** @var \Amasty\Acart\Model\ResourceModel\Rule\Collection $resourceRule */
        $resourceRule = $this->_resourceRuleFactory->create()
            ->addFieldToFilter('is_active', \Amasty\Acart\Model\Rule::RULE_ACTIVE)
            ->addOrder('priority', \Amasty\Acart\Model\ResourceModel\Quote\Collection::SORT_ORDER_ASC);

        $processedQuotes = [];
        foreach ($resourceRule->getItems() as $rule) {
            /** @var \Magento\Quote\Model\Quote $quote */
            foreach ($resourceQuote->getItems() as $quote) {
                if (!in_array($quote->getId(), $processedQuotes) && $rule->validate($quote)) {
                    $this->ruleQuoteFromRuleAndQuoteFactory->create($rule, $quote);
                    $processedQuotes[] = $quote->getId();
                }
            }
        }

        $this->deleteAmbiguousRuleQuotes();
    }

    protected function localizeDate($timestamp)
    {
        return $this->timezoneInterface->date($timestamp)
            ->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
    }

    /**
     * Delete previous rule_quote entities if a setting "send email one time per quote" is disabled.
     *
     * @return void
     */
    protected function deleteAmbiguousRuleQuotes()
    {
        $this->ruleQuoteResource->deleteNotUnique();
    }

    /**
     * _execute
     */
    protected function _execute()
    {
        /** @var ResourceModel\History\Collection $resourceHistory */
        $resourceHistory = $this->_resourceHistoryFactory->create();
        $resourceHistory->addRuleQuoteData()
            ->addRuleData()
            ->addTimeFilter(
                $this->_dateTime->formatDate($this->_getCurrentExecution()),
                $this->_dateTime->formatDate($this->_getLastExecution())
            )->addFieldToFilter('ruleQuote.status', \Amasty\Acart\Model\RuleQuote::STATUS_PROCESSING);

        foreach ($resourceHistory->getItems() as $history) {
            $history->execute();
        }

        /** @var ResourceModel\RuleQuote\Collection $resourceRuleQuote */
        $resourceRuleQuote = $this->_resourceRuleQuoteFactory->create();

        foreach ($resourceRuleQuote->addCompleteFilter()->getItems() as $ruleQuote) {
            $ruleQuote->complete();
        }
    }

    /**
     * @return int
     */
    protected function _getLastExecution()
    {
        if ($this->_lastExecution === null) {
            $flag = $this->getFlag()->loadSelf();
            $this->_lastExecution = (string)$flag->getFlagData();

            if (empty($this->_lastExecution)) {
                $this->_lastExecution = $this->_date->gmtTimestamp() - $this->_actualGap;
            }

            $flag->setFlagData($this->_getCurrentExecution());
        }

        return $this->_lastExecution;
    }

    /**
     * @return \Magento\Framework\Flag
     */
    protected function getFlag()
    {
        if ($this->flagData === null) {
            $this->flagData = $this->flagManagerFactory->create(['data' => ['flag_code' => self::LAST_EXECUTED_CODE]]);
        }

        return $this->flagData;
    }

    /**
     * @return int
     */
    protected function _getCurrentExecution()
    {
        if ($this->_currentExecution === null) {
            $this->_currentExecution = $this->_date->gmtTimestamp();
        }

        return $this->_currentExecution;
    }
}
