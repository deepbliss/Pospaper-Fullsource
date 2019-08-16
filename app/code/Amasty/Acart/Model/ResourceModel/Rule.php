<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Rule extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('amasty_acart_rule', 'rule_id');
    }

    /**
     * return all attribute codes used in Acart rules
     *
     * @return array
     */
    public function getAttributes()
    {
        $db = $this->getConnection();

        $select = $db->select()->from($this->getTable('amasty_acart_attribute'), ['code'])
            ->distinct(true);

        return $db->fetchCol($select);
    }

    /**
     * Save product attributes currently used in conditions and actions of the rule
     *
     * @param int $id
     * @param array $attributes
     *
     * @return $this
     */
    public function saveAttributes($id, $attributes)
    {
        $db = $this->getConnection();
        $tbl = $this->getTable('amasty_acart_attribute');

        $db->delete($tbl, ['rule_id=?' => $id]);

        $data = [];
        foreach ($attributes as $code) {
            $data[] = [
                'rule_id' => $id,
                'code' => $code,
            ];
        }
        $db->insertMultiple($tbl, $data);

        return $this;
    }
}
