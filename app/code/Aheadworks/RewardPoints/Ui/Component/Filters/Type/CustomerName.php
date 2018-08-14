<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Ui\Component\Filters\Type;

use Magento\Ui\Component\Filters\Type\Select;

/**
 * Class Aheadworks\RewardPoints\Ui\Component\Filters\Type\CustomerName
 */
class CustomerName extends Select
{
    /**
     * @param string $key
     * @return array
     */
    public function getConfig($key = null)
    {
        $config = $this->getData('config');
        if (null != $key && isset($config[$key])) {
            return $config[$key];
        }
        return $config;
    }

    /**
     *  {@inheritDoc}
     */
    protected function applyFilter()
    {
        if (isset($this->filterData[$this->getName()])) {
            $value = $this->filterData[$this->getName()];

            if (!empty($value)) {
                $conditionType = 'like';
                $field = $this->getConfig('index');
                $filter = $this->filterBuilder->setConditionType($conditionType)
                    ->setField($field)
                    ->setValue($value)
                    ->create();

                $this->getContext()->getDataProvider()->addFilter($filter);
            }
        }
    }
}
