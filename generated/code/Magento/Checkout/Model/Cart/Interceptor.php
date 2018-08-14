<?php
namespace Magento\Checkout\Model\Cart;

/**
 * Interceptor class for @see \Magento\Checkout\Model\Cart
 */
class Interceptor extends \Magento\Checkout\Model\Cart implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Checkout\Model\ResourceModel\Cart $resourceCart, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry, \Magento\CatalogInventory\Api\StockStateInterface $stockState, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, array $data = array())
    {
        $this->___init();
        parent::__construct($eventManager, $scopeConfig, $storeManager, $resourceCart, $checkoutSession, $customerSession, $messageManager, $stockRegistry, $stockState, $quoteRepository, $productRepository, $data);
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
}
