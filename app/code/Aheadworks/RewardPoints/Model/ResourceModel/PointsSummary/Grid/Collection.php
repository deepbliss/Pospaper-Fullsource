<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\ResourceModel\PointsSummary\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Magento\Sales\Model\Order;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;
use Aheadworks\RewardPoints\Model\Config;
use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\RewardPoints\Model\Source\SubscribeStatus;

/**
 * Class Aheadworks\RewardPoints\Model\ResourceModel\PointsSummary\Grid\Collection
 */
class Collection extends SearchResult
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param string $mainTable
     * @param string $resourceModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        Config $config,
        StoreManagerInterface $storeManager,
        $mainTable,
        $resourceModel
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $mainTable,
            $resourceModel
        );
    }

    /**
     *  {@inheritDoc}
     */
    protected function _initSelect()
    {
        $this->getSelect()->from(
            ['main_table' => $this->getMainTable()],
            [
                'customer_id' => 'main_table.entity_id',
                'website_id' => 'main_table.website_id',
                'firstname',
                'lastname',
                'email'
            ]
        );

        $this->addNameToSelect();
        $this->addCustomerEmailToSelect();
        $this->joinPointsSummaryTable();
        $this->joinLifetimeSales();
        $this->joinNootificationStatuses();
        $this->addFilterToMap('website_id', 'main_table.website_id');
        $this->addFilterToMap('balance_update_notification_status', 'buns.balance_update_notification_status');
        $this->addFilterToMap('expiration_notification_status', 'buns.expiration_notification_status');
        return $this;
    }

    /**
     * Add Customer first name and last name to select
     *
     * @return $this
     */
    private function addNameToSelect()
    {
        $connection = $this->getResource()->getConnection();
        $nameExpr = $connection->getConcatSql(['firstname', 'lastname'], ' ');

        $select = $this->getSelect();
        $select->columns(
            ['customer_name' => $nameExpr]
        );

        return $this;
    }

    /**
     * Add Customer email to select
     *
     * @return $this
     */
    private function addCustomerEmailToSelect()
    {
        $select = $this->getSelect();
        $select->columns(
            ['customer_email' => 'email']
        );

        return $this;
    }

    /**
     * Join aw_rp_points_summary table
     *
     * @return $this
     */
    private function joinPointsSummaryTable()
    {
        $connection = $this->getResource()->getConnection();

        $select = $this->getSelect();

        $select->joinLeft(
            ['rps' => $this->getTable('aw_rp_points_summary')],
            'rps.customer_id=main_table.entity_id',
            [
                'points' => $connection->getIfNullSql('rps.points', '0'),
                'points_earn' => $connection->getIfNullSql('rps.points_earn', '0'),
                'points_spend' => $connection->getIfNullSql('rps.points_spend', '0'),
            ]
        );
        return $this;
    }

    /**
     * Join balance update and expiration notification statuses
     *
     * @return $this
     */
    private function joinNootificationStatuses()
    {
        $connection = $this->getResource()->getConnection();
        $cases = [];
        foreach ($this->storeManager->getWebsites() as $website) {
            $cases[$website->getId()] = $this->config->isSubscribeCustomersToNotificationsByDefault($website->getId())
                ? SubscribeStatus::SUBSCRIBED
                : SubscribeStatus::NOT_SUBSCRIBED;
        }

        $select = $this->getConnection()->select()
            ->from(['ce' => $this->getTable('customer_entity')], ['entity_id'])
            ->joinLeft(
                ['rps' => $this->getTable('aw_rp_points_summary')],
                'rps.customer_id=ce.entity_id',
                [
                    'balance_update_notification_status' => $connection->getIfNullSql(
                        'rps.balance_update_notification_status',
                        $connection->getCaseSql('ce.website_id', $cases)
                    ),
                    'expiration_notification_status' => $connection->getIfNullSql(
                        'rps.expiration_notification_status',
                        $connection->getCaseSql('ce.website_id', $cases)
                    )
                ]
            );

        $this->getSelect()->joinLeft(
            ['buns' => $select],
            'buns.entity_id=main_table.entity_id',
            [
                'balance_update_notification_status' => 'buns.balance_update_notification_status',
                'expiration_notification_status' => 'buns.expiration_notification_status'
            ]
        );
        return $this;
    }

    /**
     * Join lifetime sales sub-query
     *
     * @return Collection
     */
    private function joinLifetimeSales()
    {
        $select = $this->getSelect();
        $connection = $this->getResource()->getConnection();

        $select->joinLeft(
            ['so' => $this->getLifetimeSalesSelect()],
            'so.customer_id=main_table.entity_id',
            [
                'lifetime_sales' => $connection->getIfNullSql('so.lifetime_sales', '0'),
            ]
        );
        return $this;
    }

    /**
     * Retrieve lifetime sales sub-query
     *
     * @return \Magento\Framework\DB\Select
     */
    private function getLifetimeSalesSelect()
    {
        $select = clone $this->getSelect();
        $select->reset();

        $columns = $this->getConnection()->describeTable($this->getTable('sales_order'));
        $lifetimeSales = 'SUM(IFNULL(base_total_invoiced, 0)) - SUM(IFNULL(base_total_refunded, 0))';
        $allowedExternalColumns = [
            'base_aw_store_credit_refunded' => '+',
            'base_aw_reward_points_refund' => '+'
        ];

        foreach ($allowedExternalColumns as $allowedExternalColumn => $operation) {
            if (isset($columns[$allowedExternalColumn])) {
                $lifetimeSales .= $operation . 'SUM(IFNULL(' . $allowedExternalColumn . ', 0))';
            }
        }

        $select->from(
            $this->getTable('sales_order'),
            [
                'lifetime_sales' => new \Zend_Db_Expr('(' . $lifetimeSales . ')'),
                'customer_id',
            ]
        );

        $select->where('customer_id IS NOT NULL');
        $select->where(
            'state IN (?)',
            [Order::STATE_COMPLETE, Order::STATE_CLOSED, Order::STATE_PROCESSING]
        );

        $select->group('customer_id');

        return $select;
    }

    /**
     * Change filter for customer_name column
     *
     * @param string|array $field
     * @param null|string|array $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field == 'customer_name') {
            $field = ['firstname', 'lastname'];
            $condition = [$condition, $condition];
        } elseif (in_array($field, ['lifetime_sales', 'points', 'points_earn', 'points_spend'])) {
            if (key($condition) == 'lteq') {
                $field = [$field, $field];
                $condition = [['null' => null], $condition];
            }
        } elseif ($field == 'customer_email') {
            $field = 'email';
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Retrieve totals
     *
     * @return []
     */
    public function getTotals()
    {
        $collectionSelect = clone $this->getSelect();
        $collectionSelect->reset(\Magento\Framework\DB\Select::LIMIT_COUNT)
            ->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);

        $totalSelect = clone $this->getSelect();
        $totalSelect->reset(\Magento\Framework\DB\Select::COLUMNS)
            ->reset(\Magento\Framework\DB\Select::FROM)
            ->reset(\Magento\Framework\DB\Select::GROUP)
            ->reset(\Magento\Framework\DB\Select::WHERE)
            ->reset(\Magento\Framework\DB\Select::LIMIT_COUNT)
            ->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET)
            ->from(
                ['main_table' => new \Zend_Db_Expr(sprintf('(%s)', $collectionSelect))],
                $this->getTotalColumns()
            );
        return $this->getConnection()->fetchRow($totalSelect) ?: [];
    }

    /**
     * Retrieve total columns
     *
     * @return []
     */
    private function getTotalColumns()
    {
        return [
            'points_earn' => 'SUM(points_earn)',
            'points_spend' => 'SUM(points_spend)',
            'firstname',
            'lastname'
        ];
    }
}
