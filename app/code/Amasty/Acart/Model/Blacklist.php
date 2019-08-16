<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

class Blacklist extends \Magento\Framework\Model\AbstractModel
{
    public function _construct()
    {
        $this->_init('Amasty\Acart\Model\ResourceModel\Blacklist');
    }
}
