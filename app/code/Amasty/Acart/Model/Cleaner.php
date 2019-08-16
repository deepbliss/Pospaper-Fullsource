<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Cleaner
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ResourceModel\History\CollectionFactory
     */
    private $historyCollectionFactory;

    /**
     * @var Date
     */
    private $date;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var ResourceModel\RuleQuote\CollectionFactory
     */
    private $ruleQuoteCollectionFactory;

    /**
     * @var string
     */
    private $expiredDate;

    public function __construct(
        \Amasty\Acart\Model\Config $config,
        \Amasty\Acart\Model\ResourceModel\History\CollectionFactory $historyCollectionFactory,
        \Amasty\Acart\Model\Date $date,
        \Psr\Log\LoggerInterface $logger,
        \Amasty\Acart\Model\ResourceModel\RuleQuote\CollectionFactory $ruleQuoteCollectionFactory
    ) {
        $this->config = $config;
        $this->historyCollectionFactory = $historyCollectionFactory;
        $this->date = $date;
        $this->logger = $logger;
        $this->ruleQuoteCollectionFactory = $ruleQuoteCollectionFactory;
    }

    /**
     * @return $this
     */
    public function clearExpiredHistory()
    {
        $historyCollection = $this->getHistoryCollection()->addExpiredFilter($this->getExpiredFormattedDate());
        $this->clearCollection($historyCollection);

        return $this;
    }

    /**
     * @return $this
     */
    public function clearExpiredRuleQuotes()
    {
        $collection = $this->getRuleQuoteCollection()
            ->addFieldToFilter('created_at', ['lt' => $this->getExpiredFormattedDate()])
            ->addFieldToFilter('status', \Amasty\Acart\Model\RuleQuote::STATUS_COMPLETE);
        $this->clearCollection($collection);

        return $this;
    }

    /**
     * @param AbstractCollection $collection
     *
     * @return $this
     */
    private function clearCollection(AbstractCollection $collection)
    {
        try {
            if ($collection->getSize()) {
                $collection->walk('delete');
            }
        } catch (LocalizedException $e) {
            $this->logger->critical($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        return $this;
    }

    /**
     * @return bool|null|string
     */
    private function getExpiredFormattedDate()
    {
        if (!$this->expiredDate) {
            $historyCleanDays = $this->config->getHistoryAutoCleanDays();
            $this->expiredDate = $historyCleanDays ? $this->date->getFormattedDate(
                $this->date->getCurrentTimestamp() - $this->date->convertDaysInSeconds($historyCleanDays)
            ) : false;
        }

        return $this->expiredDate;
    }

    /**
     * @return ResourceModel\History\Collection
     */
    private function getHistoryCollection()
    {
        return $this->historyCollectionFactory->create();
    }

    /**
     * @return ResourceModel\RuleQuote\Collection
     */
    private function getRuleQuoteCollection()
    {
        return $this->ruleQuoteCollectionFactory->create();
    }
}
