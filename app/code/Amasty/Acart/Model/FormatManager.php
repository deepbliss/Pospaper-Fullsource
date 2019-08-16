<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

use Magento\Framework\Pricing\PriceCurrencyInterface;

class FormatManager extends \Magento\Framework\DataObject
{
    const TYPE_HISTORY = 'history';

    const TYPE_QUOTE = 'quote';

    const TYPE_RULE_QUOTE = 'rule_quote';

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $dateTime;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var array
     */
    protected $config = [];

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateTime,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        $this->dateTime = $dateTime;
        $this->priceCurrency = $priceCurrency;
        parent::__construct($data);
    }

    public function init($config)
    {
        $this->config = $config;

        return $this;
    }

    public function formatDate($type, $field)
    {
        $ret = null;
        $object = isset($this->config[$type]) ? $this->config[$type] : null;

        if ($object) {
            $ret = $this->dateTime->formatDate($object->getData($field), \IntlDateFormatter::MEDIUM);
        }

        return $ret;
    }

    public function formatTime($type, $field)
    {
        $ret = null;
        $object = isset($this->config[$type]) ? $this->config[$type] : null;

        if ($object) {
            $ret = $this->dateTime->formatDate($object->getData($field), \IntlDateFormatter::MEDIUM, true);
        }

        return $ret;
    }

    public function formatPrice($type, $field)
    {
        $object = $this->config[$type];

        return $this->priceCurrency->format(
            $object->getData($field),
            true,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            $object->getStore()
        );
    }
}
