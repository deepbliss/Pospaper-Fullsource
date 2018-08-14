<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\ResourceModel\Transaction\Relation\AdjustedHistory;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Magento\Framework\App\ResourceConnection;
use Aheadworks\RewardPoints\Model\Source\Transaction\EntityType as TransactionEntityType;
use Magento\Framework\EntityManager\MetadataPool;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;

/**
 * Class Aheadworks\RewardPoints\Model\ResourceModel\Transaction\Relation\AdjustedHistory
 */
class SaveHandler implements ExtensionInterface
{
    /**
     * @var string
     */
    const TRANSACTION_ADJUSTED_HISTORY = 'transaction_adjusted_history';

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @param ResourceConnection $resourceConnection
     * @param MetadataPool $metadataPool
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        MetadataPool $metadataPool
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->metadataPool = $metadataPool;
    }

    /**
     * {@inheritDoc}
     */
    public function execute($entity, $arguments = [])
    {
        if (isset($arguments[self::TRANSACTION_ADJUSTED_HISTORY])) {
            $connection = $this->getConnection();
            $tableName = $this->resourceConnection->getTableName('aw_rp_transaction_adjusted_history');
            $transactionHistory = $arguments[self::TRANSACTION_ADJUSTED_HISTORY];

            $transactionHistory['transaction_id'] = $entity->getTransactionId();
            $connection->insert(
                $tableName,
                $transactionHistory
            );
            $entity->setBalanceAdjusted($entity->getBalanceAdjusted() + (int)$transactionHistory['balance']);
        }
        return $entity;
    }

    /**
     * Get connection
     *
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     * @throws \Exception
     */
    private function getConnection()
    {
        return $this->resourceConnection->getConnectionByName(
            $this->metadataPool->getMetadata(TransactionInterface::class)->getEntityConnectionName()
        );
    }
}
