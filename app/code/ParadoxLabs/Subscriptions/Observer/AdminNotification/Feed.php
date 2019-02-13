<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <support@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Observer\AdminNotification;

/**
 * Check for extension updates/notifications and add any to the system.
 */
class Feed extends \ParadoxLabs\TokenBase\Observer\AdminNotification\Feed
{
    /**
     * Retrieve feed url
     *
     * @return string
     */
    public function getFeedUrl()
    {
        $methods   = [];
        $methods[] = 'subscriptions';

        $this->_feedUrl = 'https://store.paradoxlabs.com/updates.php?key=' . implode(',', $methods)
            . '&version=' . $this->getModuleVersion('ParadoxLabs_Subscriptions');

        return $this->_feedUrl;
    }
}
