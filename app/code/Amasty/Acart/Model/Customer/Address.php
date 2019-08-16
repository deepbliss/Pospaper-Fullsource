<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\Customer;

class Address extends \Magento\Quote\Model\Quote\Address
{
    /**
     * @param \Magento\Customer\Model\Data\Address $address
     *
     * @return $this
     */
    public function setAddress(\Magento\Customer\Model\Data\Address $address)
    {
        parent::setData($address->__toArray());

        return $this;
    }
}
