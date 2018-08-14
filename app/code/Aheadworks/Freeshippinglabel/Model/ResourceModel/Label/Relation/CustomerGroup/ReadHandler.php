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
 * Class ReadHandler
 * @package Aheadworks\Freeshippinglabel\Model\ResourceModel\Label\Relation\CustomerGroup
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
                $this->metadataPool->getMetadata(LabelInterface::class)->getEntityConnectionName()
            );
            $select = $connection->select()
                ->from(
                    $this->resourceConnection->getTableName('aw_fslabel_label_customer_group'),
                    'customer_group_id'
                )->where('label_id = :id');
            $customerGroupIds = $connection->fetchCol($select, ['id' => $entityId]);
            $entity->setCustomerGroupIds($customerGroupIds);
        }
        return $entity;
    }
}
