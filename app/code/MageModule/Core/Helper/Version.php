<?php
/**
 * Copyright (c) 2018 MageModule: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author       MageModule admin@magemodule.com
 * @copyright   2018 MageModule
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Helper;

class Version extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Checks validity of a version number
     *
     * @param string|float $value
     *
     * @return bool
     */
    public function isValidVersionNumber($value)
    {
        return strlen($value) &&
               version_compare($value, '0.0.001', '>=') &&
               version_compare($value, '1000.0.0', '<=');
    }

    /**
     * @param string|float $value
     *
     * @return int
     */
    public function getMajorVersion($value)
    {
        $parts = explode('.', $value);

        return isset($parts[0]) ? (int)$parts[0] : 0;
    }

    /**
     * @param string|float $version - full version number
     * @param int          $add
     *
     * @return string
     */
    public function addMajorVersion($version, $add)
    {
        $major = (int)$this->getMajorVersion($version);
        $minor = (int)$this->getMinorVersion($version);
        $patch = (int)$this->getPatchVersion($version);

        $major += $add;

        return $major . '.' . $minor . '.' . $patch;
    }

    /**
     * @param string|float $value
     *
     * @return int
     */
    public function getMinorVersion($value)
    {
        $parts = explode('.', $value);

        return isset($parts[1]) ? (int)$parts[1] : 0;
    }

    /**
     * @param string|float $version - full version number
     * @param int          $add
     *
     * @return string
     */
    public function addMinorVersion($version, $add)
    {
        $major = (int)$this->getMajorVersion($version);
        $minor = (int)$this->getMinorVersion($version);
        $patch = (int)$this->getPatchVersion($version);

        $minor += $add;

        return $major . '.' . $minor . '.' . $patch;
    }

    /**
     * @param string|float $value
     *
     * @return int
     */
    public function getPatchVersion($value)
    {
        $parts = explode('.', $value);

        return isset($parts[2]) ? (int)$parts[2] : 0;
    }

    /**
     * @param string|float $version - full version number
     * @param int          $add
     *
     * @return string
     */
    public function addPatchVersion($version, $add)
    {
        $major = (int)$this->getMajorVersion($version);
        $minor = (int)$this->getMinorVersion($version);
        $patch = (int)$this->getPatchVersion($version);

        $patch += $add;

        return $major . '.' . $minor . '.' . $patch;
    }
}
