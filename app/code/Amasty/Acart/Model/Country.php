<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

use Amasty\Acart\Model\Config as Config;

class Country
{
    /**
     * @var \Amasty\Acart\Model\Config
     */
    private $config;

    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @param $countryCode
     *
     * @return bool
     */
    public function isEEACountry($countryCode)
    {
        $eeaCountries = explode(',', $this->config->getEEACountries());

        return in_array($countryCode, $eeaCountries);
    }
}
