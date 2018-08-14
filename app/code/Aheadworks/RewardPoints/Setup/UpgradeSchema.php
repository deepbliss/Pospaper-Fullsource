<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Setup;

use Aheadworks\RewardPoints\Model\Source\NotifiedStatus;
use Aheadworks\RewardPoints\Model\Source\SubscribeStatus;
use Aheadworks\RewardPoints\Model\Source\Transaction\Status;
use Magento\Framework\App\State;
use Aheadworks\RewardPoints\Model\Source\Transaction\EntityType;
use Aheadworks\RewardPoints\Model\Source\Transaction\Type;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Aheadworks\RewardPoints\Model\Comment\CommentPoolInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Aheadworks\RewardPoints\Model\DateTime;
use Aheadworks\RewardPoints\Model\Config;

/**
 * Class UpgradeSchema
 *
 * @package Aheadworks\RewardPoints\Setup
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var CommentPoolInterface
     */
    private $commentPool;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param State $appState
     * @param CommentPoolInterface $commentPool
     * @param OrderRepositoryInterface $orderRepository
     * @param DateTime $dateTime
     * @param Config $config
     */
    public function __construct(
        State $appState,
        CommentPoolInterface $commentPool,
        OrderRepositoryInterface $orderRepository,
        DateTime $dateTime,
        Config $config
    ) {
        try {
            $appState->setAreaCode('adminhtml');
        } catch (LocalizedException $e) {
        }
        $this->commentPool = $commentPool;
        $this->orderRepository = $orderRepository;
        $this->dateTime = $dateTime;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if ($context->getVersion() && version_compare($context->getVersion(), '1.1.0', '<')) {
            $this->addTransactionTables($setup);
            $this->addColumnsToTransactionTable($setup);
            $this->addColumnsToPointsSummaryTable($setup);
            $this->updateTransactionData($setup);
        }
    }

    /**
     * Add transaction tables
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addTransactionTables(SchemaSetupInterface $installer)
    {
        /**
         * Create table 'aw_rp_transaction_entity'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('aw_rp_transaction_entity'))
            ->addColumn(
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Transaction Id'
            )->addColumn(
                'entity_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true, 'primary' => true],
                'Entity Type'
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true, 'primary' => true],
                'Entity Id'
            )->addColumn(
                'entity_label',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '255',
                ['nullable' => true],
                'Entity Label'
            )->addIndex(
                $installer->getIdxName('aw_rp_transaction_entity', ['transaction_id', 'entity_type', 'entity_id']),
                ['transaction_id', 'entity_type', 'entity_id']
            )->addForeignKey(
                $installer->getFkName(
                    'aw_rp_transaction_entity',
                    'transaction_id',
                    'aw_rp_transaction',
                    'transaction_id'
                ),
                'transaction_id',
                $installer->getTable('aw_rp_transaction'),
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment('Aheadworks Reward Points Transaction Entity');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'aw_rp_transaction_adjusted_history'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('aw_rp_transaction_adjusted_history'))
            ->addColumn(
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Transaction Id'
            )->addColumn(
                'from_transaction_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'primary' => true],
                'From Transaction Id'
            )->addColumn(
                'balance',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Balance'
            )->addIndex(
                $installer->getIdxName('aw_rp_transaction_adjusted_history', ['transaction_id', 'from_transaction_id']),
                ['transaction_id', 'from_transaction_id']
            )->addForeignKey(
                $installer->getFkName(
                    'aw_rp_transaction_adjusted_history',
                    'transaction_id',
                    'aw_rp_transaction',
                    'transaction_id'
                ),
                'transaction_id',
                $installer->getTable('aw_rp_transaction'),
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    'aw_rp_transaction_adjusted_history',
                    'from_transaction_id',
                    'aw_rp_transaction',
                    'transaction_id'
                ),
                'from_transaction_id',
                $installer->getTable('aw_rp_transaction'),
                'transaction_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment('Aheadworks Reward Points Transaction Adjusted History');
        $installer->getConnection()->createTable($table);

        return $this;
    }

    /**
     * Add columns to transaction table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addColumnsToTransactionTable(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();
        $connection->addColumn(
            $installer->getTable('aw_rp_transaction'),
            'type',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'unsigned' => true,
                'default' => Type::BALANCE_ADJUSTED_BY_ADMIN,
                'comment'  => 'Transaction Type'
            ]
        );
        $connection->addColumn(
            $installer->getTable('aw_rp_transaction'),
            'status',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'unsigned' => true,
                'default' => Status::ACTIVE,
                'comment'  => 'Transaction Status'
            ]
        );
        $connection->addColumn(
            $installer->getTable('aw_rp_transaction'),
            'comment_to_customer_placeholder',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'length' => 255,
                'after' => 'comment_to_customer',
                'comment'  => 'Comment To Customer Placeholder'
            ]
        );
        $connection->addColumn(
            $installer->getTable('aw_rp_transaction'),
            'current_balance',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => false,
                'unsigned' => true,
                'default' => '0',
                'after' => 'balance',
                'comment'  => 'Current Customer Balance'
            ]
        );
        $connection->addColumn(
            $installer->getTable('aw_rp_transaction'),
            'balance_update_notified',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'unsigned' => true,
                'default' => NotifiedStatus::NO,
                'comment' => 'Balance Update Notified'
            ]
        );
        $connection->addColumn(
            $installer->getTable('aw_rp_transaction'),
            'expiration_notified',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'unsigned' => true,
                'default' => NotifiedStatus::WAITING,
                'comment' => 'Expiration Notified'
            ]
        );
        $connection->addColumn(
            $installer->getTable('aw_rp_transaction'),
            'created_by',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'unsigned' => true,
                'comment' => 'Created By'
            ]
        );

        return $this;
    }

    /**
     * Add columns to points summary table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addColumnsToPointsSummaryTable(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();

        $connection->addColumn(
            $installer->getTable('aw_rp_points_summary'),
            'balance_update_notification_status',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'unsigned' => true,
                'default' => SubscribeStatus::SUBSCRIBED,
                'comment' => 'Balance Update Notifications (status)'
            ]
        );
        $connection->addColumn(
            $installer->getTable('aw_rp_points_summary'),
            'expiration_notification_status',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'unsigned' => true,
                'default' => SubscribeStatus::SUBSCRIBED,
                'comment' => 'Points Expiration Notification (status)'
            ]
        );

        return $this;
    }

    /**
     * Update transaction data
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function updateTransactionData(SchemaSetupInterface $installer)
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $connection = $installer->getConnection();
        $select = $connection->select()
            ->from($installer->getTable('aw_rp_transaction'), ['transaction_id'])
            ->where('expiration_date <= ?', $now)
            ->where('expiration_date IS NOT NULL');
        $expiredTransactionIds = $connection->fetchCol($select);
        if (count($expiredTransactionIds)) {
            $connection->update(
                $installer->getTable('aw_rp_transaction'),
                ['status' => Status::EXPIRED],
                'transaction_id IN(' . implode(',', array_values($expiredTransactionIds)) . ')'
            );
        }

        $oldComments = [
            'reward_for_order' => ['parse' => true, 'type' => Type::POINTS_REWARDED_FOR_ORDER],
            'reward_for_registration' => ['type' => Type::POINTS_REWARDED_FOR_REGISTRATION],
            'reward_for_review' => ['type' => Type::POINTS_REWARDED_FOR_REVIEW_APPROVED_BY_ADMIN],
            'reward_for_share' => ['type' => Type::POINTS_REWARDED_FOR_SHARES],
            'reward_for_newsletter_signup' => ['type' => Type::POINTS_REWARDED_FOR_NEWSLETTER_SIGNUP],
            'spent_for_order' => ['parse' => true, 'type' => Type::POINTS_SPENT_ON_ORDER],
            'expired_points' => ['parse' => true, 'type' => Type::POINTS_EXPIRED]
        ];
        $select = $connection->select()
            ->from($installer->getTable('aw_rp_transaction'));
        $transactions = $connection->fetchAssoc($select);

        // Convert comment
        foreach ($transactions as $transaction) {
            $updateParams = ['type' => Type::BALANCE_ADJUSTED_BY_ADMIN];
            foreach ($oldComments as $oldComment => $param) {
                if (strrpos($transaction['comment_to_customer'], $oldComment) !== false) {
                    $commentArguments = [];
                    $updateParams = ['type' => $param['type']];
                    if (isset($param['parse']) && $param['parse']) {
                        if ($param['type'] == Type::POINTS_EXPIRED) {
                            $bind = [
                                'transaction_id' => $transaction['transaction_id'],
                                'entity_id'    => 0,
                                'entity_type'  => EntityType::TRANSACTION_ID,
                                'entity_label' => ''
                            ];
                            $commentArguments = [
                                EntityType::TRANSACTION_ID => [
                                    'entity_id' => 0,
                                    'entity_label' => ''
                                ]
                            ];
                        } else {
                            $orderId = str_replace($oldComment . '_', '', $transaction['comment_to_customer']);
                            try {
                                $order = $this->orderRepository->get($orderId);
                            } catch (NoSuchEntityException $e) {
                                continue;
                            }

                            $bind = [
                                'transaction_id' => $transaction['transaction_id'],
                                'entity_id'    => $order->getEntityId(),
                                'entity_type'  => EntityType::ORDER_ID,
                                'entity_label' => $order->getIncrementId()
                            ];
                            $commentArguments = [
                                EntityType::ORDER_ID => [
                                    'entity_id' => $order->getEntityId(),
                                    'entity_label' => $order->getIncrementId()
                                ]
                            ];
                        }
                        $connection->insert($installer->getTable('aw_rp_transaction_entity'), $bind);
                    }

                    $commentInstance = $this->commentPool->get($param['type']);
                    $updateParams['comment_to_customer'] = $commentInstance->renderComment($commentArguments);
                    $updateParams['comment_to_customer_placeholder'] = $commentInstance->getLabel();
                }
            }

            $connection->update(
                $installer->getTable('aw_rp_transaction'),
                $updateParams,
                'transaction_id = ' . $transaction['transaction_id']
            );
        }

        $select = $connection->select()
            ->from($installer->getTable('aw_rp_points_summary'));
        $pointsSummary = $connection->fetchAssoc($select);

        foreach ($pointsSummary as $summary) {
            $select = $connection->select()
                ->from($installer->getTable('aw_rp_transaction'), ['transaction_id'])
                ->where('customer_id = ?', $summary['customer_id']);
            $transactionIds = $connection->fetchCol($select);
            if (count($transactionIds)) {
                $connection->update(
                    $installer->getTable('aw_rp_transaction'),
                    ['status' => Status::USED, 'expiration_notified' => NotifiedStatus::CANCELLED],
                    'transaction_id IN(' . implode(',', array_values($transactionIds)) . ')'
                );
                if ($summary['points'] > 0) {
                    $customerSelect = $connection->select()
                        ->from(
                            $installer->getTable('customer_entity'),
                            [
                                'email',
                                'firstname',
                                'lastname'
                            ]
                        )
                        ->where('entity_id = ?', $summary['customer_id']);
                    $customer = $connection->fetchRow($customerSelect);
                    $connection->insert(
                        $installer->getTable('aw_rp_transaction'),
                        [
                            'customer_id' => $summary['customer_id'],
                            'customer_name' => $customer['firstname'] . ' ' . $customer['lastname'],
                            'customer_email' => $customer['email'],
                            'comment_to_customer' => null,
                            'comment_to_customer_placeholder' => null,
                            'comment_to_admin' =>
                                'Transaction has been created after update points from 1.0.0 to 1.1.0',
                            'balance' => $summary['points'],
                            'current_balance' => $summary['points'],
                            'transaction_date' => $this->dateTime->getTodayDate(true),
                            'expiration_date' => $this->getExpirationDate($summary['website_id']),
                            'website_id' => $summary['website_id'],
                            'type' => Type::BALANCE_ADJUSTED_BY_ADMIN,
                            'status' => Status::ACTIVE,
                            'balance_update_notified' => NotifiedStatus::NO,
                            'expiration_notified' => NotifiedStatus::WAITING,
                            'created_by' => null
                        ]
                    );
                }
            }
        }
    }

    /**
     * Retrieve expiration date
     *
     * @param  int|null $websiteId
     * @return string
     */
    private function getExpirationDate($websiteId = null)
    {
        $expireInDays = $this->config->getCalculationExpireRewardPoints($websiteId);

        if ($expireInDays == 0) {
            return null;
        }
        return $this->dateTime->getExpirationDate($expireInDays, false);
    }
}
