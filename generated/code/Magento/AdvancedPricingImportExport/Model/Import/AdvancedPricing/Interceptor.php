<?php
namespace Magento\AdvancedPricingImportExport\Model\Import\AdvancedPricing;

/**
 * Interceptor class for @see \Magento\AdvancedPricingImportExport\Model\Import\AdvancedPricing
 */
class Interceptor extends \Magento\AdvancedPricingImportExport\Model\Import\AdvancedPricing implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\ImportExport\Helper\Data $importExportData, \Magento\ImportExport\Model\ResourceModel\Import\Data $importData, \Magento\Eav\Model\Config $config, \Magento\Framework\App\ResourceConnection $resource, \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper, \Magento\Framework\Stdlib\StringUtils $string, \Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface $errorAggregator, \Magento\Framework\Stdlib\DateTime\DateTime $dateTime, \Magento\CatalogImportExport\Model\Import\Proxy\Product\ResourceModelFactory $resourceFactory, \Magento\Catalog\Model\Product $productModel, \Magento\Catalog\Helper\Data $catalogData, \Magento\CatalogImportExport\Model\Import\Product\StoreResolver $storeResolver, \Magento\CatalogImportExport\Model\Import\Product $importProduct, \Magento\AdvancedPricingImportExport\Model\Import\AdvancedPricing\Validator $validator, \Magento\AdvancedPricingImportExport\Model\Import\AdvancedPricing\Validator\Website $websiteValidator, \Magento\AdvancedPricingImportExport\Model\Import\AdvancedPricing\Validator\TierPrice $tierPriceValidator)
    {
        $this->___init();
        parent::__construct($jsonHelper, $importExportData, $importData, $config, $resource, $resourceHelper, $string, $errorAggregator, $dateTime, $resourceFactory, $productModel, $catalogData, $storeResolver, $importProduct, $validator, $websiteValidator, $tierPriceValidator);
    }

    /**
     * {@inheritdoc}
     */
    public function saveAdvancedPricing()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveAdvancedPricing');
        if (!$pluginInfo) {
            return parent::saveAdvancedPricing();
        } else {
            return $this->___callPlugins('saveAdvancedPricing', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteAdvancedPricing()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'deleteAdvancedPricing');
        if (!$pluginInfo) {
            return parent::deleteAdvancedPricing();
        } else {
            return $this->___callPlugins('deleteAdvancedPricing', func_get_args(), $pluginInfo);
        }
    }
}
