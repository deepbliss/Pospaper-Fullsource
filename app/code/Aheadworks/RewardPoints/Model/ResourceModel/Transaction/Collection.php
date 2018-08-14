<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\ResourceModel\Transaction;

use Aheadworks\RewardPoints\Api\Data\TransactionSearchResultsInterface;
use Aheadworks\RewardPoints\Model\Config;
use Aheadworks\RewardPoints\Model\Transaction;
use Aheadworks\RewardPoints\Model\ResourceModel\Transaction as TransactionResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Aheadworks\RewardPoints\Model\ResourceModel\Transaction\Collection
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Collection extends AbstractCollection implements TransactionSearchResultsInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteriaInterface
     */
    private $searchCriteria;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param Config $config,
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        Config $config,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->config = $config;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(Transaction::class, TransactionResource::class);
    }

    /**
     * Add customer filter
     *
     * @param int|string $customerId
     * @return $this
     */
    public function addCustomerFilter($customerId)
    {
        $this->addFieldToFilter('customer_id', ['eq' => $customerId]);
        return $this;
    }

    /**
     * Get search criteria.
     *
     * @return \Magento\Framework\Api\SearchCriteriaInterface|null
     */
    public function getSearchCriteria()
    {
        return $this->searchCriteria;
    }

    /**
     * Set search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        $this->searchCriteria = $searchCriteria;
        return $this;
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * Set total count.
     *
     * @param int $totalCount
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * Set items list.
     *
     * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null)
    {
        if (!$items) {
            return $this;
        }
        foreach ($items as $item) {
            $this->addItem($item);
        }
        return $this;
    }

    /**
     * Add expired transaction filter to collection
     *
     * @return $this
     */
    public function addExpiredTransactionFilter()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->addFieldToFilter('expiration_date', ['notnull' => true]);
        $this->addFieldToFilter('expiration_date', ['lteq' => $now]);
        return $this;
    }

    /**
     * Add will expire transaction filter to collection
     *
     * @return $this
     */
    public function addWillExpireTransactionFilter()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $now->add(new \DateInterval('P' . $this->config->getExpirationReminderDays() . 'D'));

        $this->addFieldToFilter('expiration_date', ['notnull' => true]);
        $this->addFieldToFilter(new \Zend_Db_Expr('DATE(expiration_date)'), ['lteq' => $now->format('Y-m-d')]);
        return $this;
    }

    /**
     * Add filter by entity
     *
     * @param int $entityType
     * @param int $entityId
     * @return $this
     */
    public function addFilterByEntity($entityType, $entityId)
    {
        $this->getSelect()
            ->joinLeft(
                ['te' => $this->getTable('aw_rp_transaction_entity')],
                'te.transaction_id = main_table.transaction_id',
                []
            )->where('te.entity_type = ?', $entityType)
           ->where('te.entity_id = ?', $entityId);
        return $this;
    }

    /**
     * Add positive balance filter
     *
     * @return $this
     */
    public function addPositiveBalanceFilter()
    {
        $this->addFieldToFilter('balance', ['gteq' => 0]);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field == 'created_by') {
            return $this->addCreatedByFilter($condition);
        }
        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add created by filter
     *
     * @param string $createdBy
     * @return $this
     */
    public function addCreatedByFilter($createdBy)
    {
        $this->addFilter('created_by', $createdBy, 'public');
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterLoad()
    {
        $this->attachRelationTable(
            'aw_rp_transaction_entity',
            'transaction_id',
            'transaction_id',
            '',
            'entities'
        );
        $this->attachRelationTable(
            'aw_rp_transaction_adjusted_history',
            'transaction_id',
            'transaction_id',
            '',
            'balance_adjusted'
        );
        $this->attachRelationTable(
            'admin_user',
            'created_by',
            'user_id',
            '',
            'created_by'
        );
        return parent::_afterLoad();
    }

    /**
     * {@inheritdoc}
     */
    protected function _renderFiltersBefore()
    {
        $this->joinLinkageTable('admin_user', 'created_by', 'user_id', 'created_by');
        parent::_renderFiltersBefore();
    }

    /**
     * Join to linkage table if filter is applied
     *
     * @param string $tableName
     * @param string $columnName
     * @param string $linkageColumnName
     * @param string $columnFilter
     * @return void
     */
    private function joinLinkageTable(
        $tableName,
        $columnName,
        $linkageColumnName,
        $columnFilter
    ) {
        if ($this->getFilter($columnFilter)) {
            $linkageTableName = $columnFilter . '_table';
            $select = $this->getSelect();
            $select->joinLeft(
                [$linkageTableName => $this->getTable($tableName)],
                'main_table.' . $columnName . ' = ' . $linkageTableName . '.' . $linkageColumnName,
                []
            );
            switch ($columnFilter) {
                case 'created_by':
                    $this->addFilterToMap(
                        $columnFilter,
                        'CONCAT_WS(" ", ' . $linkageTableName . '.firstname, ' . $linkageTableName . '.lastname)'
                    );
                    break;
                default:
                    $this->addFilterToMap($columnFilter, $columnFilter . '_table.' . $columnFilter);
                    break;
            }
        }
    }

    /**
     * Attach entity table data to collection items
     *
     * @param string $tableName
     * @param string $columnName
     * @param string $linkageColumnName
     * @param string $columnNameRelationTable
     * @param string $fieldName
     * @return void
     */
    private function attachRelationTable(
        $tableName,
        $columnName,
        $linkageColumnName,
        $columnNameRelationTable,
        $fieldName
    ) {
        $ids = $this->getColumnValues($columnName);
        if (count($ids)) {
            $connection = $this->getConnection();
            $select = $connection->select()
                ->from([$tableName . '_table' => $this->getTable($tableName)])
                ->where($tableName . '_table.' . $linkageColumnName . ' IN (?)', $ids);

            /** @var \Magento\Framework\DataObject $item */
            foreach ($this as $item) {
                switch ($fieldName) {
                    case 'balance_adjusted':
                        $result = 0;
                        break;
                    case 'created_by':
                        $result = '';
                        break;
                    default:
                        $result = [];
                }
                $id = $item->getData($columnName);
                foreach ($connection->fetchAll($select) as $data) {
                    if ($data[$linkageColumnName] == $id) {
                        switch ($fieldName) {
                            case 'entities':
                                $result[$data['entity_type']] = [
                                    'entity_id'    => $data['entity_id'],
                                    'entity_label' => $data['entity_label']
                                ];
                                break;
                            case 'balance_adjusted':
                                $result += $data['balance'];
                                break;
                            case 'created_by':
                                $result = $data['firstname'] . ' ' . $data['lastname'];
                                break;
                            default:
                                $result[] = $data[$columnNameRelationTable];
                                break;
                        }
                    }
                }
                $item->setData($fieldName, $result);
            }
        }
    }
}
