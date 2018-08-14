<?php
namespace Magento\InstantPurchase\Controller\Button\PlaceOrder;

/**
 * Interceptor class for @see \Magento\InstantPurchase\Controller\Button\PlaceOrder
 */
class Interceptor extends \Magento\InstantPurchase\Controller\Button\PlaceOrder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Magento\InstantPurchase\Model\InstantPurchaseOptionLoadingFactory $instantPurchaseOptionLoadingFactory, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\InstantPurchase\Model\PlaceOrder $placeOrder, \Magento\Sales\Api\OrderRepositoryInterface $orderRepository)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $customerSession, $formKeyValidator, $instantPurchaseOptionLoadingFactory, $productRepository, $placeOrder, $orderRepository);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
