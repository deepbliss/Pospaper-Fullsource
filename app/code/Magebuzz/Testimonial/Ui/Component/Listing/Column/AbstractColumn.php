<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */

namespace Magebuzz\Testimonial\Ui\Component\Listing\Column;

abstract class AbstractColumn extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $this->_prepareItem($item);
            }
        }

        return $dataSource;
    }

    /**
     * prepare item.
     *
     * @param array $item
     *
     * @return array
     */
    abstract protected function _prepareItem(array & $item);
}
