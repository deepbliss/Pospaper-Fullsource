<?php

namespace Pos\Shipping\Plugin;

class PosStateFilter
{
    protected $disallowed = array(
        'Guam',
        'Puerto Rico',
        'Palau',
        'Virgin Islands',
        'Northern Mariana Islands',
        'Marshall Islands',
        'Federated States Of Micronesia',
        'American Samoa',
        'Armed Forces Africa',
        'Armed Forces Americas',
        'Armed Forces Canada',
        'Armed Forces Europe',
        'Armed Forces Middle East',
        'Armed Forces Pacific',
    );

    public function afterToOptionArray($subject, $options)
    {
        $result = array_filter($options, function ($option) {
            if (isset($option['label']))
                return !in_array($option['label'], $this->disallowed);
            return true;
        });

        return $result;
    }
}