<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\ResourceModel\Rule;

/**
 * @method \Amasty\Acart\Model\Rule[] getItems()
 * @method \Amasty\Acart\Model\Rule getFirstItem()
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\Acart\Model\Rule', 'Amasty\Acart\Model\ResourceModel\Rule');
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }
}
