<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model\ResourceModel\Field;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Amasty\Checkout\Model\Field', 'Amasty\Checkout\Model\ResourceModel\Field');
    }

    public function joinStore($store = null)
    {
        if ($store) {
            $this->getSelect()
                ->columns([
                    'label' => new \Zend_Db_Expr('IF (fs.label IS NULL, main_table.label, fs.label)'),
                    'default_label' => 'main_table.label',
                    'use_default' => new \Zend_Db_Expr('fs.id IS NULL'),
                ])
                ->joinLeft(
                    ['fs' => $this->getTable('amasty_amcheckout_field_store')],
                    'fs.field_id = main_table.id AND fs.store_id = ' . (int)$store,
                    []
                )
            ;
        }

        return $this;
    }

    public function joinAttribute()
    {
        $this->getSelect()
            ->join(
                ['a' => $this->getTable('eav_attribute')],
                'a.attribute_id = main_table.attribute_id',
                ['attribute_code', 'default_label' => 'frontend_label']
            )
        ;

        return $this;
    }
}
