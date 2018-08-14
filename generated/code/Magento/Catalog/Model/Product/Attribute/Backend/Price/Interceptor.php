<?php
namespace Magento\Catalog\Model\Product\Attribute\Backend\Price;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Product\Attribute\Backend\Price
 */
class Interceptor extends \Magento\Catalog\Model\Product\Attribute\Backend\Price implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Directory\Model\CurrencyFactory $currencyFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Helper\Data $catalogData, \Magento\Framework\App\Config\ScopeConfigInterface $config, \Magento\Framework\Locale\FormatInterface $localeFormat, \Magento\Catalog\Model\Attribute\ScopeOverriddenValue $scopeOverriddenValue = null)
    {
        $this->___init();
        parent::__construct($currencyFactory, $storeManager, $catalogData, $config, $localeFormat, $scopeOverriddenValue);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate($object);
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }
}
