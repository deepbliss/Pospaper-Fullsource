<?php
namespace Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection;

/**
 * Interceptor class for @see \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection
 */
class Interceptor extends \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Data\Collection\EntityFactory $entityFactory, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Eav\Model\Config $eavConfig, \Magento\Framework\App\ResourceConnection $resource, \Magento\Eav\Model\EntityFactory $eavEntityFactory, \Magento\Catalog\Model\ResourceModel\Helper $resourceHelper, \Magento\Framework\Validator\UniversalFactory $universalFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Module\Manager $moduleManager, \Magento\Catalog\Model\Indexer\Product\Flat\State $catalogProductFlatState, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory, \Magento\Catalog\Model\ResourceModel\Url $catalogUrl, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\Stdlib\DateTime $dateTime, \Magento\Customer\Api\GroupManagementInterface $groupManagement, \Magento\Search\Model\QueryFactory $catalogSearchData, \Magento\Framework\Search\Request\Builder $requestBuilder, \Magento\Search\Model\SearchEngine $searchEngine, \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory, \Magento\Framework\DB\Adapter\AdapterInterface $connection = null, $searchRequestName = 'catalog_view_container', \Magento\Framework\Api\Search\SearchResultFactory $searchResultFactory = null, \Magento\Catalog\Model\ResourceModel\Product\Collection\ProductLimitationFactory $productLimitationFactory = null, \Magento\Framework\EntityManager\MetadataPool $metadataPool = null)
    {
        $this->___init();
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $eavConfig, $resource, $eavEntityFactory, $resourceHelper, $universalFactory, $storeManager, $moduleManager, $catalogProductFlatState, $scopeConfig, $productOptionFactory, $catalogUrl, $localeDate, $customerSession, $dateTime, $groupManagement, $catalogSearchData, $requestBuilder, $searchEngine, $temporaryStorageFactory, $connection, $searchRequestName, $searchResultFactory, $productLimitationFactory, $metadataPool);
    }

    /**
     * {@inheritdoc}
     */
    public function load($printQuery = false, $logQuery = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'load');
        if (!$pluginInfo) {
            return parent::load($printQuery, $logQuery);
        } else {
            return $this->___callPlugins('load', func_get_args(), $pluginInfo);
        }
    }
}
