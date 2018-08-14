<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model\ResourceModel\Label;

use Aheadworks\Freeshippinglabel\Model\Label;
use Aheadworks\Freeshippinglabel\Model\ResourceModel\Label as LabelResource;
use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Label Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Label::class, LabelResource::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterLoad()
    {
        $this->attachRelationTable(
            'aw_fslabel_label_customer_group',
            'id',
            'label_id',
            'customer_group_id',
            'customer_group_ids'
        );
        return parent::_afterLoad();
    }

    /**
     * Attach relation table data to collection items
     *
     * @param string $tableName
     * @param string $columnName
     * @param string $linkageColumnName
     * @param string $columnNameRelationTable
     * @param string $fieldName
     * @return void
     */
    protected function attachRelationTable(
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
                $resultIds = [];
                $id = $item->getData($columnName);
                foreach ($connection->fetchAll($select) as $data) {
                    if ($data[$linkageColumnName] == $id) {
                        $resultIds[] = $data[$columnNameRelationTable];
                    }
                }
                $item->setData($fieldName, $resultIds);
            }
        }
    }
}
