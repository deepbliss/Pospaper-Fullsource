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

namespace ParadoxLabs\Subscriptions\Model\Service;

/**
 * Currency Manager
 */
class CurrencyManager
{
    /**
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected $currencyFactory;

    /**
     * @var \Magento\Directory\Model\Currency[]
     */
    protected $currencies = [];

    /**
     * Data constructor.
     *
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     */
    public function __construct(
        \Magento\Directory\Model\CurrencyFactory $currencyFactory
    ) {
        $this->currencyFactory = $currencyFactory;
    }

    /**
     * Convert price from one currency to another.
     *
     * @param float $price
     * @param string $from Valid and configured Magento base currency code
     * @param string $to Valid and configured Magento user currency code
     * @return float
     */
    public function convertPriceCurrency($price, $from, $to)
    {
        if ($from === null || $to === null || $from === $to) {
            return $price;
        }

        $currency = $this->getCurrencyByCode($from);

        return $currency->convert($price, $to);
    }

    /**
     * Get currency object by code.
     *
     * @param string $code
     * @return \Magento\Directory\Model\Currency
     */
    public function getCurrencyByCode($code)
    {
        if (!isset($this->currencies[ $code ])) {
            $this->currencies[ $code ] = $this->currencyFactory->create();
            $this->currencies[ $code ]->load($code);
        }

        return $this->currencies[ $code ];
    }
}
