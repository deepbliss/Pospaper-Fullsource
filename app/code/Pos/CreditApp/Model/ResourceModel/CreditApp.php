<?php


namespace Pos\CreditApp\Model\ResourceModel;

class CreditApp extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('pos_creditapp_creditapp', 'creditapp_id');
    }
}
