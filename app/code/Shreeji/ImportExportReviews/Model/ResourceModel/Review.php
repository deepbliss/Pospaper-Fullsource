<?php

namespace Shreeji\ImportExportReviews\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;

class Review extends \Magento\Review\Model\ResourceModel\Review {

    /**
     * Perform actions before object save
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _beforeSave(AbstractModel $object) {
        if (!$object->getId() && !$object->getSkipCreatedAtSet()) {
            $object->setCreatedAt($this->_date->gmtDate());
        }
        if ($object->hasData('stores') && is_array($object->getStores())) {
            $stores = $object->getStores();
            $stores[] = 0;
            $object->setStores($stores);
        } elseif ($object->hasData('stores')) {
            $object->setStores([$object->getStores(), 0]);
        }
        return $this;
    }

}
