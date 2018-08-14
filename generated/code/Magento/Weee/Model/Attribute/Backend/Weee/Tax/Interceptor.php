<?php
namespace Magento\Weee\Model\Attribute\Backend\Weee\Tax;

/**
 * Interceptor class for @see \Magento\Weee\Model\Attribute\Backend\Weee\Tax
 */
class Interceptor extends \Magento\Weee\Model\Attribute\Backend\Weee\Tax implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Directory\Model\CurrencyFactory $currencyFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Helper\Data $catalogData, \Magento\Framework\App\Config\ScopeConfigInterface $config, \Magento\Framework\Locale\FormatInterface $localeFormat, \Magento\Directory\Helper\Data $directoryHelper, \Magento\Weee\Model\ResourceModel\Attribute\Backend\Weee\Tax $attributeTax, \Magento\Catalog\Model\Attribute\ScopeOverriddenValue $scopeOverriddenValue = null)
    {
        $this->___init();
        parent::__construct($currencyFactory, $storeManager, $catalogData, $config, $localeFormat, $directoryHelper, $attributeTax, $scopeOverriddenValue);
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
