<?php
namespace Magento\Checkout\Model\Session;

/**
 * Interceptor class for @see \Magento\Checkout\Model\Session
 */
class Interceptor extends \Magento\Checkout\Model\Session implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Request\Http $request, \Magento\Framework\Session\SidResolverInterface $sidResolver, \Magento\Framework\Session\Config\ConfigInterface $sessionConfig, \Magento\Framework\Session\SaveHandlerInterface $saveHandler, \Magento\Framework\Session\ValidatorInterface $validator, \Magento\Framework\Session\StorageInterface $storage, \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager, \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory, \Magento\Framework\App\State $appState, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Customer\Model\Session $customerSession, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory, \Magento\Quote\Model\QuoteFactory $quoteFactory)
    {
        $this->___init();
        parent::__construct($request, $sidResolver, $sessionConfig, $saveHandler, $validator, $storage, $cookieManager, $cookieMetadataFactory, $appState, $orderFactory, $customerSession, $quoteRepository, $remoteAddress, $eventManager, $storeManager, $customerRepository, $quoteIdMaskFactory, $quoteFactory);
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
