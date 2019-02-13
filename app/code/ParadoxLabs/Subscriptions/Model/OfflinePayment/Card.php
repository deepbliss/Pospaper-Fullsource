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

namespace ParadoxLabs\Subscriptions\Model\OfflinePayment;

/**
 * Card Class
 *
 * This is a container for offline payment methods. As needed (on the fly), each offline method will be generated a
 * OfflinePayment\Card instance. These instances will not persist, but it lets us treat them identical to stored
 * card instances (no special cases everywhere payment is touched).
 */
class Card extends \ParadoxLabs\TokenBase\Model\Card
{
    /**
     * Identifier getter
     *
     * @return mixed
     */
    public function getId()
    {
        return 0;
    }

    /**
     * Get hash, generate if necessary
     *
     * @return string
     */
    public function getHash()
    {
        return $this->getMethod();
    }

    /**
     * Get card label (formatted number).
     *
     * @param bool $includeType
     * @return string|\Magento\Framework\Phrase
     */
    public function getLabel($includeType = true)
    {
        /**
         * By default, we'll make the "card label" the payment method title.
         */
        $title = $this->getMethodInstance()->getConfigData('title');

        /**
         * If available, we'll add PO number to it.
         */
        /** @var \Magento\Payment\Model\Info $infoInstance */
        $infoInstance = $this->getInfoInstance();

        if ($infoInstance instanceof \Magento\Payment\Model\InfoInterface
            && !empty($infoInstance->getPoNumber())) {
            return __(
                '%1: %2',
                $title,
                $infoInstance->getPoNumber()
            );
        }

        return $title;
    }

    /**
     * Get expires
     *
     * @return string
     */
    public function getExpires()
    {
        return '';
    }

    /**
     * @return bool
     */
    public function isSaveAllowed()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isModified()
    {
        return false;
    }
}
