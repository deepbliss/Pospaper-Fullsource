<?php

namespace Pos\Custommodule\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface CustomOptionsInterface extends ExtensibleDataInterface
{
    const LABEL = 'label';
    const VALUE = 'value';

    public function getLabel();

    public function setLabel($label);

    public function getValue();

    public function setValue($value);
}
