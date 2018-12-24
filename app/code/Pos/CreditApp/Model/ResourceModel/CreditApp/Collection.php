<?php


namespace Pos\CreditApp\Model\ResourceModel\CreditApp;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Pos\CreditApp\Model\CreditApp::class,
            \Pos\CreditApp\Model\ResourceModel\CreditApp::class
        );
    }
}
