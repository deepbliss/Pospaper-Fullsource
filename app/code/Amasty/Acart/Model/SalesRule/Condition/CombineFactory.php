<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\SalesRule\Condition;

class CombineFactory extends \Magento\SalesRule\Model\Rule\Condition\CombineFactory
{
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $instanceName = '\\Amasty\\Acart\\Model\\SalesRule\\Condition\\Combine'
    ) {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }
}
