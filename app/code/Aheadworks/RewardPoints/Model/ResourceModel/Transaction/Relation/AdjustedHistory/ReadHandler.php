<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\ResourceModel\Transaction\Relation\AdjustedHistory;

use Magento\Framework\App\ResourceConnection;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class ReadHandler
 *
 * @package Aheadworks\RewardPoints\Model\ResourceModel\Transaction\Relation\AdjustedHistory
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @param MetadataPool $metadataPool
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(MetadataPool $metadataPool, ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
        $this->metadataPool = $metadataPool;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        if ($entityId = (int)$entity->getId()) {
            $connection = $this->resourceConnection->getConnectionByName(
                $this->metadataPool->getMetadata(TransactionInterface::class)->getEntityConnectionName()
            );
            $select = $connection->select()
                ->from($this->resourceConnection->getTableName('aw_rp_transaction_adjusted_history'))
                ->where('transaction_id = :id');
            $transactionHistory = $connection->fetchAll($select, ['id' => $entityId]);
            $result = 0;
            foreach ($transactionHistory as $transactionHistoryData) {
                $result += $transactionHistoryData['balance'];
            }
            $entity->setBalanceAdjusted($result);
        }
        return $entity;
    }
}
