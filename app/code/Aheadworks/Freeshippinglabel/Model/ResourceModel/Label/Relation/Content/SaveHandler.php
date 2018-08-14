<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model\ResourceModel\Label\Relation\Content;

use Magento\Framework\App\ResourceConnection;
use Aheadworks\Freeshippinglabel\Api\Data\LabelInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Aheadworks\Freeshippinglabel\Api\Data\LabelContentInterface;

/**
 * Class SaveHandler
 * @package Aheadworks\Freeshippinglabel\Model\ResourceModel\Label\Relation\Content
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
        $content = $entity->getContent() ? : [];

        $connection = $this->resourceConnection->getConnectionByName(
            $this->metadataPool->getMetadata(LabelInterface::class)->getEntityConnectionName()
        );
        $tableName = $this->resourceConnection->getTableName('aw_fslabel_label_content');

        $connection->delete($tableName, ['label_id = ?' => $entityId]);
        $toInsert = [];
        /** @var LabelContentInterface $contentItem */
        foreach ($content as $contentItem) {
            $toInsert[] = [
                'label_id' => $entityId,
                'store_id' => $contentItem->getStoreId(),
                'content_type' => $contentItem->getContentType(),
                'message' => $contentItem->getMessage()
            ];
        }
        if ($toInsert) {
            $connection->insertMultiple($tableName, $toInsert);
        }

        return $entity;
    }
}
