<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Ui\Component\Listing\Column;

use Magento\Store\Ui\Component\Listing\Column\Store\Options;

/**
 * Class StoreOptions
 *
 * To use this class, add
 * <item name="options" xsi:type="object">Amasty\Base\Ui\Component\Listing\Column\StoreOptions</item>
 * to your ui field as argument
 *
 * @package Amasty\Base\Ui\Component\Listing\Column
 */
class StoreOptions extends Options
{
    /**
     * All Store Views value
     */
    const ALL_STORE_VIEWS = '0';

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $this->currentOptions['All Store Views']['label'] = __('All Store Views');
        $this->currentOptions['All Store Views']['value'] = self::ALL_STORE_VIEWS;

        $this->generateCurrentOptions();

        $this->options = array_values($this->currentOptions);

        return $this->options;
    }
}
