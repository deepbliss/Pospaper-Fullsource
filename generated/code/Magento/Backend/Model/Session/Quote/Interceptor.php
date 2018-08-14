<?php
namespace Magento\Backend\Model\Session\Quote;

/**
 * Interceptor class for @see \Magento\Backend\Model\Session\Quote
 */
class Interceptor extends \Magento\Backend\Model\Session\Quote implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Request\Http $request, \Magento\Framework\Session\SidResolverInterface $sidResolver, \Magento\Framework\Session\Config\ConfigInterface $sessionConfig, \Magento\Framework\Session\SaveHandlerInterface $saveHandler, \Magento\Framework\Session\ValidatorInterface $validator, \Magento\Framework\Session\StorageInterface $storage, \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager, \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory, \Magento\Framework\App\State $appState, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Customer\Api\GroupManagementInterface $groupManagement, \Magento\Quote\Model\QuoteFactory $quoteFactory)
    {
        $this->___init();
        parent::__construct($request, $sidResolver, $sessionConfig, $saveHandler, $validator, $storage, $cookieManager, $cookieMetadataFactory, $appState, $customerRepository, $quoteRepository, $orderFactory, $storeManager, $groupManagement, $quoteFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'start');
        if (!$pluginInfo) {
            return parent::start();
        } else {
            return $this->___callPlugins('start', func_get_args(), $pluginInfo);
        }
    }
}
