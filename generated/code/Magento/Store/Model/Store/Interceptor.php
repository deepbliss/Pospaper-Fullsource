<?php
namespace Magento\Store\Model\Store;

/**
 * Interceptor class for @see \Magento\Store\Model\Store
 */
class Interceptor extends \Magento\Store\Model\Store implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Store\Model\ResourceModel\Store $resource, \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase, \Magento\Framework\App\Cache\Type\Config $configCacheType, \Magento\Framework\UrlInterface $url, \Magento\Framework\App\RequestInterface $request, \Magento\Config\Model\ResourceModel\Config\Data $configDataResource, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\App\Config\ReinitableConfigInterface $config, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Session\SidResolverInterface $sidResolver, \Magento\Framework\App\Http\Context $httpContext, \Magento\Framework\Session\SessionManagerInterface $session, \Magento\Directory\Model\CurrencyFactory $currencyFactory, \Magento\Store\Model\Information $information, $currencyInstalled, \Magento\Store\Api\GroupRepositoryInterface $groupRepository, \Magento\Store\Api\WebsiteRepositoryInterface $websiteRepository, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, $isCustomEntryPoint = false, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $resource, $coreFileStorageDatabase, $configCacheType, $url, $request, $configDataResource, $filesystem, $config, $storeManager, $sidResolver, $httpContext, $session, $currencyFactory, $information, $currencyInstalled, $groupRepository, $websiteRepository, $resourceCollection, $isCustomEntryPoint, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseUrl($type = 'link', $secure = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseUrl');
        if (!$pluginInfo) {
            return parent::getBaseUrl($type, $secure);
        } else {
            return $this->___callPlugins('getBaseUrl', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save();
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete();
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }
}
