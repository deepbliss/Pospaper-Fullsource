<?php

namespace MageModule\Core\Model\Eav\Entity\Attribute\Source;

class Status extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const ENABLED  = 1;
    const DISABLED = 2;

    /**
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['label' => __('Enabled'), 'value' => self::ENABLED],
                ['label' => __('Disabled'), 'value' => self::DISABLED],
            ];
        }

        return $this->_options;
    }

    /**
     * @return array
     */
    public function getOptionArray()
    {
        $_options = [];
        foreach ($this->getAllOptions() as $option) {
            $_options[$option['value']] = $option['label'];
        }
        return $_options;
    }
}
