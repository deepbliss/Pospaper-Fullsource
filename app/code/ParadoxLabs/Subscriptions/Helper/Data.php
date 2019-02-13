<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Helper;

/**
 * General helper
 */
class Data extends DataLegacy
{
    /**
     * Get subscription interval (frequency unit) from the given string. For legacy purposes only.
     *
     * @param string $label
     * @param string $oneString
     * @return int
     */
    public function getIntervalFromString($label, $oneString)
    {
        preg_match("/(\d+) /", $label, $matches);

        $interval = 0;

        if (strpos($label, $oneString) !== false) {
            $interval = 1;
        } elseif (isset($matches[1]) && $matches[1] > 0) {
            $interval = (int)$matches[1];
        }

        return $interval;
    }

    /**
     * Get the given datakey from an array or DataObject, because we don't know what's what.
     *
     * @param array|\Magento\Framework\DataObject $thing
     * @param string $key
     * @return mixed|null
     */
    public function getDataValue($thing, $key)
    {
        if ($thing instanceof \Magento\Framework\DataObject) {
            return $thing->getData($key);
        }

        if (is_array($thing) && isset($thing[$key])) {
            return $thing[$key];
        }

        return null;
    }
}
