<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Amasty\Acart\Model\Country as Country;
use Amasty\Geoip\Model\Geolocation as Geolocation;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Store\Model\StoreManagerInterface;

class QuoteEmail extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Amasty\Acart\Model\Config
     */
    private $config;

    /**
     * @var \Amasty\Acart\Model\Country
     */
    private $country;

    /**
     * @var Geolocation
     */
    private $geolocation;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        Context $context,
        Registry $registry,
        Config $config,
        Country $country,
        Geolocation $geolocation,
        RemoteAddress $remoteAddress,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context, $registry);
        $this->config = $config;
        $this->country = $country;
        $this->geolocation = $geolocation;
        $this->remoteAddress = $remoteAddress;
        $this->storeManager = $storeManager;
    }

    public function _construct()
    {
        $this->_init('Amasty\Acart\Model\ResourceModel\QuoteEmail');
    }

    /**
     * @return bool
     */
    public function isNeedLogEmail()
    {
        $isNeedLogEmail = true;
        $storeId = $this->storeManager->getStore()->getId();

        if ($this->config->isDisableLoggingForGuests($storeId)) {
            $ip = $this->remoteAddress->getRemoteAddress();
            $geolocationData = $this->geolocation->locate($ip);
            $countryCode = $geolocationData->getData('country');

            if (!$countryCode || $this->country->isEEACountry($countryCode)) {
                $isNeedLogEmail = false;
            }
        }

        return $isNeedLogEmail;
    }
}
