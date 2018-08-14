<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\ResourceModel\Transaction\Relation\Entity;

use Magento\Framework\App\ResourceConnection;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class ReadHandler
 *
 * @package Aheadworks\RewardPoints\Model\ResourceModel\Transaction\Relation\Entity
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
                ->from($this->resourceConnection->getTableName('aw_rp_transaction_entity'))
                ->where('transaction_id = :id');
            $entitiesData = $connection->fetchAssoc($select, ['id' => $entityId]);
            $resultIds = [];
            foreach ($entitiesData as $entityData) {
                $resultIds[$entityData['entity_type']] = [
                    'entity_id'    => $entityData['entity_id'],
                    'entity_label' => $entityData['entity_label']
                ];
            }
            $entity->setEntities($resultIds);
        }
        return $entity;
    }
}
