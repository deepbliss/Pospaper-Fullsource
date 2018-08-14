<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Store\Model\Store;

/**
 * Label model
 */
class Label extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('aw_fslabel_label', 'id');
    }

    /**
     * Fetch message from db by type, store and label_id
     *
     * @param int $labelId
     * @param string $messageType
     * @param int $storeId
     * @return string
     */
    public function getMessageTemplate($labelId, $messageType, $storeId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getTable('aw_fslabel_label_content'), 'message')
            ->where('label_id = :id')
            ->where('store_id IN(:store_id, :default_store_id)')
            ->where('content_type = :content_type')
            ->order('store_id DESC');
        $message = $connection->fetchOne(
            $select,
            [
                'id' => $labelId,
                'store_id' => $storeId,
                'default_store_id' => Store::DEFAULT_STORE_ID,
                'content_type' => $messageType
            ]
        );

        return $message;
    }
}
