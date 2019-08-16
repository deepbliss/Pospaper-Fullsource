<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

class RuleQuote extends \Magento\Framework\Model\AbstractModel
{
    const COMPLETE_QUOTE_REASON_PLACE_ORDER = 'place_order';
    const COMPLETE_QUOTE_REASON_CLICK_LINK = 'click_by_link';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETE = 'complete';
    const ABANDONED_RESTORED_STATUS = 'restored';
    const ABANDONED_NOT_RESTORED_STATUS = 'notrestored';

    /**
     * @var ResourceModel\History\CollectionFactory
     */
    private $_historyCollectionFactory;

    /**
     * @var RuleFactory
     */
    private $ruleFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Amasty\Acart\Model\ResourceModel\History\CollectionFactory $historyCollectionFactory,
        \Amasty\Acart\Model\RuleFactory $ruleFactory,
        \Amasty\Acart\Model\ResourceModel\RuleQuote $resource,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_historyCollectionFactory = $historyCollectionFactory;
        $this->ruleFactory = $ruleFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function _construct()
    {
        $this->_init('Amasty\Acart\Model\ResourceModel\RuleQuote');
    }

    /**
     * @param string $reason
     *
     * @return void
     */
    public function complete($reason = '')
    {
        /** @var ResourceModel\History\Collection $pendingHistoryCollection */
        $pendingHistoryCollection = $this->_historyCollectionFactory->create();
        $pendingHistoryCollection
            ->addFieldToFilter('rule_quote_id', $this->getId())
            ->addFieldToFilter('status', History::STATUS_PROCESSING);

        switch ($reason) {
            case self::COMPLETE_QUOTE_REASON_PLACE_ORDER:
            case self::COMPLETE_QUOTE_REASON_CLICK_LINK:
                $this->setStatus(self::STATUS_COMPLETE)
                    ->save();
                break;
            default:
                if (!$pendingHistoryCollection->getSize()) {
                    $this->setStatus(self::STATUS_COMPLETE)
                        ->save();
                }
        }
    }

    public function clickByLink()
    {
        $rule = \Magento\Framework\App\ObjectManager::getInstance()
            ->create('Amasty\Acart\Model\Rule')->load($this->getRuleId());

        if (strpos($rule->getCancelCondition(), \Amasty\Acart\Model\Rule::CANCEL_CONDITION_CLICKED) !== false) {
            foreach ($this->_getProcessingItems($this)->getItems() as $ruleQuote) {
                $ruleQuote->complete(self::COMPLETE_QUOTE_REASON_CLICK_LINK);

                /** @var ResourceModel\History\Collection $collection */
                $collection = $this->_historyCollectionFactory->create()
                    ->addFieldToFilter('rule_quote_id', $ruleQuote->getId());
                foreach ($collection->getItems() as $history) {
                    $history->setStatus(\Amasty\Acart\Model\History::STATUS_CANCEL_EVENT);
                    $history->save();
                }
            }
        }
    }

    /**
     * @param int $quoteId
     */
    public function buyQuote($quoteId)
    {
        /** @var \Amasty\Acart\Model\ResourceModel\RuleQuote\Collection $collection */
        $collection = $this->getCollection()
            ->addFieldToFilter('quote_id', $quoteId);
        $collection->getSelect()
            ->order('rule_quote_id desc')
            ->limit(1);

        $ruleQuote = $collection->getFirstItem();

        if ($ruleQuote->getId()) {
            foreach ($this->_getProcessingItems($ruleQuote)->getItems() as $ruleQuote) {
                $ruleQuote->complete(self::COMPLETE_QUOTE_REASON_PLACE_ORDER);
            }
        }
    }

    /**
     * @param \Amasty\Acart\Model\RuleQuote $ruleQuote
     *
     * @return ResourceModel\RuleQuote\Collection
     */
    protected function _getProcessingItems($ruleQuote)
    {
        return \Magento\Framework\App\ObjectManager::getInstance()
            ->create('Amasty\Acart\Model\ResourceModel\RuleQuote\Collection')
            ->addFieldToFilter('customer_email', $ruleQuote->getCustomerEmail())
            ->addFieldToFilter('status', self::STATUS_PROCESSING);
    }

    /**
     * @param int $ruleQuoteId
     *
     * @return $this
     */
    public function loadById($ruleQuoteId)
    {
        $this->_resource->load($this, $ruleQuoteId);

        return $this;
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        return $this->ruleFactory->create()->loadById($this->getRuleId());
    }
}
