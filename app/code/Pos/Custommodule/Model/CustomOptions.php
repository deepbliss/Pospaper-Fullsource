<?php

namespace Pos\Custommodule\Model;

use Magento\Framework\Api\AbstractExtensibleObject;;
use Pos\Custommodule\Api\Data\CustomOptionsInterface;

class CustomOptions extends AbstractExtensibleObject implements CustomOptionsInterface
{
    public function getLabel()
    {
        return $this->_get(self::LABEL);
    }

    public function setLabel($label)
    {
        return $this->setData(self::LABEL, $label);
    }

    public function getValue()
    {
        return $this->_get(self::VALUE);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }
}