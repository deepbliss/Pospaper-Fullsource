<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

class Schedule extends \Magento\Framework\Model\AbstractModel
{
    public function _construct()
    {
        $this->_init('Amasty\Acart\Model\ResourceModel\Schedule');
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $config = $this->getData();

        unset($config['rule_id']);

        $config['discount_amount'] = (int)$config['discount_amount'];
        $config['discount_qty'] = (int)$config['discount_qty'];

        return $config;
    }

    /**
     * @return int
     */
    public function getDeliveryTime()
    {
        return ($this->getDays() * 24 * 60 * 60) +
            ($this->getHours() * 60 * 60) +
            ($this->getMinutes() * 60);
    }
}
