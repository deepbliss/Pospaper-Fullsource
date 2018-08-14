<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class DeliveryDate
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getDeliveryDays()
    {
        $days = $this->scopeConfig->getValue(
            'amasty_checkout/delivery_date/available_days', ScopeInterface::SCOPE_STORE
        );

        if (!$days)
            return [];

        $days = explode(',', $days);

        foreach ($days as &$day) {
            $day = (int)$day;
        }

        return $days;
    }

    public function getDeliveryHours()
    {
        $hoursSetting = trim($this->scopeConfig->getValue(
            'amasty_checkout/delivery_date/available_hours', ScopeInterface::SCOPE_STORE
        ));

        $hours = [];

        $intervals = preg_split('#\s*,\s*#', $hoursSetting, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($intervals as $interval) {
            if (preg_match('#(?P<lower>\d+)(\s*-\s*(?P<upper>\d+))?#', $interval, $matches)) {
                $lower = (int)$matches['lower'];
                if ($lower > 23) {
                    continue;
                }

                if (isset($matches['upper'])) {
                    $upper = (int)$matches['upper'];
                    if ($upper > 24) {
                        continue;
                    }

                    $upper--;

                    if ($lower > $upper) {
                        continue;
                    }
                } else {
                    $upper = $lower;
                }

                $hours = array_merge($hours, range($lower, $upper));
            }
        }

        if (!$hours) {
            $hours = range(0, 23);
        }
        else {
            $hours = array_unique($hours);
            asort($hours);
        }

        $options = [[
                        'value' => '-1',
                        'label' => ' ',
                    ]];

        foreach ($hours as $hour) {
            $options [] = [
                'value' => $hour,
                'label' => $hour . ':00 - ' . (($hour) + 1) . ':00',
            ];
        }

        return $options;
    }
}
