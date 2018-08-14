<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */

namespace Amasty\Checkout\Model;

use Magento\Framework\DataObject;

class Totals extends DataObject implements \Amasty\Checkout\Api\Data\TotalsInterface
{
    public function getTotals()
    {
        return $this->getData(self::TOTALS);
    }

    public function getImageData()
    {
        return $this->getData(self::IMAGE_DATA);
    }

    public function getOptionsData()
    {
        return $this->getData(self::OPTIONS_DATA);
    }

    public function getShipping()
    {
        return $this->getData(self::SHIPPING);
    }

    public function getPayment()
    {
        return $this->getData(self::PAYMENT);
    }
}
