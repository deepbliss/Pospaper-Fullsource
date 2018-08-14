<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model\ResourceModel\Label\Relation\CustomerGroup;

use Magento\Framework\App\ResourceConnection;
use Aheadworks\Freeshippinglabel\Api\Data\LabelInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class SaveHandler
 * @package Aheadworks\Freeshippinglabel\Model\ResourceModel\Label\Relation\CustomerGroup
 */
class SaveHandler implements ExtensionInterface
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
        $entityId = (int)$entity->getId();
        $customerGroupIds = $entity->getCustomerGroupIds();
        $customerGroupIdsOrig = $this->getCustomerGroupIds($entityId);

        $toInsert = array_diff($customerGroupIds, $customerGroupIdsOrig);
        $toDelete = array_diff($customerGroupIdsOrig, $customerGroupIds);

        $connection = $this->getConnection();
        $tableName = $this->resourceConnection->getTableName('aw_fslabel_label_customer_group');

        if (count($toInsert)) {
            $data = [];
            foreach ($toInsert as $customerGroupId) {
                $data[] = [
                    'label_id' => (int)$entityId,
                    'customer_group_id' => (int)$customerGroupId,
                ];
            }
            $connection->insertMultiple($tableName, $data);
        }
        if (count($toDelete)) {
            $connection->delete(
                $tableName,
                ['label_id = ?' => $entityId, 'customer_group_id IN (?)' => $toDelete]
            );
        }
        return $entity;
    }

    /**
     * Get customer group IDs to which entity is assigned
     *
     * @param int $entityId
     * @return array
     */
    private function getCustomerGroupIds($entityId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->resourceConnection->getTableName('aw_fslabel_label_customer_group'), 'customer_group_id')
            ->where('label_id = :id');
        return $connection->fetchCol($select, ['id' => $entityId]);
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
            $this->metadataPool->getMetadata(LabelInterface::class)->getEntityConnectionName()
        );
    }
}
