<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model;

use Magento\CheckoutAgreements\Model\AgreementsProvider;

class Config
{
    const CONFIG_PATH_CUSTOM_BLOCK = self:: AMASTY_CHECKOUT_SECTION . 'custom_blocks/';

    const AMASTY_CHECKOUT_SECTION = 'amasty_checkout/';

    const GROUP_BLOCK = 'block_names/';

    const ADDITIONAL_OPTIONS = 'additional_options/';

    const VALUE_ORDER_TOTALS = 'order_totals';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param string $position
     * @return boolean
     */
    public function getCustomBlockIdByPosition($position)
    {
        return $this->getConfigValueByPath(self::CONFIG_PATH_CUSTOM_BLOCK . $position . '_block_id');
    }

    /**
     * @return mixed
     */
    public function getPlaceDisplayTermsAndConditions()
    {
        return $this->getAddionalOptions('display_agreements');
    }

    /**
     * @param $path
     * @param null $storeId
     * @param string $scope
     * @return mixed
     */
    public function getConfigValueByPath(
        $path,
        $storeId = null,
        $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE
    ) {
        return $this->scopeConfig->getValue($path, $scope, $storeId);
    }

    /**
     * @param $path
     *
     * @return mixed
     */
    public function getBlockNames($path)
    {
        return $this->getConfigValueByPath(self::AMASTY_CHECKOUT_SECTION . self::GROUP_BLOCK . $path);
    }

    /**
     * @param $path
     *
     * @return mixed
     */
    public function getAddionalOptions($path)
    {
        return $this->getConfigValueByPath(self::AMASTY_CHECKOUT_SECTION . self::ADDITIONAL_OPTIONS .  $path);
    }

    /**
     * @return bool
     */
    public function isSetAgreements()
    {
        return $this->isSetConfigValue(AgreementsProvider::PATH_ENABLED);
    }

    /**
     * @param        $path
     * @param null   $storeId
     * @param string $scope
     *
     * @return bool
     */
    private function isSetConfigValue(
        $path,
        $storeId = null,
        $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE
    ) {
        return $this->scopeConfig->isSetFlag($path, $scope, $storeId);
    }
}
